<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-04 14:53:23
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-06 12:29:26
 */
namespace App\Models\Admin\Homepage;

use App\Models\BaseModel;

class FrontendLotteryFnfBetableMethod extends BaseModel
{
    protected $table = 'frontend_lottery_fnf_betable_methods';

    protected $fillable = [
        'series_id',
        'lottery_name',
        'lottery_id',
        'method_id',
        'method_name',
        'method_group',
        'method_row, group_sort',
        'tab_sort',
        'method_sort',
        'status',
    ];
}
