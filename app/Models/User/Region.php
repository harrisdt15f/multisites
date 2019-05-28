<?php

namespace App\Models\User;

use App\Models\BaseModel;

class Region extends BaseModel
{
    protected $table = 'region';

    protected $fillable = [
        'region_id', 'region_parent_id', 'region_name', 'region_level',
    ];
}
