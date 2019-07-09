<?php

namespace App\Models\Game\Lottery\Logics;


/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 5/24/2019
 * Time: 5:40 PM
 */
trait MethodsLogics
{
    public static function getMethodConfig($lotterySign)
    {
        return self::where('lottery_id', $lotterySign)->where('status', 1)->where('show', 1)->get();
    }
}
