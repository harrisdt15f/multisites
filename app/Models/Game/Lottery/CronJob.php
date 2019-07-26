<?php

namespace App\Models\Game\Lottery;

use App\Models\BaseModel;
use App\Models\Game\Lottery\Logics\CronJobLogics;

class CronJob extends BaseModel
{
	use CronJobLogics;
	
	public const COMMAND = 'LotterySchedule';
	public const STATUS_OPEN = 1;
	public const STATUS_CLOSE = 0;

    protected $guarded = ['id'];
}
