<?php

namespace App\Models;

use App\Models\Admin\PartnerAdminUsers;

class PlatForms extends BaseModel
{

    protected $table = 'platforms';
    public $timestamps = true;

    public function partnerAdminUsers()
    {
        return $this->hasMany(PartnerAdminUsers::class, 'platform_id', 'platform_id');
    }

}
