<?php

namespace App\Models\Admin\Activity;

use App\Models\BaseModel;

class FrontendInfoCategorie extends BaseModel
{
    protected $fillable = [
        'title', 'parent', 'template', 'platform_id', 'created_at', 'updated_at',
    ];
}
