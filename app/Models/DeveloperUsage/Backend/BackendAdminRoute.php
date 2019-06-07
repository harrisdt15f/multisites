<?php

namespace App\Models\DeveloperUsage\Backend;

use App\Models\BaseModel;
use App\Models\DeveloperUsage\Menu\BackendSystemMenu;

class BackendAdminRoute extends BaseModel
{
    protected $fillable = [
        'route_name', 'controller', 'method', 'menu_group_id', 'title', 'description', 'created_at', 'updated_at',
    ];
    public function menu()
    {
        return $this->belongsTo(BackendSystemMenu::class, 'menu_group_id', 'id');
    }
}
