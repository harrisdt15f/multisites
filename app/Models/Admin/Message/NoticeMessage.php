<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-01 14:51:08
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-06 12:10:13
 */

namespace App\Models\Admin\Message;

use App\Models\Admin\BackendAdminUser;
use App\Models\BaseModel;

class NoticeMessage extends BaseModel
{
	public const ARTIFICIAL = 1;
	public const AUDIT = 2;
	public const FUND = 3;

    protected $table = 'notice_messages';

    protected $fillable = [
        'type', 'message', 'created_at', 'updated_at',
    ];
}
