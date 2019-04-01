<?php

namespace App\models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class PartnerAdminUsers extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $table = 'partner_admin_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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
        return $this->hasOne(PlatForms::class,'platform_id', 'platform_id');
    }

    public function accessGroup()
    {
        return $this->hasOne(PartnerAdminGroupAccess::class,'id','group_id');
    }
}
