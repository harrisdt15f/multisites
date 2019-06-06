<?php

/**
 * @Author: LingPh
 * @Date:   2019-05-30 14:30:03
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-06 12:02:02
 */
namespace App\Models\DeveloperUsage\MethodLevel;

use App\Models\BaseModel;
use App\Models\DeveloperUsage\MethodLevel\Traits\MethodLevelLogics;

class LotteryMethodsWaysLevel extends BaseModel
{
    use MethodLevelLogics;
    protected $table = 'lottery_methods_ways_levels';

    protected $fillable = [
        'method_id', 'level', 'position', 'count', 'prize', 'created_at', 'updated_at',
    ];
}
