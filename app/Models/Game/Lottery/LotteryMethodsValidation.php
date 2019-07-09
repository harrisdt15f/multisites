<?php
/**
 * @Author: Fish
 * @Date:   2019/7/9 10:46
 */

namespace App\Models\Game\Lottery;


use App\Models\BaseModel;
use App\Models\Game\Lottery\Logics\LotteryMethodsValidationLogics;

class LotteryMethodsValidation extends BaseModel
{
    use LotteryMethodsValidationLogics;

    protected $guarded = ['id'];

    public function methodsLayout()
    {
        return $this->hasMany(LotteryMethodsLayout::class,'validation_id','id');
    }
}