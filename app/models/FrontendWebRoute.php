<?php

namespace App\models;

class FrontendWebRoute extends BaseModel
{
    protected $table = 'frontend_web_route';

    protected $fillable = [
        'route_name', 'frontend_model_id', 'title', 'description', 'created_at', 'updated_at',
    ];
}
