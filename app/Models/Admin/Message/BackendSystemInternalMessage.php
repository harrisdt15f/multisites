<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-01 14:51:08
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-06 17:46:44
 */

namespace App\Models\Admin\Message;

use App\Models\Admin\BackendAdminUser;
use App\Models\BaseModel;

class BackendSystemInternalMessage extends BaseModel
{
    protected $table = 'backend_system_internal_messages';
    protected $casts = array('created_at' => 'created_at', 'updated_at' => 'updated_at');

    protected $fillable = [
        'operate_admin_id', 'receive_admin_id', 'receive_group_id', 'message_id', 'status', 'created_at', 'updated_at',
    ];

    public function noticeMessage()
    {
        return $this->hasOne(BackendSystemNoticeList::class, 'id', 'message_id');
    }
}
