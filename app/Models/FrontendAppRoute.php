<?php

namespace App\Models;

class FrontendAppRoute extends BaseModel
{
    protected $table = 'frontend_app_route';

    protected $fillable = [
        'route_name', 'frontend_model_id', 'title', 'description', 'created_at', 'updated_at',
    ];
}
