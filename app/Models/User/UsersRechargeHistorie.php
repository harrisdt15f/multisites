<?php

namespace App\Models\User;

use App\Models\BaseModel;
use App\Models\User\Logics\PayTraits;

class UsersRechargeHistorie extends BaseModel
{
    use PayTraits;

    const ARTIFICIAL = 1; //人工充值
    const AUTOMATIC = 0; //自动充值
    const UNDERWAYRECHARGE = 0; //正在充值
    const RECHARGESUCCESS = 1; //充值成功
    const RECHARGEFAILURE = 2; //充值失败
    const UNDERWAYAUDIT = 10; //正在审核
    const AUDITSUCCESS = 11; //审核成功
    const AUDITFAILURE = 12; //审核失败

    protected $guarded = ['id'];
}
