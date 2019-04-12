<?php

namespace App\models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use SMartins\PassportMultiauth\HasMultiAuthApiTokens;

class UserHandleModel extends Authenticatable
{
    use Notifiable, HasMultiAuthApiTokens;

    const TYPE_TOP_AGENT     = 1;
    const TYPE_AGENT         = 2;
    const TYPE_USER          = 3;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'top_id', 'parent_id', 'rid', 'sign', 'platform_id', 'type', 'vip_level', 'is_tester', 'frozen_type', 'username', 'nickname', 'password', 'fund_password', 'prize_group', 'remember_token', 'level_deep', 'register_ip', 'last_login_ip', 'register_time', 'last_login_time', 'extend_info', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'register_time' => 'datetime',
        'last_login_time' => 'datetime',
    ];


    public function platform()
    {
        return $this->hasOne(PlatForms::class,'platform_id', 'platform_id');
    }

    public function account()
    {
        return $this->hasOne(HandleUserAccounts::class,'id','account_id');
    }
}
