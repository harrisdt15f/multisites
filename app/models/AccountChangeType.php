<?php

namespace App\models;

class AccountChangeType extends BaseModel
{
    protected $table = 'account_change_type';

    protected $fillable = [
        'name', 'sign', 'in_out', 'type',
    ];
}
