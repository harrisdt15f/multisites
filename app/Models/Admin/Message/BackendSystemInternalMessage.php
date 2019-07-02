<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-01 14:51:08
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-07 13:41:14
 */

namespace App\Models\Admin\Message;

use App\Models\Admin\BackendAdminUser;
use App\Models\BaseModel;

class BackendSystemInternalMessage extends BaseModel
{
    protected $guarded = ['id'];

    protected $casts = array('created_at' => 'created_at', 'updated_at' => 'updated_at');

    public function noticeMessage()
    {
        return $this->hasOne(BackendSystemNoticeList::class, 'id', 'message_id');
    }
}
