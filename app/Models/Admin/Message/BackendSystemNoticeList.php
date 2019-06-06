<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-01 14:51:08
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-06 17:27:18
 */

namespace App\Models\Admin\Message;

use App\Models\Admin\BackendAdminUser;
use App\Models\BaseModel;

class BackendSystemNoticeList extends BaseModel
{
	public const ARTIFICIAL = 1;
	public const AUDIT = 2;
	public const FUND = 3;

    protected $table = 'backend_system_notice_lists';

    protected $fillable = [
        'type', 'message', 'created_at', 'updated_at',
    ];
}
