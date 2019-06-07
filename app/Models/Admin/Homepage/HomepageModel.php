<?php

namespace App\Models\Admin\Homepage;

use App\Models\BaseModel;

class HomepageModel extends BaseModel
{
    protected $fillable = [
        'model_name', 'pid', 'key', 'value', 'show_num', 'status', 'is_edit_value', 'is_edit_show_num', 'created_at', 'updated_at',
    ];
}
