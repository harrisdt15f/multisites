<?php

namespace App\Http\SingleActions\Frontend\Game\Lottery;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Lib\Common\configurationsRelated;
use App\Models\Admin\SystemConfiguration;
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
        $maxMultiples = SystemConfiguration::where('sign', 'max_multiples')->value('value');
        if ($maxMultiples === null) {
            $maxMultiples = $this->createMaxMultiplesConfig();
        }
        $lotteries = LotteryList::with(['issueRule:lottery_id,begin_time,end_time'])
            ->where('status', 1)->get([
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
                'maxMultiples' => $maxMultiples,
            ];
        }
        return $contll->msgOut(true, $data);
    }

    public function createMaxMultiplesConfig()
    {
        $configurationsRelated = new configurationsRelated();
        $parentId = systemConfiguration::where('sign', 'system')->value('id');
        if ($parentId === null) {
            $systemELoq = $configurationsRelated->create(0, 'system', '系统相关', '所有系统相关配置都保存此', null, 1, 1);
            $parentId = $systemELoq->id;
        }
        $maxMultiplesEloq = $configurationsRelated->create($parentId, 'max_multiples', '投注最大倍数', '玩家投注时可选择的最大倍数', 10000,
            1, 1);
        return $maxMultiplesEloq->value;
    }
}
