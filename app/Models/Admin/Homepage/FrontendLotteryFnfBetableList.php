<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-04 14:41:55
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-14 11:31:47
 */

namespace App\Models\Admin\Homepage;

use App\Models\BaseModel;

class FrontendLotteryFnfBetableList extends BaseModel
{
    protected $guarded = ['id'];

    public function method()
    {
        return $this->hasOne(FrontendLotteryFnfBetableMethod::class, 'id', 'method_id')->select('id', 'lottery_name', 'method_name');
    }
}
