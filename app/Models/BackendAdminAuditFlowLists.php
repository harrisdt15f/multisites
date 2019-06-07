<?php

namespace App\Models;

use App\Models\Admin\BackendAdminAuditPasswordsList;
use App\Models\Admin\BackendAdminUser;

class BackendAdminAuditFlowList extends BaseModel
{
    protected $fillable = [
        'admin_id', 'auditor_id', 'apply_note', 'auditor_note', 'admin_name', 'auditor_name', 'username',
    ];

    public function admin()
    {
        return $this->hasOne(BackendAdminUser::class, 'id', 'admin_id');
    }

    public function auditor()
    {
        return $this->hasOne(BackendAdminUser::class, 'id', 'auditor_id');
    }

    public function auditlist()
    {
        return $this->belongsTo(BackendAdminAuditPasswordsList::class, 'audit_flow_id', 'id');
    }
}
