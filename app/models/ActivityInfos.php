<?php

namespace App\models;

class ActivityInfos extends BaseModel
{
    protected $table = 'partner_activity_infos';

    protected $fillable = [
        'title', 'type', 'content', 'pic_path', 'thumbnail_path', 'start_time', 'end_time', 'status', 'admin_id', 'admin_name', 'redirect_url', 'is_time_interval', 'created_at', 'updated_at',
    ];
}
