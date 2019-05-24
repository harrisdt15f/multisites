<?php

namespace App\Models;

class PartnerAdminGroupAccess extends BaseModel
{
    protected $table = 'partner_access_group';
    protected $fillable = [
        'group_name', 'role', 'status',
    ];
    public function getTableColumns() {
        return $this
            ->getConnection()
            ->getSchemaBuilder()
            ->getColumnListing($this->getTable());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function adminUsers()
    {
        return $this->hasMany(PartnerAdminUsers::class,'group_id','id')->select(['id','name','email','is_test','status','platform_id','group_id']);
    }
}
