<?php

namespace App\Models\Admin;

use App\Models\Admin\BackendAdminUser;
use App\Models\BaseModel;

class BackendAdminAccessGroup extends BaseModel
{
    protected $fillable = [
        'group_name', 'role', 'status',
    ];
    public function getTableColumns()
    {
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
        return $this->hasMany(BackendAdminUser::class, 'group_id', 'id')->select(['id', 'name', 'email', 'is_test', 'status', 'platform_id', 'group_id']);
    }
}
