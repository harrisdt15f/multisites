<?php

namespace App\models;

class AuditFlow extends BaseModel
{
    protected $table = 'audit_flow';

    protected $fillable = [
        'admin_id', 'auditor_id', 'apply_note', 'auditor_note',
    ];
}
