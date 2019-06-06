<?php

namespace App\Models;

use App\Models\Admin\BackendAdminUser;

class PlatForms extends BaseModel
{

    protected $table = 'platforms';
    public $timestamps = true;

    public function partnerAdminUsers()
    {
        return $this->hasMany(BackendAdminUser::class, 'platform_id', 'platform_id');
    }

}
