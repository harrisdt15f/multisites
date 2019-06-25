<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-25 10:45:55
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-25 10:49:26
 */
namespace App\Http\SingleActions\Frontend\Game\Lottery;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class LotteriesProjectHistoryAction
{
    /**
     * 游戏-下注历史
     * @param  FrontendApiMainController  $contll
     * @param  $inputDatas
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, $inputDatas): JsonResponse
    {
        $lotterySign = $inputDatas['lottery_sign'] ?? '*';
        $start = $inputDatas['start']; //0
        $count = $inputDatas['count']; //10
        $data = Project::getGamePageList($lotterySign, $start, $count);
        return $contll->msgOut(true, $data);
    }
}
