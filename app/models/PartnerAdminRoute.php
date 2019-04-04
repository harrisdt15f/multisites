<?php

namespace App\models;

class PartnerAdminRoute extends BaseModel
{
    protected $table = 'partner_admin_route';

    public function menu()
    {
        return $this->belongsTo(PartnerMenus::class,'menu_group_id','id');
    }
}
