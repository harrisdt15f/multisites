<?php

namespace App\Models\Admin\Notice;

use App\Models\Admin\BackendAdminUser;
use App\Models\BaseModel;

class FrontendMessageNotice extends BaseModel
{
    protected $guarded = ['id'];

    public function admin()
    {
        return $this->hasOne(BackendAdminUser::class, 'id', 'admin_id');
    }
}
