<?php

namespace App\Models\Admin\Fund;

use App\Models\BaseModel;

class FrontendSystemBank extends BaseModel
{
    protected $fillable = [
        'title', 'code', 'pay_type', 'status', 'min_recharge', 'max_recharge', 'min_withdraw', 'max_withdraw', 'remarks', 'allow_user_level', 'created_at', 'updated_at',
    ];
}
