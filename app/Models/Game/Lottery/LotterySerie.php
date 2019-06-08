<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 6/8/2019
 * Time: 3:20 PM
 */

namespace App\Models\Game\Lottery;

use App\Models\BaseModel;

class LotterySerie extends BaseModel
{
    protected $guarded = ['id'];

    public function lotteries()
    {
        return $this->hasMany(LotteryList::Class, 'series_id', 'series_name');
    }
}
