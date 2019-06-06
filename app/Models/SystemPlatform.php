<?php

namespace App\Models;

use App\Models\Admin\BackendAdminUser;

class SystemPlatform extends BaseModel
{

    protected $table = 'system_platforms';
    public $timestamps = true;

    public function partnerAdminUsers()
    {
        return $this->hasMany(BackendAdminUser::class, 'platform_id', 'platform_id');
    }

}
