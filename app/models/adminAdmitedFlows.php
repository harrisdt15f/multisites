<?php

namespace App\models;

class adminAdmitedFlows extends BaseModel
{
    protected $table = 'admin_admited_flows';

    protected $fillable = [
        'type', 'in_out', 'super_admin_id', 'super_admin_name', 'admin_id', 'admin_name', 'user_id', 'user_name', 'amount', 'comment', 'updated_at', 'created_at',
    ];
}
