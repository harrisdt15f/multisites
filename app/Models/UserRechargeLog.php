<?php

namespace App\Models;

class UserRechargeLog extends BaseModel
{
    protected $table = 'user_recharge_log';

    const ARTIFICIAL = 1; //人工充值
    const AUTOMATIC = 0; //自动充值

    protected $fillable = [
        'company_order_num', 'log_num', 'real_amount', 'deposit_mode', 'req_type', 'req_type_1_params', 'req_type_2_params', 'req_type_4_params', 'updated_at', 'created_at',
    ];
}
