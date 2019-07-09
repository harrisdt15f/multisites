<?php
/**
 * @Author: Fish
 * @Date:   2019/7/9 14:05
 */

namespace App\Models\Game\Lottery;


use App\Models\BaseModel;
use App\Models\Game\Lottery\Logics\MethodsLogics;

class LotteryMethodsNumberButtonRule extends BaseModel
{
    use MethodsLogics;

    protected $guarded = ['id'];

}