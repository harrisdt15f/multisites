<?php

namespace App\Models\Admin\Notice;

use App\Models\Admin\BackendAdminUser;
use App\Models\BaseModel;

class FrontendMessageNotice extends BaseModel
{
    protected $fillable = [
        'type', 'title', 'content', 'start_time', 'end_time', 'sort', 'status', 'admin_id', 'created_at', 'updated_at',
    ];

    public function admin()
    {
        return $this->hasOne(BackendAdminUser::class, 'id', 'admin_id');
    }
}
