<?php

namespace App\models;

class PassworAuditLists extends BaseModel
{
    protected $table = 'passwords_audit_lists';

    protected $fillable = [
        'type', 'user_id', 'audit_data', 'status', 'audit_flow_id'
    ];
}
