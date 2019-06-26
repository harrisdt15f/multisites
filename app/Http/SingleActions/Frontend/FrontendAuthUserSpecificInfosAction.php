<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-26 19:58:54
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-26 20:11:22
 */
namespace App\Http\SingleActions\Frontend;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\User\FrontendUsersSpecificInfo;
use Illuminate\Http\JsonResponse;

class FrontendAuthUserSpecificInfosAction
{
    /**
     * 用户个人信息
     * @param  FrontendApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        $usersSpecificInfoEloq = FrontendUsersSpecificInfo::where('user_id', $contll->partnerUser->id)->first();
        if ($usersSpecificInfoEloq !== null) {
            $data = [
                'nickname' => $usersSpecificInfoEloq->nickname,
                'realname' => $usersSpecificInfoEloq->realname,
                'mobile' => $usersSpecificInfoEloq->mobile,
                'email' => $usersSpecificInfoEloq->email,
                'zip_code' => $usersSpecificInfoEloq->zip_code,
                'address' => $usersSpecificInfoEloq->address,
            ];
        } else {
            $data = false;
        }

        return $contll->msgOut(true, $data);
    }
}
