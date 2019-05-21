<?php

namespace App\models;

class HomepageRotationChart extends BaseModel
{
    protected $table = 'homepage_rotation_chart';

    protected $fillable = [
        'title', 'content', 'pic_path', 'thumbnail_path', 'type', 'redirect_url', 'activity_id', 'status', 'start_time', 'end_time', 'sort', 'created_at', 'updated_at',
    ];

    public function activity(){
    	$data = $this->hasOne(ActivityInfos::class,'id','activity_id');
    	return $data;
    }
}
