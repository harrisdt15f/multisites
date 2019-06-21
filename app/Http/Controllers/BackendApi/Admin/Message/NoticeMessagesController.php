<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-01 14:29:10
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-21 21:05:17
 */

namespace App\Http\Controllers\BackendApi\Admin\Message;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Message\NoticeMessagesSendMessagesRequest;
use App\Http\SingleActions\Backend\Admin\Message\NoticeMessagesAdminMessagesAction;
use App\Http\SingleActions\Backend\Admin\Message\NoticeMessagesSendMessagesAction;
use Illuminate\Http\JsonResponse;

class NoticeMessagesController extends BackEndApiMainController
{
    /**
     * 当前管理员的站内信息
     * @param  NoticeMessagesAdminMessagesAction $action
     * @return JsonResponse
     */
    public function adminMessages(NoticeMessagesAdminMessagesAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 手动发送站内信息
     * @param  NoticeMessagesSendMessagesRequest $request
     * @param  NoticeMessagesSendMessagesAction  $action
     * @return JsonResponse
     */
    public function sendMessages(NoticeMessagesSendMessagesRequest $request, NoticeMessagesSendMessagesAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
