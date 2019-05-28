<?php

namespace App\Models\DeveloperUsage\Frontend;

use App\Models\BaseModel;

class FrontendAppRoute extends BaseModel
{
    protected $table = 'frontend_app_route';

    protected $fillable = [
        'route_name', 'frontend_model_id', 'title', 'description', 'created_at', 'updated_at',
    ];
}
