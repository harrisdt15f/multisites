<?php

namespace App\models;

class ActivityInfos extends BaseModel
{
    protected $table = 'partner_activity_infos';

    protected $fillable = [
        'title', 'content', 'pic_path', 'thumbnail_path', 'start_time', 'end_time', 'status', 'admin_id', 'admin_name', 'redirect_url', 'created_at', 'updated_at',
    ];
}
