<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-25 10:40:27
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-25 10:44:52
 */
namespace App\Http\SingleActions\Frontend\Game\Lottery;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\Game\Lottery\LotteryIssue;
use App\Models\Game\Lottery\LotteryList;
use Illuminate\Http\JsonResponse;

class LotteriesAvailableIssuesAction
{
    /**
     * 游戏-可用奖期
     * @param  FrontendApiMainController  $contll
     * @param  $inputDatas
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, $inputDatas): JsonResponse
    {
        $lotterySign = $inputDatas['lottery_sign'];
        $lottery = LotteryList::findBySign($lotterySign);
        $canUserInfo = LotteryIssue::getCanBetIssue($lotterySign, $lottery->max_trace_number);
        $canBetIssueData = [];
        $currentIssue = [];
        foreach ($canUserInfo as $index => $issue) {
            if ($index <= 0) {
                $currentIssue = [
                    'issue_no' => $issue->issue,
                    'begin_time' => $issue->begin_time,
                    'end_time' => $issue->end_time,
                    'open_time' => $issue->allow_encode_time,
                ];
            }
            $canBetIssueData[] = [
                'issue_no' => $issue->issue,
                'begin_time' => $issue->begin_time,
                'end_time' => $issue->end_time,
                'open_time' => $issue->allow_encode_time,
            ];
        }
        // 上一期
        $_lastIssue = LotteryIssue::getLastIssue($lotterySign);
        $lastIssue = $_lastIssue !== null ? [
            'issue_no' => $_lastIssue->issue,
            'begin_time' => $_lastIssue->begin_time,
            'end_time' => $_lastIssue->end_time,
            'open_time' => $_lastIssue->allow_encode_time,
            'open_code' => '1,2,3,4,5',
        ] : [];
        $data = [
            'issueInfo' => $canBetIssueData,
            'currentIssue' => $currentIssue,
            'lastIssue' => $lastIssue,
        ];
        return $contll->msgOut(true, $data);
    }
}
