<?php

namespace App\Models\User\Fund;

use App\Models\AuditFlow;
use App\Models\BaseModel;

class ArtificialRechargeLog extends BaseModel
{
    public const DECREMENT = 0; //减少额度
    public const INCREMENT = 1; //增加额度
    public const SYSTEM = 0; //系统操作
    public const SUPERADMIN = 1; //超管操作
    public const ADMIN = 2; //管理员操作
    public const UNDERWAYAUDIT = 0; //待审核
    public const AUDITSUCCESS = 1; //审核通过
    public const AUDITFAILURE = 2; //审核驳回

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
