<?php

namespace App\Models\DeveloperUsage\Frontend;

use App\Models\BaseModel;

class FrontendAppRoute extends BaseModel
{
    protected $table = 'frontend_app_route';

    protected $fillable = [
        'route_name', 'controller', 'method', 'frontend_model_id', 'title', 'description', 'is_open', 'created_at', 'updated_at',
    ];
}
