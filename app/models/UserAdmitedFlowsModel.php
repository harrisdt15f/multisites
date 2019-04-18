<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class UserAdmitedFlowsModel extends Model
{
    protected $table = 'user_admited_flows';

    protected $fillable = [
        'admin_id', 'admin_name', 'user_id', 'username', 'comment'
    ];
}
