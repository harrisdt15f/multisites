<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-25 16:59:44
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-25 17:19:33
 */
namespace App\Http\SingleActions\Frontend\User\Fund;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\User\Fund\FrontendUsersBankCard;
use Illuminate\Http\JsonResponse;

class UserBankCardDeleteAction
{
    protected $model;

    /**
     * @param  FrontendUsersBankCard  $frontendUsersBankCard
     */
    public function __construct(FrontendUsersBankCard $frontendUsersBankCard)
    {
        $this->model = $frontendUsersBankCard;
    }

    /**
     * 用户删除绑定银行卡
     * @param  FrontendApiMainController  $contll
     * @param  $inputDatas
     * @param  $userid
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, $inputDatas, $userid): JsonResponse
    {
        $bankCardEloq = $this->model::find($inputDatas['id']);
        if ($bankCardEloq->user_id != $userid) {
            return $contll->msgOut(false, [], '100200');
        }
        if ($bankCardEloq->delete()) {
            return $contll->msgOut(true);
        } else {
            return $contll->msgOut(false, [], '100201');
        }
    }
}
