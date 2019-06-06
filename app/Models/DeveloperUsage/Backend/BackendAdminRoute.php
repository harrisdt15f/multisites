<?php

namespace App\Models\DeveloperUsage\Backend;

use App\Models\BaseModel;
use App\Models\DeveloperUsage\Menu\PartnerMenus;

class BackendAdminRoute extends BaseModel
{
    protected $table = 'backend_admin_routes';

    protected $fillable = [
        'route_name', 'controller', 'method', 'menu_group_id', 'title', 'description', 'created_at', 'updated_at',
    ];
    public function menu()
    {
        return $this->belongsTo(PartnerMenus::class, 'menu_group_id', 'id');
    }
}
