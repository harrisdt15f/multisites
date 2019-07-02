<?php

/**
 * @Author: Fish
 * @Date:   2019-07-2 12:35:55
 */

namespace App\Http\Controllers\FrontendApi\User\Fund;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Http\SingleActions\Frontend\User\Fund\UserFundAction;
use Illuminate\Http\JsonResponse;

class UserFundController extends FrontendApiMainController
{
    /**
     * 用户账变记录
     * @param  UserFundAction $action
     * @return JsonResponse
     */
    public function lists(UserFundAction $action): JsonResponse
    {
        return $action->execute($this);
    }

}
