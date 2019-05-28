<?php

namespace App\Models\Admin\Fund;

use App\Models\BaseModel;

class FundOperation extends BaseModel
{
    protected $table = 'fund_operation';

    protected $fillable = [
        'admin_id', 'fund',
    ];
}
