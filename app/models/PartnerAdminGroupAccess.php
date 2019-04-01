<?php

namespace App\models;

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
}
