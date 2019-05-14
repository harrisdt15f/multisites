<?php

namespace App\models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class PartnerSysConfigures extends BaseModel
{
    protected $table = 'partner_sys_configures';

    protected $fillable = [
        'parent_id',
        'pid',
        'sign',
        'name',
        'description',
        'value',
        'add_admin_id',
        'last_update_admin_id',
        'status'
    ];

    public function childs(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id', 'id');
    }


}
