<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-24 17:47:58
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-24 17:53:02
 */
namespace App\Http\SingleActions\Backend\Game\Lottery;

use App\Http\Controllers\backendApi\BackEndApiMainController;
use App\Models\Game\Lottery\LotteryIssue;
use Exception;
use Illuminate\Http\JsonResponse;

class LotteriesInputCodeAction
{
    /**
     * 奖期录号
     * @param   BackEndApiMainController  $contll
     * @param   $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, $inputDatas): JsonResponse
    {
        $issueEloq = LotteryIssue::where('lottery_id', $inputDatas['lottery_id'])->where('issue', $inputDatas['issue'])->first();
        if ($issueEloq === null) {
            return $contll->msgOut(false, [], '101703');
        }
        if ($issueEloq->official_code !== null) {
            return $contll->msgOut(false, [], '101704');
        }
        try {
            $status_encode = LotteryIssue::ENCODED;
            $issueEloq->status_encode = $status_encode;
            $issueEloq->encode_time = time();
            $issueEloq->official_code = $inputDatas['code'];
            $issueEloq->save();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $contll->msgOut(false, [], $sqlState, $msg);
        }
    }
}
