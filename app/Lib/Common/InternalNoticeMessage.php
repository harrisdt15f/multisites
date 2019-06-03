<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-01 16:09:24
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-03 11:02:22
 */
namespace App\Lib\Common;

use App\Models\Admin\Message\InternalNotice;
use App\Models\Admin\Message\NoticeMessage;

class InternalNoticeMessage
{
    /**
     * 生成notice_messages表
     * @param $type     1手动发送 2审核相关 3充值体现相关
     * @param $message     消息内容
     * @return int $id
     */
    public function createNoticeMessages($type, $message)
    {
        $data = [
            'type' => $type,
            'message' => $message,
        ];
        $noticeMessage = new NoticeMessage();
        $noticeMessage->fill($data);
        $noticeMessage->save();
        return $noticeMessage->id;
    }

    /**
     * 生成interna_notice表
     * @param $adminEloq   接收信息的管理员Eloq
     * @param $message_id  notice_messages表id
     * @param $send_id  发送人id  系统null
     * @return void
     */
    public function createInternalNotice($adminsArr, $message_id, $send_id = null)
    {
        foreach ($adminsArr as $admin) {
            $data = [
                'send_id' => $send_id,
                'admin_id' => $admin['id'],
                'group_id' => $admin['group_id'],
                'message_id' => $message_id,
                'status' => 0,
            ];
            $noticeMessage = new InternalNotice();
            $noticeMessage->fill($data);
            $noticeMessage->save();
        }
    }

    /**
     * 插入站内消息
     */
    public function insertMessage($type, $message, $adminsArr, $send_id = null)
    {
        $message_id = $this->createNoticeMessages($type, $message);
        $this->createInternalNotice($adminsArr, $message_id, $send_id);
    }
}
