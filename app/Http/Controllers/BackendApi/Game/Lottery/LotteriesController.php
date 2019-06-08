<?php

namespace App\Http\Controllers\BackendApi\Game\Lottery;

use App\Events\IssueGenerateEvent;
use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Game\Lottery\LotteryList;
use App\Models\Game\Lottery\LotteryMethod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class LotteriesController extends BackEndApiMainController
{
    protected $eloqM = 'Game\Lottery\LotteryList';
    protected $methodCacheName = 'play_method_list';
    protected $lotteryIssue = 'Game\Lottery\LotteryIssue';

    public function seriesLists()
    {
        $seriesData = Config::get('game.main.series');
        return $this->msgOut(true, $seriesData);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function lotteriesLists()
    {
        $series = array_keys(Config::get('game.main.series'));
        $seriesStringImploded = implode(',', $series);
        $rule = [
            'series_id' => 'required|in:' . $seriesStringImploded,
        ];
        $validator = Validator::make($this->inputs, $rule);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $lotteriesEloq = $this->eloqM::where([
            ['series_id', '=', $this->inputs['series_id']],
            ['status', '=', 1],
        ])->with([
            'issueRule' => function ($query) {
                $query->select('id', 'lottery_id', 'lottery_name', 'begin_time', 'end_time', 'issue_seconds',
                    'first_time', 'adjust_time', 'encode_time', 'issue_count', 'status', 'created_at', 'updated_at');
            },
        ])->get()->toArray();
        return $this->msgOut(true, $lotteriesEloq);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function lotteriesMethodLists()
    {
        $method = [];
        $redisKey = 'play_method_list';
        if (Cache::has($redisKey)) {
            $method = Cache::get($redisKey);
        } else {
            $seriesList = array_keys(Config::get('game.main.series'));
            foreach ($seriesList as $seriesIthem) {
                $methodEloq = LotteryMethod::where([
                    ['series_id', '=', $seriesIthem],
                ])->first();
                $lotteriesIds = $methodEloq->lotteriesIds; //        dd($lotteriesIds);
                foreach ($lotteriesIds as $litems) {
                    $currentLotteryId = $litems->lottery_id;
                    $temp[$seriesIthem][$currentLotteryId]['data'] = $this->eloqM::select('id', 'cn_name',
                        'status')->where('en_name', $currentLotteryId)->first()->toArray();
                    $temp[$seriesIthem][$currentLotteryId]['child'] = [];
                    $methodGrops = $litems->methodGroups;
                    foreach ($methodGrops as $mgitems) {
                        $curentMethodGroup = $mgitems->method_group;
                        //玩法组 data
                        $methodGroup = ['lottery_id' => $currentLotteryId, 'method_group' => $curentMethodGroup];
                        //玩法组下是否存在开启状态的玩法
                        $checkOpenGroup = LotteryMethod::where('lottery_id', $currentLotteryId)
                            ->where('method_group', $curentMethodGroup)
                            ->where('status', 1)
                            ->first();
                        $methodGroup['status'] = is_null($checkOpenGroup) ? LotteryMethod::CLOSE : LotteryMethod::OPEN;
                        //$temp 插入玩法组data
                        $temp[$seriesIthem][$currentLotteryId]['child'][$curentMethodGroup]['data'] = $methodGroup;
                        $temp[$seriesIthem][$currentLotteryId]['child'][$curentMethodGroup]['child'] = [];
                        $methodRows = $mgitems->methodRows;
                        foreach ($methodRows as $rowitems) {
                            $method_row = $rowitems->method_row;
                            //玩法行 data
                            $methodRow = [
                                'lottery_id' => $currentLotteryId,
                                'method_group' => $curentMethodGroup,
                                'method_row' => $method_row,
                            ];
                            //玩法行下是否存在开启状态的玩法
                            $checkOpenRow = LotteryMethod::where('lottery_id', $currentLotteryId)
                                ->where('method_group', $curentMethodGroup)
                                ->where('method_row', $method_row)
                                ->where('status', 1)
                                ->first();
                            $methodRow['status'] = is_null($checkOpenRow) ? LotteryMethod::CLOSE : LotteryMethod::OPEN;
                            //$temp 插入玩法行data
                            $temp[$seriesIthem][$currentLotteryId]['child'][$curentMethodGroup]['child'][$rowitems->method_row]['data'] = $methodRow;
                            //玩法data
                            $methodData = $rowitems->select('id',
                                'method_name', 'status', 'total')->where('lottery_id', $currentLotteryId)
                                ->where('method_group', $curentMethodGroup)
                                ->where('method_row', $method_row)
                            // ->with('methodDetails')
                                ->get()->toArray();
                            //$temp 插入玩法data
                            $temp[$seriesIthem][$currentLotteryId]['child'][$curentMethodGroup]['child'][$rowitems->method_row]['child'] = $methodData;
                        }
                    }

                }
                $method = array_merge($method, $temp);
            }
            $hourToStore = 24;
            $expiresAt = Carbon::now()->addHours($hourToStore)->diffInMinutes();
            Cache::put($redisKey, $method, $expiresAt);
//            Cache::forever($redisKey, $method);
        }
        return $this->msgOut(true, $method);
    }

    public function lotteryIssueLists()
    {
        $eloqM = $this->modelWithNameSpace($this->lotteryIssue);
        $seriesId = $this->inputs['series_id'] ?? '';
//        {"method":"whereIn","key":"id","value":["cqssc","xjssc","hljssc","zx1fc","txffc"]}
        //        $extraWhereConditions = Arr::wrap(json_decode($this->inputs['extra_where'], true));
        if (!empty($seriesId)) {
            $lotteryEnNames = LotteryList::where('series_id', $seriesId)->get(['en_name']);
            foreach ($lotteryEnNames as $lotteryIthems) {
                $tempLotteryId[] = $lotteryIthems->en_name;
            }
            $this->inputs['extra_where']['method'] = 'whereIn';
            $this->inputs['extra_where']['key'] = 'lottery_id';
            $this->inputs['extra_where']['value'] = $tempLotteryId;
        }
        $searchAbleFields = ['lottery_id', 'issue'];
        $data = $this->generateSearchQuery($eloqM, $searchAbleFields);
        return $this->msgOut(true, $data);
    }

    // 生成奖期
    public function generateIssue()
    {
        $rule = [
            'lottery_id' => 'required',
            'start_time' => 'required|date_format:Y-m-d',
            'end_time' => 'required|date_format:Y-m-d',
//            'start_issue' => 'required|numeric',//
        ];
        $validator = Validator::make($this->inputs, $rule);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        event(new IssueGenerateEvent($this->inputs));
        return $this->msgOut(true);
    }

    //彩种开关
    public function lotteriesSwitch()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'status' => 'required|numeric|in:0,1',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $lotteriesEloq = $this->eloqM::find($this->inputs['id']);
        if (is_null($lotteriesEloq)) {
            return $this->msgOut(false, [], '101700');
        }
        try {
            $lotteriesEloq->status = $this->inputs['status'];
            $lotteriesEloq->save();
            //清理彩种玩法缓存
            $this->clearMethodCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //玩法组开关
    public function methodGroupSwitch()
    {
        $validator = Validator::make($this->inputs, [
            'lottery_id' => 'required|string',
            'method_group' => 'required|string',
            'status' => 'required|numeric|in:0,1',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $methodGroupIds = LotteryMethod::where('lottery_id', $this->inputs['lottery_id'])->where('method_group', $this->inputs['method_group'])->pluck('id');
        if (empty($methodGroupIds)) {
            return $this->msgOut(false, [], '101701');
        }
        try {
            $updateDate = ['status' => $this->inputs['status']];
            LotteryMethod::whereIn('id', $methodGroupIds)->update($updateDate);
            //清理彩种玩法缓存
            $this->clearMethodCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //玩法行开关
    public function methodRowSwitch()
    {
        $validator = Validator::make($this->inputs, [
            'lottery_id' => 'required|string',
            'method_group' => 'required|string',
            'method_row' => 'required|string',
            'status' => 'required|numeric|in:0,1',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $methodGroupIds = LotteryMethod::where('lottery_id', $this->inputs['lottery_id'])->where('method_group', $this->inputs['method_group'])->where('method_row', $this->inputs['method_row'])->pluck('id');
        if (empty($methodGroupIds)) {
            return $this->msgOut(false, [], '101702');
        }
        try {
            $updateDate = ['status' => $this->inputs['status']];
            LotteryMethod::whereIn('id', $methodGroupIds)->update($updateDate);
            //清理彩种玩法缓存
            $this->clearMethodCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //玩法开关
    public function methodSwitch()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'status' => 'required|numeric|in:0,1',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastData = LotteryMethod::find($this->inputs['id']);
        if (empty($pastData)) {
            return $this->msgOut(false, [], '101703');
        }
        try {
            $pastData->status = $this->inputs['status'];
            $pastData->save();
            //清理彩种玩法缓存
            $this->clearMethodCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //清理玩法缓存
    public function clearMethodCache()
    {
        $redisKey = $this->methodCacheName;
        if (Cache::has($redisKey)) {
            Cache::forget($redisKey);
        }
    }

    //编辑玩法
    public function editMethod()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'total' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastData = LotteryMethod::find($this->inputs['id']);
        if (empty($pastData)) {
            return $this->msgOut(false, [], '101703');
        }
        try {
            $pastData->total = $this->inputs['total'];
            $pastData->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
}
