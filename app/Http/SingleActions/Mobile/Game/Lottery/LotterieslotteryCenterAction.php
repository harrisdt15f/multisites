<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-25 10:40:27
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-25 10:44:52
 */
namespace App\Http\SingleActions\Mobile\Game\Lottery;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\Game\Lottery\LotteryIssue;
use App\Models\Game\Lottery\LotteryList;
use Illuminate\Http\JsonResponse;

class LotterieslotteryCenterAction
{
    /**
     * 游戏-开奖中心
     * @param  FrontendApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        $lotteriesEloq = LotteryList::select('en_name')->where('status', 1)->with(['oneIssues' => function ($query) {
            $query->select('lottery_id', 'issue', 'official_code')
                ->where('status_encode', 1)
                ->orderBy('issue', 'desc');
        }])->get();
        $data = [];
        foreach ($lotteriesEloq as $lotteryEloq) {
            $issue = $lotteryEloq->oneIssues->issue ?? null;
            $officialCode = $lotteryEloq->oneIssues->official_code ?? null;
            $data[] = [
                'lottery_id' => $lotteryEloq->en_name,
                'issue' => $issue,
                'official_code' => $officialCode,
            ];
        }
        return $contll->msgOut(true, $data);
    }
}
