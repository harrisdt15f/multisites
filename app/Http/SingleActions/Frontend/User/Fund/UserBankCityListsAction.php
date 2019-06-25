<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-25 19:57:13
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-25 20:04:29
 */
namespace App\Http\SingleActions\Frontend\User\Fund;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\User\UsersRegion;
use Illuminate\Http\JsonResponse;

class UserBankCityListsAction
{
    /**
     * 添加银行卡时选择的城市列表
     * @param  FrontendApiMainController  $contll
     * @param  $inputDatas
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, $inputDatas): JsonResponse
    {
        $data = UsersRegion::select('id', 'region_id', 'region_name')->where('region_parent_id', $inputDatas['region_parent_id'])->get()->toArray();
        return $contll->msgOut(true, $data);
    }
}
