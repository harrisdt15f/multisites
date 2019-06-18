<?php

namespace App\Models\Admin\Homepage;

use App\Models\BaseModel;
use App\Models\Game\Lottery\LotteryList;

class FrontendLotteryRedirectBetList extends BaseModel
{
    protected $fillable = [
        'lotteries_id', 'pic_path', 'sort', 'created_at', 'updated_at',
    ];
    public function lotteries()
    {
        return $this->hasOne(LotteryList::class, 'id', 'lotteries_id');
    }
}
