<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class HandleUserAccounts extends Model
{
    protected $table = 'user_accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'balance', 'frozen', 'status',
    ];
}
