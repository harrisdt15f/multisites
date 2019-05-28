<?php

namespace App\Models\User\Fund;

use App\Models\BaseModel;

class AccountChangeType extends BaseModel
{
    protected $table = 'account_change_type';

    protected $fillable = [
        'name', 'sign', 'in_out', 'type',
    ];
}
