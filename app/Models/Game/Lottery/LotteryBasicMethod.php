<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 6/19/2019
 * Time: 5:52 PM
 */

namespace App\Models\Game\Lottery;


use App\Models\BaseModel;
use App\Models\DeveloperUsage\MethodLevel\LotteryMethodsWaysLevel;
use App\Models\Game\Lottery\Logics\LotteryBasicMethodLogics;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LotteryBasicMethod extends BaseModel
{
    use LotteryBasicMethodLogics;

    protected $guarded = ['id'];

    public function prizeLevel(): HasMany
    {
        return $this->hasMany(LotteryMethodsWaysLevel::class, 'basic_method_id', 'id');
    }
}