<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-26 11:03:18
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-26 11:16:48
 */
namespace App\Http\SingleActions\Frontend;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\User\FrontendUsersSpecificInfo;
use Illuminate\Http\JsonResponse;

class FrontendAuthResetSpecificInfosAction
{
    /**
     * 用户设置详细信息
     * @param  FrontendApiMainController  $contll
     * @param  $inputDatas
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, $inputDatas): JsonResponse
    {
        $specificinfoEloq = FrontendUsersSpecificInfo::where('user_id', $contll->partnerAdmin->id)->first();
        if ($specificinfoEloq === null) {
            $specificinfoEloq = new FrontendUsersSpecificInfo();
        }
        $inputDatas['user_id'] = $contll->partnerAdmin->id;
        $contll->editAssignment($specificinfoEloq, $inputDatas);
        try {
            $specificinfoEloq->save();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            return $contll->msgOut(false, [], '100012');
        }
    }

    // public function createSpecificInfo($id,$inputDatas){

    // }
}
