<?php

namespace App\models;

class Notice extends BaseModel
{
    protected $table = 'partner_notice';

    protected $fillable = [
        'type', 'title', 'content', 'start_time', 'end_time', 'sort', 'status', 'admin_id', 'created_at', 'updated_at',
    ];
}
