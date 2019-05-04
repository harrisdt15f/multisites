<?php

namespace App\models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use SMartins\PassportMultiauth\HasMultiAuthApiTokens;

class PartnerAdminUsers extends Authenticatable
{
    use Notifiable, HasMultiAuthApiTokens;
    protected $table = 'partner_admin_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'email_verified_at', 'rmember_token', 'is_test', 'group_id', 'status', 'platform_id', 'super_id',
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
        'email_verified_at' => 'datetime',
    ];

    public function platform()
    {
        return $this->hasOne(PlatForms::class, 'platform_id', 'platform_id');
    }

    public function accessGroup()
    {
        return $this->hasOne(PartnerAdminGroupAccess::class, 'id', 'group_id');
    }

    public function operateAmount()
    {
        return $this->hasOne(FundOperation::class, 'admin_id', 'id');
    }
}
