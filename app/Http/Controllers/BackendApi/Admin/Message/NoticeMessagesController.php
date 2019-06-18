<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-01 14:29:10
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-18 18:39:56
 */

namespace App\Http\Controllers\BackendApi\Admin\Message;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Message\NoticeMessagesSendMessagesRequest;
use App\Lib\Common\InternalNoticeMessage;
use App\Models\Admin\BackendAdminUser;
use App\Models\Admin\Message\BackendSystemNoticeList;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class NoticeMessagesController extends BackEndApiMainController
{
    protected $eloqM = 'Admin\Message\BackendSystemInternalMessage';

    /**
     * 当前管理员的站内信息
     * @return JsonResponse
     */
    public function adminMessages(): JsonResponse
    {
        $messagesEloq = $this->eloqM::where('receive_admin_id', $this->partnerAdmin->id)->with('noticeMessage')->orderBy('created_at', 'desc')->get();
        $messages = [];
        foreach ($messagesEloq as $messageEloq) {
            $data = [
                'id' => $messageEloq->id,
                'status' => $messageEloq->status,
                'type' => $messageEloq->noticeMessage->type,
                'message' => $messageEloq->noticeMessage->message,
                'created_at' => $messageEloq->created_at,
            ];
            $messages[] = $data;
        }
        return $this->msgOut(true, $messages);
    }

    /**
     * 手动发送站内信息
     * @param  NoticeMessagesSendMessagesRequest $request
     * @return JsonResponse
     */
    public function sendMessages(NoticeMessagesSendMessagesRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $adminsArr = BackendAdminUser::select('id', 'group_id')->whereIn('id', $inputDatas['admins_id'])->get()->toArray();
        DB::beginTransaction();
        try {
            $messageObj = new InternalNoticeMessage();
            $type = BackendSystemNoticeList::ARTIFICIAL;
            $message = $inputDatas['message'];
            $messageObj->insertMessage($type, $message, $adminsArr, $this->partnerAdmin->id);
            DB::commit();
            return $this->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
}
