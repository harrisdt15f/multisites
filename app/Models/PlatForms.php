<?php

namespace App\Models;

class PlatForms extends BaseModel
{

    protected $table = 'platforms';
    public $timestamps = true;

    public function partnerAdminUsers()
    {
        return $this->hasMany(PartnerAdminUsers::class,'platform_id','platform_id');
    }

}
