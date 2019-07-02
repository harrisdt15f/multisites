<?php

/**
 * @Author: LingPh
 * @Date:   2019-05-30 14:30:03
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-07 13:44:23
 */
namespace App\Models\DeveloperUsage\MethodLevel;

use App\Models\BaseModel;
use App\Models\DeveloperUsage\MethodLevel\Traits\MethodLevelLogics;

class LotteryMethodsWaysLevel extends BaseModel
{
    use MethodLevelLogics;

    protected $guarded = ['id'];
}
