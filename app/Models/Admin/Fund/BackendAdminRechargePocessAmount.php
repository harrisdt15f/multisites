<?php

namespace App\Models\Admin\Fund;

use App\Models\BaseModel;

class BackendAdminRechargePocessAmount extends BaseModel
{
    protected $fillable = [
        'admin_id', 'fund',
    ];
}
