<?php

namespace App\models;

class PartnerAdminRoute extends BaseModel
{
    protected $table = 'partner_admin_route';

    public function menu()
    {
        return $this->belongsTo(PartnerMenus::class,'id','partner_ad_route_id');
    }

    public function parentRoute()
    {
        return $this->belongsTo(__CLASS__,'route_parent_id','id');
    }
}
