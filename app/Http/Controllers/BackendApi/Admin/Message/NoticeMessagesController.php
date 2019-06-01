<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-01 14:29:10
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-01 18:01:25
 */

namespace App\Http\Controllers\BackendApi\Admin\Message;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\Common\InternalNoticeMessage;
use App\Models\Admin\Message\NoticeMessage;
use App\Models\Admin\PartnerAdminUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NoticeMessagesController extends BackEndApiMainController
{
    protected $eloqM = 'Admin\Message\InternalNotice';

    /**
     * 当前管理员的站内信息
     * @return JsonResponse $messages
     */
    public function adminMessages()
    {
        $messagesEloq = $this->eloqM::where('admin_id', $this->partnerAdmin->id)->with('noticeMessage')->orderBy('created_at', 'desc')->get();
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
     * @return JsonResponse $messages
     */
    public function sendMessages()
    {
        $validator = Validator::make($this->inputs, [
            'admins_id' => 'required|array',
            'message' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $adminsArr = PartnerAdminUsers::select('id', 'group_id')->whereIn('id', $this->inputs['admins_id'])->get()->toArray();
        DB::beginTransaction();
        try {
            $messageClass = new InternalNoticeMessage();
            $type = NoticeMessage::ARTIFICIAL;
            $message = $this->inputs['message'];
            //插入notice_messages表
            $messageId = $messageClass->createNoticeMessages($type, $message);
            //插入internal_messages表
            $messageClass->createInternalNotice($adminsArr, $messageId, $this->partnerAdmin->id);
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
