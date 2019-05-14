<?php

namespace App\models;

class UserRechargeHistory extends BaseModel
{
    protected $table = 'user_recharge_history';

    const ARTIFICIAL = 1; //人工充值
    const AUTOMATIC = 0; //自动充值
    const UNDERWAYRECHARGE = 0; //正在充值
    const RECHARGESUCCESS = 1; //充值成功
    const RECHARGEFAILURE = 2; //充值失败
    const UNDERWAYAUDIT = 10; //正在审核
    const AUDITSUCCESS = 11; //审核成功
    const AUDITFAILURE = 12; //审核失败

    protected $fillable = [
        'user_id', 'user_name', 'is_tester', 'top_agent', 'channel', 'payment_id', 'amount', 'company_order_num', 'third_party_order_num', 'deposit_mode', 'real_amount', 'fee', 'audit_flow_id', 'status', 'updated_at', 'created_at',
    ];
}
