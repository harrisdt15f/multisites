<?php

namespace App\Models\Admin;

use App\Models\AuditFlow;
use App\Models\BaseModel;

class PassworAuditLists extends BaseModel
{
    protected $table = 'passwords_audit_lists';

    protected $fillable = [
        'type', 'user_id', 'audit_data', 'status', 'audit_flow_id',
    ];

    public function auditFlow()
    {
        $data = $this->hasOne(AuditFlow::class, 'id', 'audit_flow_id');
        return $data;
    }

}
