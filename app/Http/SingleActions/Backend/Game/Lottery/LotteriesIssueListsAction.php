<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-24 16:12:52
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-24 22:04:38
 */
namespace App\Http\SingleActions\Backend\Game\Lottery;

use App\Http\Controllers\backendApi\BackEndApiMainController;
use App\Models\Game\Lottery\LotteryList;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class LotteriesIssueListsAction
{
    protected $model;

    /**
     * @param  LotteryList  $lotteryList
     */
    public function __construct(LotteryList $lotteryList)
    {
        $this->model = $lotteryList;
    }

    /**
     * 获取彩种接口
     * @param   BackEndApiMainController  $contll
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        $eloqM = $contll->modelWithNameSpace($contll->lotteryIssueEloq);
        $seriesId = $contll->inputs['series_id'] ?? '';
//        {"method":"whereIn","key":"id","value":["cqssc","xjssc","hljssc","zx1fc","txffc"]}
        //        $extraWhereConditions = Arr::wrap(json_decode($this->inputs['extra_where'], true));
        if (!empty($seriesId)) {
            $lotteryEnNames = $this->model::where('series_id', $seriesId)->get(['en_name']);
            foreach ($lotteryEnNames as $lotteryIthems) {
                $tempLotteryId[] = $lotteryIthems->en_name;
            }
            $contll->inputs['extra_where']['method'] = 'whereIn';
            $contll->inputs['extra_where']['key'] = 'lottery_id';
            $contll->inputs['extra_where']['value'] = $tempLotteryId;
        }
        $searchAbleFields = ['lottery_id', 'issue'];
        $orderFields = 'begin_time';
        $orderFlow = 'asc';
        $fixedJoin = 1;
        $withTable = 'lottery';
        $afewMinutes = Carbon::now()->subMinute('20')->timestamp;
        $this->inputs['time_condtions'] = $this->inputs['time_condtions'] ?? '[["end_time",">=",' . $afewMinutes . ']]'; // 从现在开始。如果。没有时间字段的话，就用当前时间以上的显示
        $data = $contll->generateSearchQuery($eloqM, $searchAbleFields, $fixedJoin, $withTable, null, $orderFields, $orderFlow);
        return $contll->msgOut(true, $data);
    }
}
