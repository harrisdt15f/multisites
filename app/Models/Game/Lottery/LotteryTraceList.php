<?php

namespace App\Models\Game\Lottery;

use App\Models\BaseModel;
use App\Models\LotteryTrace;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LotteryTraceList extends BaseModel
{
    protected $guarded = ['id'];
    public const STATUS_RUNNING = 0;
    public const STATUS_FINISHED = 1;
    public const STATUS_USER_STOPED = 2;
    public const STATUS_ADMIN_STOPED = 3;
    public const STATUS_SYSTEM_STOPED = 4;

    public function trace(): BelongsTo
    {
        return $this->belongsTo(LotteryTrace::class, 'trace_id', 'id');
    }
}
