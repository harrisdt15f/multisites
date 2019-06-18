<?php

namespace App\Models\Admin;

use App\Models\BackendAdminAuditFlowList;
use App\Models\BaseModel;

class BackendAdminAuditPasswordsList extends BaseModel
{
    protected $fillable = [
        'type', 'user_id', 'audit_data', 'status', 'audit_flow_id',
    ];

    public function auditFlow()
    {
        return $this->hasOne(BackendAdminAuditFlowList::class, 'id', 'audit_flow_id');
    }

}
