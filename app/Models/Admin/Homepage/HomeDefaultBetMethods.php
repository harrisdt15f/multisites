<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-04 14:53:23
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-04 14:54:56
 */
namespace App\Models\Admin\Homepage;

use App\Models\BaseModel;
use App\Models\Game\Lottery\LotteriesModel;

class HomeDefaultBetMethods extends BaseModel
{
    protected $table = 'home_default_bet_methods';

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
