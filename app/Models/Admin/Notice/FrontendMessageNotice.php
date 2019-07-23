<?php

namespace App\Models\Admin\Notice;

use App\Models\Admin\BackendAdminUser;
use App\Models\BaseModel;

class FrontendMessageNotice extends BaseModel
{
	public const UN_READ=0;//未读
	public const READ=1;//已读

    protected $guarded = ['id'];

    /**
     * 公告|站内信 详情信息
     */
    public function messageContent()
    {
    	return $this->hasOne(FrontendMessageNoticesContent::class,'id','notices_content_id');
    }
}
