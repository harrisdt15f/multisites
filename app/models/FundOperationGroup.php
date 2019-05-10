<?php

namespace App\models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class FundOperationGroup extends BaseModel
{
    protected $table = 'fund_operation_group';

    protected $fillable = [
        'group_id', 'group_name',
    ];

    public function admins(): HasMany
    {
        return $this->hasMany(PartnerAdminUsers::class, 'group_id', 'group_id');
    }
}
