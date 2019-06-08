<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 6/8/2019
 * Time: 3:20 PM
 */

namespace App\Models\Game\Lottery;

<<<<<<< HEAD
=======

>>>>>>> master
use App\Models\BaseModel;

class LotterySerie extends BaseModel
{
    protected $guarded = ['id'];

    public function lotteries()
    {
<<<<<<< HEAD
        return $this->hasMany(LotteryList::class, 'series_id', 'series_name');
    }

}
=======
        return $this->hasMany(LotteryList::Class, 'series_id', 'series_name');
    }

}
>>>>>>> master
