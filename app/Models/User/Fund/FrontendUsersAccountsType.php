<?php

namespace App\Models\User\Fund;

use App\Models\BaseModel;
use App\Models\User\Fund\Logics\FrontendUsersAccountsTypeLogics;

class FrontendUsersAccountsType extends BaseModel
{
    use FrontendUsersAccountsTypeLogics;

    protected $fillable = [
        'name',
        'sign',
        'in_out',
        'param',
        'amount',
        'user_id',
        'project_id',
        'lottery_id',
        'method_id',
        'issue',
        'from_id',
        'from_admin_id',
        'to_id',
        'frozen_type',
        'activity_sign',
        'admin_id',
    ];

    public static $rules = [
        'name' => 'required|min:2|max:32',
        'sign' => 'required|min:2|max:32',
        'in_out' => 'required|in:1,2',
        'param' => 'required|string',
        'amount' => 'in:0,1',
        'user_id' => 'in:0,1',
        'project_id' => 'in:0,1',
        'lottery_id' => 'in:0,1',
        'method_id' => 'in:0,1',
        'issue' => 'in:0,1',
        'from_id' => 'in:0,1',
        'from_admin_id' => 'in:0,1',
        'to_id' => 'in:0,1',
        'frozen_type' => 'required|in:0,1',
        'activity_sign' => 'required|in:0,1',
    ];
}
