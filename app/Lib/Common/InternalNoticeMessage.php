<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-01 16:09:24
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-06 17:51:50
 */
namespace App\Lib\Common;

use App\Models\Admin\Message\BackendSystemInternalMessage;
use App\Models\Admin\Message\BackendSystemNoticeList;

class InternalNoticeMessage
{
    /**
     * 生成backend_system_notice_lists表
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
        $noticeMessage = new BackendSystemNoticeList();
        $noticeMessage->fill($data);
        $noticeMessage->save();
        return $noticeMessage->id;
    }

    /**
     * 生成interna_notice表
     * @param $adminEloq   接收信息的管理员Eloq
     * @param $message_id  backend_system_notice_lists表id
     * @param $operate_admin_id  发送人id  系统null
     * @return void
     */
    public function createInternalNotice($adminsArr, $message_id, $operate_admin_id = null)
    {
        foreach ($adminsArr as $admin) {
            $data = [
                'operate_admin_id' => $operate_admin_id,
                'receive_admin_id' => $admin['id'],
                'receive_group_id' => $admin['group_id'],
                'message_id' => $message_id,
                'status' => 0,
            ];
            $noticeMessage = new BackendSystemInternalMessage();
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
