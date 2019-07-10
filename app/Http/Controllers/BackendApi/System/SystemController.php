<?php
/**
 * @Author: Fish
 * @Date:   2019/7/8 17:49
 */

namespace App\Http\Controllers\BackendApi\System;


use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Help\HelpCenterUploadPicRequest;
use App\Http\Requests\Backend\System\SystemRequest;
use Illuminate\Http\JsonResponse;

class SystemController extends BackEndApiMainController
{
    /**
     * 图片上传
     * @param  SystemRequest $request
     * @return JsonResponse
     */
    public function uploadPic(SystemRequest $request): string
    {
        $input = $request->validated();
        $picPath['path'] = $this->publicUploadImg($input, $this->currentPlatformEloq->platform_id, $this->currentPlatformEloq->platform_name);
        return $this->msgOut(true, $picPath);
    }
}