<?php

namespace App\Models;

class Banks extends BaseModel
{
    protected $table = 'banks';

    protected $fillable = [
        'title', 'code', 'pay_type', 'status', 'min_recharge', 'max_recharge', 'min_withdraw', 'max_withdraw', 'remarks', 'allow_user_level', 'created_at', 'updated_at',
    ];
}
