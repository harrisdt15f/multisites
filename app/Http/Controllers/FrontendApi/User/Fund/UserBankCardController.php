<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-25 16:20:55
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-25 17:34:41
 */

namespace App\Http\Controllers\FrontendApi\User\Fund;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Http\Requests\Frontend\User\Fund\UserBankCardAddRequest;
use App\Http\Requests\Frontend\User\Fund\UserBankCardDeleteRequest;
use App\Http\SingleActions\Frontend\User\Fund\UserBankCardAddAction;
use App\Http\SingleActions\Frontend\User\Fund\UserBankCardDeleteAction;
use App\Http\SingleActions\Frontend\User\Fund\UserBankCardListsAction;
use Illuminate\Http\JsonResponse;

class UserBankCardController extends FrontendApiMainController
{
    //用户银行卡列表
    public function lists(): JsonResponse
    {
        return $action->execute($this);
    }

    //用户添加绑定银行卡
    public function add(UserBankCardAddRequest $request, UserBankCardAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas, $this->partnerAdmin);
    }

    //用户删除绑定银行卡
    public function delete(UserBankCardDeleteRequest $request, UserBankCardDeleteAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas, $this->partnerAdmin->id);
    }
}
