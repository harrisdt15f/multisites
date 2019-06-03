<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-01 14:51:08
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-03 11:03:20
 */

namespace App\Models\Admin\Message;

use App\Models\Admin\PartnerAdminUsers;
use App\Models\BaseModel;

class InternalNotice extends BaseModel
{
    protected $table = 'internal_notices';
    protected $casts = array('created_at' => 'created_at', 'updated_at' => 'updated_at');

    protected $fillable = [
        'send_id', 'admin_id', 'group_id', 'message_id', 'status', 'created_at', 'updated_at',
    ];

    public function noticeMessage()
    {
        return $this->hasOne(NoticeMessage::class, 'id', 'message_id');
    }
}
