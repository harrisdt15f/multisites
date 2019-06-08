<?php

namespace App\Http\Controllers\BackendApi\Game\Lottery;

use App\Events\IssueGenerateEvent;
use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Game\Lottery\LotteryList;
use App\Models\Game\Lottery\LotteryMethod;
use App\Models\Game\Lottery\LotterySerie;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class LotteriesController extends BackEndApiMainController
{
    protected $eloqM = 'Game\Lottery\LotteryList';
    protected $methodCacheName = 'play_method_list';
    protected $lotteryIssue = 'Game\Lottery\LotteryIssue';

    /**
     * 获取系列接口
     * @return JsonResponse
     */
    public function seriesLists(): JsonResponse
    {
        $seriesData = Config::get('game.main.series');
        return $this->msgOut(true, $seriesData);
    }

    /**
     * 获取彩种接口
     * @return JsonResponse
     */
    public function lotteriesLists(): JsonResponse
    {
        $series = array_keys(Config::get('game.main.series'));
        $seriesStringImploded = implode(',', $series);
        $rule = [
            'series_id' => 'required|in:'.$seriesStringImploded,
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
     * 获取玩法结果。
     * @return JsonResponse
     */
    public function lotteriesMethodLists(): JsonResponse
    {
        $method = [];
        $redisKey = 'play_method_list';
        if (Cache::has($redisKey)) {
            $method = Cache::get($redisKey);
        } else {
            $seriesEloq = LotterySerie::get();
            foreach ($seriesEloq as $seriesIthem) {
                $lottery = $seriesIthem->lotteries;//->where('status',1)
                $seriesId = $seriesIthem->series_name;
                foreach ($lottery as $litems) {
                    $lotteyArr = collect($litems->toArray())
                        ->only(['id', 'cn_name', 'status']);
//                    $methodEloq = $litems->gameMethods;
                    $currentLotteryId = $litems->en_name;
                    $temp[$seriesId][$currentLotteryId]['data'] = $lotteyArr;
                    $temp[$seriesId][$currentLotteryId]['child'] = [];
                    //#########################################################
                    $methodGrops = $litems->methodGroups;
                    try {
                        $methodGroupstatus = $litems->methodGroups->where('status', 1)->first()->status;
                        if (is_null($methodGroupstatus)) {
                            $methodGroupstatus = LotteryMethod::CLOSE;
                        }
                    } catch (\Exception $e) {
                        $methodGroupstatus = LotteryMethod::CLOSE;
                    }
                    foreach ($methodGrops as $mgItems) {
                        $curentMethodGroup = $mgItems->method_group;
                        //玩法组 data
                        $methodGroup = [
                            'lottery_id' => $currentLotteryId,
                            'method_group' => $curentMethodGroup,
                            'status' => $methodGroupstatus//玩法组下是否存在开启状态的玩法
                        ];
                        //$temp 插入玩法组data
                        $temp[$seriesId][$currentLotteryId]['child'][$curentMethodGroup]['data'] = $methodGroup;
                        $temp[$seriesId][$currentLotteryId]['child'][$curentMethodGroup]['child'] = [];
                        //#########################################################
                        $methodRows = $mgItems->methodRows->where('lottery_id',$currentLotteryId);
                        try {
                            $methodRowstatus = $methodRows->where('status', 1)->first()->status;
                            if (is_null($methodRowstatus)) {
                                $methodRowstatus = LotteryMethod::CLOSE;
                            }
                        } catch (\Exception $e) {
                            $methodRowstatus = LotteryMethod::CLOSE;
                        }
                        foreach ($methodRows as $mrItems) {
                            $currentMethodRow = $mrItems->method_row;
                            //玩法行 data
                            $methodRow = [
                                'lottery_id' => $currentLotteryId,
                                'method_group' => $curentMethodGroup,
                                'method_row' => $currentMethodRow,
                                'status' => $methodRowstatus//玩法行下是否存在开启状态的玩法
                            ];
                            //$temp 插入玩法行data
                            $temp[$seriesId][$currentLotteryId]['child'][$curentMethodGroup]['child'][$mrItems->method_row]['data'] = $methodRow;
                            //玩法data
                            $methodData = $mrItems->methodDetails
                                ->where('method_group',$curentMethodGroup)
                                ->where('method_row',$currentMethodRow);
                            //$temp 插入玩法data
                            $temp[$seriesId][$currentLotteryId]['child'][$curentMethodGroup]['child'][$mrItems->method_row]['child'] = $methodData;
                        }
                    }
                }
                $method = array_merge($method, $temp);
            }
            $hourToStore = 24;
            $expiresAt = Carbon::now()->addHours($hourToStore)->diffInMinutes();
            Cache::put($redisKey, $method, $expiresAt);
            Cache::forever($redisKey, $method);
        }
        return $this->msgOut(true, $method);
    }

    /**
     * 获取奖期列表接口。
     * @return JsonResponse
     */
    public function lotteryIssueLists(): JsonResponse
    {
        $eloqM = $this->modelWithNameSpace($this->lotteryIssue);
        $seriesId = $this->inputs['series_id'] ?? '';
//        {"method":"whereIn","key":"id","value":["cqssc","xjssc","hljssc","zx1fc","txffc"]}
        //        $extraWhereConditions = Arr::wrap(json_decode($this->inputs['extra_where'], true));
        if (!empty($seriesId)) {
            $lotteryEnNames = LotteryList::where([
                ['series_id', '=', $seriesId]
            ])->get(['en_name']);
            foreach ($lotteryEnNames as $lotteryIthems) {
                $tempLotteryId[] = $lotteryIthems->en_name;
            }
            $this->inputs['extra_where']['method'] = 'whereIn';
            $this->inputs['extra_where']['key'] = 'lottery_id';
            $this->inputs['extra_where']['value'] = $tempLotteryId;
        }
        $searchAbleFields = ['lottery_id', 'issue'];
        $orderFields = 'begin_time';
        $orderFlow = 'asc';
        $this->inputs['time_condtions'] = $this->inputs['time_condtions'] ?? '[["begin_time",">=",'.Carbon::now()->timestamp.']]';// 从现在开始。如果。没有时间字段的话，就用当前时间以上的显示
        $data = $this->generateSearchQuery($eloqM, $searchAbleFields, 0, null, null, $orderFields, $orderFlow);
        return $this->msgOut(true, $data);
    }

    // 生成奖期
    public function generateIssue(): JsonResponse
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
    public function lotteriesSwitch(): ?JsonResponse
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
    public function methodGroupSwitch(): ?JsonResponse
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
    public function methodRowSwitch(): ?JsonResponse
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
    public function methodSwitch(): ?JsonResponse
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
    public function clearMethodCache(): void
    {
        $redisKey = $this->methodCacheName;
        if (Cache::has($redisKey)) {
            Cache::forget($redisKey);
        }
    }

    //编辑玩法
    public function editMethod(): ?JsonResponse
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
