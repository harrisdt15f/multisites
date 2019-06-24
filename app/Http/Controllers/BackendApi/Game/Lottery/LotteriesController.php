<?php

namespace App\Http\Controllers\BackendApi\Game\Lottery;

use App\Events\IssueGenerateEvent;
use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Game\Lottery\LotteriesEditMethodRequest;
use App\Http\Requests\Backend\Game\Lottery\LotteriesGenerateIssueRequest;
use App\Http\Requests\Backend\Game\Lottery\LotteriesInputNumberRequest;
use App\Http\Requests\Backend\Game\Lottery\LotteriesListsRequest;
use App\Http\Requests\Backend\Game\Lottery\LotteriesLotteriesSwitchRequest;
use App\Http\Requests\Backend\Game\Lottery\LotteriesMethodGroupSwitchRequest;
use App\Http\Requests\Backend\Game\Lottery\LotteriesMethodRowSwitchRequest;
use App\Http\Requests\Backend\Game\Lottery\LotteriesMethodSwitchRequest;
use App\Jobs\Lottery\Encode\IssueEncoder;
use App\Models\Game\Lottery\LotteryIssue;
use App\Models\Game\Lottery\LotteryList;
use App\Models\Game\Lottery\LotteryMethod;
use App\Models\Game\Lottery\LotterySerie;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class LotteriesController extends BackEndApiMainController
{
    protected $eloqM = 'Game\Lottery\LotteryList';
    protected $methodCacheName = 'play_method_list';
    protected $lotteryIssueEloq = 'Game\Lottery\LotteryIssue';

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
     * @param  LotteriesListsRequest  $request
     * @return JsonResponse
     */
    public function lists(LotteriesListsRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $lotteriesEloq = $this->eloqM::where([
            ['series_id', '=', $inputDatas['series_id']],
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
    public function methodLists(): JsonResponse
    {
        $method = [];
        $redisKey = 'play_method_list';
        if (Cache::has($redisKey)) {
            $method = Cache::get($redisKey);
        } else {
            $seriesEloq = LotterySerie::get();
            foreach ($seriesEloq as $seriesIthem) {
                $lottery = $seriesIthem->lotteries; //->where('status',1)
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
                    foreach ($methodGrops as $mgItems) {
                        $curentMethodGroup = $mgItems->method_group;
                        $methodGroupBool = $mgItems->where('lottery_id', $currentLotteryId)->where('method_group',
                            $curentMethodGroup)->where('status', 1)->exists();
                        $methodGroupstatus = $methodGroupBool ? LotteryMethod::OPEN : LotteryMethod::CLOSE;
                        //玩法组 data
                        $methodGroup = $this->methodData($currentLotteryId, $curentMethodGroup, $methodGroupstatus);
                        //$temp 插入玩法组data
                        $temp[$seriesId][$currentLotteryId]['child'][$curentMethodGroup]['data'] = $methodGroup;
                        $temp[$seriesId][$currentLotteryId]['child'][$curentMethodGroup]['child'] = [];
                        //#########################################################
                        $methodRows = $mgItems->methodRows->where('lottery_id', $currentLotteryId);
                        foreach ($methodRows as $mrItems) {
                            $currentMethodRow = $mrItems->method_row;
                            $methodRowBool = $mrItems->where('lottery_id', $currentLotteryId)->where('method_group',
                                $curentMethodGroup)->where('method_row', $currentMethodRow)->where('status',
                                1)->exists();
                            $methodRowstatus = $methodRowBool ? LotteryMethod::OPEN : LotteryMethod::CLOSE;
                            //玩法行 data
                            $methodRow = $this->methodData($currentLotteryId, $curentMethodGroup, $methodRowstatus,
                                $currentMethodRow);
                            //$temp 插入玩法行data
                            $temp[$seriesId][$currentLotteryId]['child'][$curentMethodGroup]['child'][$mrItems->method_row]['data'] = $methodRow;
                            //玩法data
                            //###########################################################################################
                            $methodData = LotteryMethod::where('lottery_id', $currentLotteryId)->where('method_group',
                                $curentMethodGroup)->where('method_row', $currentMethodRow)->get();
                            // $methodData = $mrItems->methodDetails
                            //     ->where('method_group', $curentMethodGroup)
                            //     ->where('method_row', $currentMethodRow);
                            //$temp 插入玩法data
                            $temp[$seriesId][$currentLotteryId]['child'][$curentMethodGroup]['child'][$mrItems->method_row]['child'] = $methodData;
                        }
                    }
                }
                $method = array_merge($method, $temp);
            }
            $hourToStore = 24;
            $expiresAt = Carbon::now()->addHours($hourToStore);
            Cache::put($redisKey, $method, $expiresAt);
        }
        return $this->msgOut(true, $method);
    }

    /**
     * 获取奖期列表接口。
     * @return JsonResponse
     */
    public function issueLists(): JsonResponse
    {
        $eloqM = $this->modelWithNameSpace($this->lotteryIssueEloq);
        $seriesId = $this->inputs['series_id'] ?? '';
//        {"method":"whereIn","key":"id","value":["cqssc","xjssc","hljssc","zx1fc","txffc"]}
        //        $extraWhereConditions = Arr::wrap(json_decode($this->inputs['extra_where'], true));
        if (!empty($seriesId)) {
            $lotteryEnNames = LotteryList::where([
                ['series_id', '=', $seriesId],
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
        $fixedJoin = 1;
        $withTable = 'lottery';
        $afewMinutes = Carbon::now()->subMinute('20')->timestamp;
        $this->inputs['time_condtions'] = $this->inputs['time_condtions'] ?? '[["end_time",">=",'.$afewMinutes.']]'; // 从现在开始。如果。没有时间字段的话，就用当前时间以上的显示
        $data = $this->generateSearchQuery($eloqM, $searchAbleFields, $fixedJoin, $withTable, null, $orderFields,
            $orderFlow);
        return $this->msgOut(true, $data);
    }

    /**
     * 生成奖期
     * @param  LotteriesGenerateIssueRequest  $request
     * @return JsonResponse
     */
    public function generateIssue(LotteriesGenerateIssueRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        event(new IssueGenerateEvent($inputDatas));
        return $this->msgOut(true);
    }

    /**
     * 彩种开关
     * @param  LotteriesLotteriesSwitchRequest  $request
     * @return JsonResponse
     */
    public function lotteriesSwitch(LotteriesLotteriesSwitchRequest $request): ?JsonResponse
    {
        $inputDatas = $request->validated();
        $lotteriesEloq = $this->eloqM::find($inputDatas['id']);
        try {
            $lotteriesEloq->status = $inputDatas['status'];
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

    /**
     * 玩法组开关
     * @param  LotteriesMethodGroupSwitchRequest  $request
     * @return JsonResponse
     */
    public function methodGroupSwitch(LotteriesMethodGroupSwitchRequest $request): ?JsonResponse
    {
        $inputDatas = $request->validated();
        $methodGroupIds = LotteryMethod::where('lottery_id', $inputDatas['lottery_id'])->where('method_group',
            $inputDatas['method_group'])->pluck('id');
        if (empty($methodGroupIds)) {
            return $this->msgOut(false, [], '101701');
        }
        try {
            $updateDate = ['status' => $inputDatas['status']];
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

    /**
     * 玩法行开关
     * @param  LotteriesMethodRowSwitchRequest  $request
     * @return JsonResponse
     */
    public function methodRowSwitch(LotteriesMethodRowSwitchRequest $request): ?JsonResponse
    {
        $inputDatas = $request->validated();
        $methodGroupIds = LotteryMethod::where('lottery_id', $inputDatas['lottery_id'])->where('method_group',
            $inputDatas['method_group'])->where('method_row', $inputDatas['method_row'])->pluck('id');
        if (empty($methodGroupIds)) {
            return $this->msgOut(false, [], '101702');
        }
        try {
            $updateDate = ['status' => $inputDatas['status']];
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

    /**
     * 玩法开关
     * @param  LotteriesMethodSwitchRequest  $request
     * @return JsonResponse
     */
    public function methodSwitch(LotteriesMethodSwitchRequest $request): ?JsonResponse
    {
        $inputDatas = $request->validated();
        $pastData = LotteryMethod::find($inputDatas['id']);
        if (empty($pastData)) {
            return $this->msgOut(false, [], '101703');
        }
        try {
            $pastData->status = $inputDatas['status'];
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

    /**
     * 清理玩法缓存
     * @return void
     */
    public function clearMethodCache(): void
    {
        $redisKey = $this->methodCacheName;
        if (Cache::has($redisKey)) {
            Cache::forget($redisKey);
        }
    }

    /**
     * 编辑玩法
     * @param  LotteriesEditMethodRequest  $request
     * @return JsonResponse
     */
    public function editMethod(LotteriesEditMethodRequest $request): ?JsonResponse
    {
        $inputDatas = $request->validated();
        $pastData = LotteryMethod::find($inputDatas['id']);
        try {
            $pastData->total = $inputDatas['total'];
            $pastData->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 组装玩法组和玩法行data
     * @param  int  $lotteryId  [彩种]
     * @param  int  $methodGroup  [玩法组]
     * @param  int  $status  [开启状态]
     * @param  int  $methodRow  [玩法行]
     * @return array  $dataArr
     */
    public function methodData($lotteryId, $methodGroup, $status, $methodRow = null): array
    {
        $dataArr = [
            'lottery_id' => $lotteryId,
            'method_group' => $methodGroup,
            'status' => $status, //玩法行下是否存在开启状态的玩法
        ];
        if ($methodRow !== null) {
            $dataArr['method_row'] = $methodRow;
        }
        return $dataArr;
    }

    /**
     * 奖期录号
     * @param  LotteriesInputNumberRequest  $request
     * @return JsonResponse
     */
    public function inputCode(LotteriesInputNumberRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $issueEloq = LotteryIssue::where([
            ['issue', '=', $inputDatas['issue']],
            ['lottery_id', $inputDatas['lottery_id']],
            ['end_time', '<=', now()->timestamp]
        ])->first();
        if ($issueEloq === null) {
            return $this->msgOut(false, [], '101703');
        }
        if ($issueEloq->official_code !== null) {
            return $this->msgOut(false, [], '101704');
        }
        $status_encode = LotteryIssue::ENCODED;
        try {
            $issueEloq->status_encode = $status_encode;
            $issueEloq->encode_time = time();
            $issueEloq->official_code = $inputDatas['code'];
            $issueEloq->save();
            if (!empty($issueEloq->toArray())) {
                dispatch(new IssueEncoder($issueEloq->toArray()))->onQueue('issues');
            }
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * @return JsonResponse
     */
    public function lotteriesCodeLength(): JsonResponse
    {
        $datas = $this->eloqM::select('en_name', 'code_length', 'valid_code', 'lottery_type')->get()->toArray();
        return $this->msgOut(true, $datas);
    }
}
