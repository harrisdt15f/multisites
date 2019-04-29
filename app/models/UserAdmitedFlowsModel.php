<?php

namespace App\models;

class UserAdmitedFlowsModel extends BaseModel
{
    protected $table = 'user_admited_flows';

    protected $fillable = [
        'admin_id', 'admin_name', 'user_id', 'username', 'comment'
    ];
}
