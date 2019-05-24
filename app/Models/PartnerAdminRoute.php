<?php

namespace App\Models;

class PartnerAdminRoute extends BaseModel
{
    protected $table = 'partner_admin_route';

    protected $fillable = [
        'route_name', 'menu_group_id', 'title', 'description', 'created_at', 'updated_at',
    ];
    public function menu()
    {
        return $this->belongsTo(PartnerMenus::class, 'menu_group_id', 'id');
    }
}
