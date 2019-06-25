<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-25 16:20:55
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-25 20:03:17
 */

namespace App\Http\Controllers\FrontendApi\User\Fund;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Http\Requests\Frontend\User\Fund\UserBankCardAddRequest;
use App\Http\Requests\Frontend\User\Fund\UserBankCardDeleteRequest;
use App\Http\Requests\Frontend\User\Fund\UserBankCityListsRequest;
use App\Http\SingleActions\Frontend\User\Fund\UserBankBankListsAction;
use App\Http\SingleActions\Frontend\User\Fund\UserBankCardAddAction;
use App\Http\SingleActions\Frontend\User\Fund\UserBankCardDeleteAction;
use App\Http\SingleActions\Frontend\User\Fund\UserBankCardListsAction;
use App\Http\SingleActions\Frontend\User\Fund\UserBankCityListsAction;
use App\Http\SingleActions\Frontend\User\Fund\UserBankProvinceListsAction;
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

    //添加银行卡时选择的银行列表
    public function bankLists(UserBankBankListsAction $action)
    {
        return $action->execute($this);
    }

    //添加银行卡时选择的省份列表
    public function provinceLists(UserBankProvinceListsAction $action)
    {
        return $action->execute($this);
    }

    //添加银行卡时选择的城市列表
    public function cityLists(UserBankCityListsRequest $request, UserBankCityListsAction $action)
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
