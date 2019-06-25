<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-25 10:32:52
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-25 10:34:18
 */
namespace App\Http\SingleActions\Frontend\Game\Lottery;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use Illuminate\Http\JsonResponse;

class LotteriesIssueHistoryAction
{
    /**
     * 历史奖期
     * @param  FrontendApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        $data = [
            [
                'issue_no' => '201809221',
                'code' => '1,2,3,4,5',
            ],
            [
                'issue_no' => '201809222',
                'code' => '1,2,3,4,5',
            ],
            [
                'issue_no' => '201809223',
                'code' => '1,2,3,4,5',
            ],
        ];
        return $contll->msgOut(true, $data);
    }
}
