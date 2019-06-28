<?php

namespace App\Models;

use App\Models\Game\Lottery\LotteryTraceList;
use App\Models\Logics\TraceTraits;

class LotteryTrace extends BaseModel
{
    use TraceTraits;
    protected $guarded = ['id'];

    public const STATUS_RUNNING = 0;
    public const STATUS_FINISHED = 1;
    public const STATUS_USER_CANCELED = 2;
//    public const STATUS_ADMIN_CANCELED = 3;
    public const STATUS_SYSTEM_CANCELED = 4;
    public const STATUS_USER_DROPED = 5;

    public function traceRunningLists()
    {
        return $this->hasMany(LotteryTraceList::class, 'trace_id', 'id')->where('status', 0);
    }
}
