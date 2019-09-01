<?php

namespace App\Models\User;

use App\Models\BaseModel;
use App\Models\User\Logics\PayTraits;

class UsersWithdrawHistorie extends BaseModel
{
    use PayTraits;

    const WAIT = 0; //等待状态
    const WITHDRAWSUCCESS = 1; //充值成功
    const WITHDRAWFAILURE = 2; //充值失败
    const UNDERWAYAUDIT = 10; //正在审核
    const AUDITSUCCESS = 11; //审核成功
    const AUDITFAILURE = 12; //审核失败

    protected $guarded = ['id'];
}
