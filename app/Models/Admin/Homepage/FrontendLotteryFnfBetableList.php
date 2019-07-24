<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-04 14:41:55
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-14 11:31:47
 */

namespace App\Models\Admin\Homepage;

use App\Models\BaseModel;
use App\Models\Game\Lottery\LotteryIssue;

class FrontendLotteryFnfBetableList extends BaseModel
{
    protected $guarded = ['id'];

    public function method()
    {
        return $this->hasOne(FrontendLotteryFnfBetableMethod::class, 'id', 'method_id')->select('id', 'lottery_name', 'method_name');
    }

    public function currentIssue()
    {
        return $this->hasOne(LotteryIssue::class, 'lottery_id', 'lotteries_id')->where('end_time', '>', time())->orderBy('id', 'ASC');
    }
}
