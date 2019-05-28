<?php

namespace App\Models;

use App\Models\Admin\PartnerAdminUsers;
use App\Models\Admin\PassworAuditLists;

class AuditFlow extends BaseModel
{
    protected $table = 'audit_flow';

    protected $fillable = [
        'admin_id', 'auditor_id', 'apply_note', 'auditor_note', 'admin_name', 'auditor_name', 'username',
    ];

    public function admin()
    {
        return $this->hasOne(PartnerAdminUsers::class, 'id', 'admin_id');
    }

    public function auditor()
    {
        return $this->hasOne(PartnerAdminUsers::class, 'id', 'auditor_id');
    }

    public function auditlist()
    {
        return $this->belongsTo(PassworAuditLists::class, 'audit_flow_id', 'id');
    }
}
