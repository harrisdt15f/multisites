<?php

namespace App\Models\Admin;

use App\Models\BackendAdminAuditFlowList;
use App\Models\BaseModel;

class BackendAdminAuditPasswordsList extends BaseModel
{
    protected $table = 'backend_admin_audit_passwords_lists';

    protected $fillable = [
        'type', 'user_id', 'audit_data', 'status', 'audit_flow_id',
    ];

    public function auditFlow()
    {
        $data = $this->hasOne(BackendAdminAuditFlowList::class, 'id', 'audit_flow_id');
        return $data;
    }

}
