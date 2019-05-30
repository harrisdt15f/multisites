<?php

/**
 * @Author: LingPh
 * @Date:   2019-05-30 14:30:03
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-05-30 15:43:41
 */
namespace App\Models\DeveloperUsage\MethodLevel;

use App\Models\BaseModel;
use App\Models\DeveloperUsage\MethodLevel\Traits\MethodLevelLogics;

class MethodLevel extends BaseModel
{
    use MethodLevelLogics;
    protected $table = 'methods_way_levels';

    protected $fillable = [
        'method_id', 'level', 'position', 'count', 'prize', 'created_at', 'updated_at',
    ];
}
