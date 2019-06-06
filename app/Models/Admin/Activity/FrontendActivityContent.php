<?php

namespace App\Models\Admin\Activity;

use App\Models\BaseModel;

class FrontendActivityContent extends BaseModel
{
    protected $table = 'frontend_activity_contents';

    protected $fillable = [
        'title', 'content', 'pic_path', 'is_time_interval', 'thumbnail_path', 'start_time', 'end_time', 'status', 'admin_id', 'admin_name', 'redirect_url', 'sort', 'created_at', 'updated_at',
    ];
}
