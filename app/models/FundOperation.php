<?php

namespace App\models;

class FundOperation extends BaseModel
{
    protected $table = 'fund_operation';

    protected $fillable = [
        'user_id', 'fund',
    ];
}
