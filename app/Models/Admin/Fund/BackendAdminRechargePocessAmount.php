<?php

namespace App\Models\Admin\Fund;

use App\Models\BaseModel;

class BackendAdminRechargePocessAmount extends BaseModel
{
    protected $table = 'backend_admin_recharge_pocess_amounts';

    protected $fillable = [
        'admin_id', 'fund',
    ];
}
