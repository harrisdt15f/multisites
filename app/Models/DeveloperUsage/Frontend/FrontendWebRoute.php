<?php

namespace App\Models\DeveloperUsage\Frontend;

use App\Models\BaseModel;

class FrontendWebRoute extends BaseModel
{
    protected $table = 'frontend_web_route';

    protected $fillable = [
        'route_name', 'frontend_model_id', 'title', 'description', 'is_open', 'created_at', 'updated_at',
    ];
}
