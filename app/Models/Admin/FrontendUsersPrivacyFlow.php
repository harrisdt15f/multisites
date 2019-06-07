<?php

namespace App\Models\Admin;

use App\Models\BaseModel;

class FrontendUsersPrivacyFlow extends BaseModel
{
    protected $fillable = [
        'admin_id', 'admin_name', 'user_id', 'username', 'comment',
    ];
}
