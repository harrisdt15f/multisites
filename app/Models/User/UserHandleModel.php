<?php

namespace App\Models\User;

use App\Models\Admin\UserAdmitedFlowsModel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserHandleModel extends Authenticatable implements JWTSubject
{
    use Notifiable;

    const TYPE_TOP_AGENT = 1;
    const TYPE_AGENT = 2;
    const TYPE_USER = 3;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'top_id', 'parent_id', 'rid', 'sign', 'account_id', 'type', 'vip_level', 'is_tester', 'frozen_type', 'nickname', 'password', 'fund_password', 'prize_group', 'remember_token', 'level_deep', 'register_ip', 'last_login_ip', 'register_time', 'last_login_time', 'extend_info', 'status',
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

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function platform()
    {
        return $this->hasOne(PlatForms::class, 'platform_id', 'platform_id');
    }

    public function account()
    {
        return $this->hasOne(HandleUserAccounts::class, 'user_id', 'id');
    }
    //用户冻结历史
    public function userAdmitedFlow()
    {
        return $this->hasMany(UserAdmitedFlowsModel::class, 'user_id', 'id')->orderBy('created_at', 'desc');
    }
}
