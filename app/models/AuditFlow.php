<?php

namespace App\models;

class AuditFlow extends BaseModel
{
    protected $table = 'audit_flow';

    protected $fillable = [
        'admin_id', 'auditor_id', 'apply_note', 'auditor_note','admin_name','auditor_name','user_name',
    ];

    public function admin()
    {
        return $this->hasOne(PartnerAdminUsers::class,'id','admin_id');
    }

    public function auditor()
    {
        return $this->hasOne(PartnerAdminUsers::class,'id','auditor_id');
    }
}
