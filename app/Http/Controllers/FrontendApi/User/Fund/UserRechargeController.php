<?php

namespace App\Http\Controllers\FrontendApi\User\Fund;

/**
 * @Author: Fish
 * @Date:   2019-07-2 12:35:55
 */


use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Http\SingleActions\Frontend\User\Fund\UserRechargeListAction;
use Illuminate\Http\JsonResponse;

class UserRechargeController extends FrontendApiMainController
{

    /**
     * 用户充值记录
     * @param  UserRechargeListAction $action
     * @return JsonResponse
     */
    public function rechargeList(UserRechargeListAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    public function recharge(UserRechargeAction $action): JsonResponse
    {
        return $action->execute($this);
    }
}
