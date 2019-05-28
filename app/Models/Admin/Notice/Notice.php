<?php

namespace App\Models\Admin\Notice;

use App\Models\Admin\PartnerAdminUsers;
use App\Models\BaseModel;

class Notice extends BaseModel
{
    protected $table = 'partner_notice';

    protected $fillable = [
        'type', 'title', 'content', 'start_time', 'end_time', 'sort', 'status', 'admin_id', 'created_at', 'updated_at',
    ];

    public function admin()
    {
        $data = $this->hasOne(PartnerAdminUsers::class, 'id', 'admin_id');
        return $data;
    }
}
