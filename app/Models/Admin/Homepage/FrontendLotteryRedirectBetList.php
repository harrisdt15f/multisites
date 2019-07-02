<?php

namespace App\Models\Admin\Homepage;

use App\Models\BaseModel;
use App\Models\Game\Lottery\LotteryList;

class FrontendLotteryRedirectBetList extends BaseModel
{
    protected $guarded = ['id'];

    public function lotteries()
    {
        return $this->hasOne(LotteryList::class, 'id', 'lotteries_id');
    }
}
