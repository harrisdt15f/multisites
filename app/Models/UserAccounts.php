<?php

namespace App\Models;

class UserAccounts extends BaseModel
{
    protected $table = 'user_accounts';

    protected $fillable = [
        'user_id', 'balance', 'frozen', 'status', 'created_at', 'updated_at',
    ];
}
