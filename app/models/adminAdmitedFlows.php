<?php

namespace App\models;

class adminAdmitedFlows extends BaseModel
{
    protected $table = 'admin_admited_flows';

    protected $fillable = [
        'super_admin_id', 'super_admin_name', 'admin_id', 'admin_name', 'comment', 'updated_at', 'created_at',
    ];
}
