<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-04 14:41:55
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-08 14:35:03
 */

namespace App\Models\Admin\Homepage;

use App\Models\BaseModel;

class FrontendLotteryFnfBetableList extends BaseModel
{
    protected $fillable = [
        'lotteries_id', 'method_id', 'sort', 'created_at', 'updated_at',
    ];

    public function method()
    {
        return $this->hasOne(FrontendLotteryFnfBetableMethod::class, 'id', 'method_id');
    }
}
