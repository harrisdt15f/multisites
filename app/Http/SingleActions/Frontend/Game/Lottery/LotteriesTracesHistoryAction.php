<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-28 16:07:01
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-28 16:09:16
 */
namespace App\Http\SingleActions\Frontend\Game\Lottery;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class LotteriesTracesHistoryAction
{
    /**
     * 游戏-追号历史
     * @param  FrontendApiMainController  $contll
     * @param  $inputDatas
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, $inputDatas): JsonResponse
    {
        $lotterySign = $inputDatas['lottery_sign'] ?? '*';
        $count = $inputDatas['count']; //10
        $beginTime = $inputDatas['begin_time'] ?? null;
        $endTime = $inputDatas['end_time'] ?? null;
        $data = Project::getGameTracesList($contll->partnerUser->id, $lotterySign, $count, $beginTime, $endTime);
        return $contll->msgOut(true, $data);
    }
}
