<?php

namespace App\Http\Controllers\FrontendApi\Game\Lottery;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\models\LotteriesModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LottriesController extends FrontendApiMainController
{
    public function lotteryList(): JsonResponse
    {
        $lotteries = LotteriesModel::with(['issueRule:lottery_id,begin_time,end_time'])->get([
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
                    'name'  => $seriesConfig[$lottery->series_id],
                    'sign'  => $lottery->series_id,
                    'list'  => []
                ];
            }
            $data[$lottery->series_id]['list'][] = [
                'id'                => $lottery->en_name,
                'name'              => $lottery->name,
                'min_times'         => $lottery->min_times,
                'max_times'         => $lottery->max_times,
                'valid_modes'       => $lottery->valid_modes,
                'min_prize_group'   => $lottery->min_prize_group,
                'max_prize_group'   => $lottery->max_prize_group,
                'max_trace_number'  => $lottery->max_trace_number,
                'day_issue'         => $lottery->day_issue,
                'begin_time'        => $lottery->issueRule['begin_time'],
                'end_time'        => $lottery->issueRule['end_time'],
            ];
        }
        return $this->msgOut(true, $data);
    }
}
