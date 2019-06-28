<?php

namespace App\Models;

use App\Models\Game\Lottery\LotteryIssue;
use App\Models\Game\Lottery\LotteryTraceList;
use App\Models\Logics\ProjectTraits;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends BaseModel
{
    use ProjectTraits;

    protected $guarded = ['id'];

    public const STATUS_NORMAL = 0;
    public const STATUS_DROPED = 1;
    public const STATUS_LOST = 2;
    public const STATUS_WON = 3;
    public const STATUS_PRIZE_SENT = 4;
    public const STATUS_DROPED_BY_SYSTEM = 5;

    /**
     * @return HasOne
     */
    public function tracelist(): HasOne
    {
        return $this->hasOne(LotteryTraceList::class, 'project_id', 'id');
    }

}
