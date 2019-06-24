<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-24 21:43:49
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-24 21:52:15
 */
namespace App\Http\SingleActions\Frontend\Game\Lottery;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\Game\Lottery\LotteryList;
use Illuminate\Http\JsonResponse;

class LotteriesLotteryListAction
{
    /**
     * 获取彩票列表
     * @param  FrontendApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        $lotteries = LotteryList::with(['issueRule:lottery_id,begin_time,end_time'])->get([
            'cn_name as name',
            'en_name',
            'series_id',
            'min_times',
            'max_times',
            'valid_modes',
            'min_prize_group',
            'max_prize_group',
            'max_trace_number',
            'day_issue',
        ]);
        $seriesConfig = config('game.main.series');
        $data = [];
        foreach ($lotteries as $lottery) {
            if (!isset($data[$lottery->series_id])) {
                $data[$lottery->series_id] = [
                    'name' => $seriesConfig[$lottery->series_id],
                    'sign' => $lottery->series_id,
                    'list' => [],
                ];
            }
            $data[$lottery->series_id]['list'][] = [
                'id' => $lottery->en_name,
                'name' => $lottery->name,
                'min_times' => $lottery->min_times,
                'max_times' => $lottery->max_times,
                'valid_modes' => $lottery->valid_modes,
                'min_prize_group' => $lottery->min_prize_group,
                'max_prize_group' => $lottery->max_prize_group,
                'max_trace_number' => $lottery->max_trace_number,
                'day_issue' => $lottery->day_issue,
                'begin_time' => $lottery->issueRule['begin_time'],
                'end_time' => $lottery->issueRule['end_time'],
            ];
        }
        return $contll->msgOut(true, $data);
    }
}
