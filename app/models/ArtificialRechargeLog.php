<?php

namespace App\models;

class ArtificialRechargeLog extends BaseModel
{
    protected $table = 'artificial_recharge_log';

    protected $fillable = [
        'type', 'in_out', 'super_admin_id', 'super_admin_name', 'admin_id', 'admin_name', 'user_id', 'user_name', 'amount', 'comment', 'audit_flow_id', 'status', 'updated_at', 'created_at',
    ];
}
