<?php

namespace App\models;

class ArtificialRechargeLog extends BaseModel
{
    const DECREMENT = 0; //减少额度
    const INCREMENT = 1; //增加额度
    const SYSTEM = 0; //系统对管理员操作
    const SUPERADMIN = 1; //超管对管理员操作
    const ADMIN = 2; //管理员对用户操作
    const UNDERWAYAUDIT = 0; //待审核
    const AUDITSUCCESS = 1; //审核通过
    const AUDITFAILURE = 2; //审核驳回

    protected $table = 'artificial_recharge_log';

    protected $fillable = [
        'type', 'in_out', 'super_admin_id', 'super_admin_name', 'admin_id', 'admin_name', 'user_id', 'user_name', 'amount', 'comment', 'audit_flow_id', 'status', 'updated_at', 'created_at',
    ];

    public function auditFlow()
    {
        $data = $this->hasOne(AuditFlow::class, 'id', 'audit_flow_id');
        return $data;
    }
}
