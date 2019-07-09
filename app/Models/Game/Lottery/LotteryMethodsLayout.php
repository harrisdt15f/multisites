<?php
/**
 * @Author: Fish
 * @Date:   2019/7/9 11:32
 */

namespace App\Models\Game\Lottery;


use App\Models\BaseModel;
use App\Models\Game\Lottery\Logics\MethodsLogics;

class LotteryMethodsLayout extends BaseModel
{
    use MethodsLogics;

    protected $guarded = ['id'];

    public function getRule()
    {
        return $this->hasOne(LotteryMethodsNumberRule::class,'id','rule_id');
    }
}