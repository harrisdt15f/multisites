<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class PlatForms extends Model 
{

    protected $table = 'platforms';
    public $timestamps = true;

    public function partnerAdminUsers()
    {
        return $this->hasMany(PartnerAdminUsers::class,'platform_id','platform_id');
    }

}
