<?php

namespace App\Models\User;

use App\Models\BaseModel;

class UsersRegion extends BaseModel
{
    protected $table = 'users_regions';

    protected $fillable = [
        'region_id', 'region_parent_id', 'region_name', 'region_level',
    ];
}
