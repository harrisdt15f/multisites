<?php

namespace App\Http\SingleActions\Mobile\Game\Lottery;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\Game\Lottery\LotteryIssue;
use Illuminate\Http\JsonResponse;

class LotteriesIssueHistoryAction
{
    /**
     * 历史奖期
     * @param  FrontendApiMainController  $contll
     * @param  $inputDatas
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, $inputDatas): JsonResponse
    {
        $contll->inputs['status_encode'] = LotteryIssue::ENCODED;
        $lotteryIssueEloq = new LotteryIssue();
        $searchAbleFields = ['lottery_id', 'status_encode'];
        $orderFields = 'id';
        $orderFlow = 'desc';
        $data = $contll->generateSearchQuery(
            $lotteryIssueEloq,
            $searchAbleFields,
            0,
            null,
            null,
            $orderFields,
            $orderFlow
        );
        return $contll->msgOut(true, $data);
    }
}
