<?php

namespace App\Models\Admin\Activity;

use App\Models\BaseModel;

class BackendAdminMessageArticle extends BaseModel
{
    protected $fillable = [
        'category_id', 'title', 'summary', 'content', 'search_text', 'is_for_agent', 'add_admin_id', 'status', 'last_update_admin_id', 'sort', 'created_at', 'updated_at', 'audit_flow_id', 'pic_path',
    ];
}
