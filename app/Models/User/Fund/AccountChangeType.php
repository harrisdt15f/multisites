<?php

namespace App\Models\User\Fund;

use App\Models\BaseModel;
use App\Models\User\Fund\Logics\AccountChangeTypeLogics;

class AccountChangeType extends BaseModel
{
    use AccountChangeTypeLogics;

    protected $table = 'account_change_type';

    protected $fillable = [
        'name', 'sign', 'in_out', 'type',
    ];

    public static $rules = [
        'name'              => 'required|min:2|max:32',
        'sign'              => 'required|min:2|max:32',
        'type'              => 'required|in:1,2',
        'amount'            => 'required|in:0,1',
        'user_id'           => 'required|in:0,1',
        'project_id'        => 'required|in:0,1',
        'lottery_id'        => 'required|in:0,1',
        'method_id'         => 'required|in:0,1',
        'issue'             => 'required|in:0,1',
        'from_id'           => 'required|in:0,1',
        'from_admin_id'     => 'required|in:0,1',
        'to_id'             => 'required|in:0,1',
        'frozen_type'       => 'required|in:0,1',
        'activity_sign'     => 'required|in:0,1',
    ];
}
