<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-01 14:51:08
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-07 13:41:36
 */

namespace App\Models\Admin\Message;

use App\Models\Admin\BackendAdminUser;
use App\Models\BaseModel;

class BackendSystemNoticeList extends BaseModel
{
	public const ARTIFICIAL = 1;
	public const AUDIT = 2;
	public const FUND = 3;

    protected $guarded = ['id'];
}
