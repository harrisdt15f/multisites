<?php

namespace App\Models\Admin\Homepage;

use App\Models\Admin\Activity\FrontendActivityContent;
use App\Models\BaseModel;

class FrontendPageBanner extends BaseModel
{
    protected $table = 'frontend_page_banners';

    protected $fillable = [
        'title', 'content', 'pic_path', 'thumbnail_path', 'type', 'redirect_url', 'activity_id', 'status', 'start_time', 'end_time', 'sort', 'created_at', 'updated_at',
    ];

    public function activity()
    {
        $data = $this->hasOne(FrontendActivityContent::class, 'id', 'activity_id');
        return $data;
    }
}
