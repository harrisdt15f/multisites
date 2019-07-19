<?php

namespace App\Models\Admin\Homepage;

use App\Models\BaseModel;
use App\Models\Game\Lottery\LotteryIssue;

class FrontendLotteryNoticeList extends BaseModel
{
    protected $guarded = ['id'];

    //各个彩种最新一期的开奖
    public function specificNewestOpenedIssue()
    {
        return $this->hasOne(LotteryIssue::class, 'lottery_id', 'lotteries_id')->select('lottery_id', 'issue', 'official_code', 'encode_time')->where('status_encode', 1)->orderBy('issue', 'desc');
    }
}
