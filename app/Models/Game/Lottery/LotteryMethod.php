<?php

namespace App\Models\Game\Lottery;

use App\Models\BaseModel;
use App\Models\Game\Lottery\Logics\MethodsLogics;

class LotteryMethod extends BaseModel
{
    use MethodsLogics;

    public const OPEN=1;
    public const CLOSE=0;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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

    public function methodRows()
    {
<<<<<<< HEAD
        return $this->hasMany(__CLASS__, 'method_group', 'method_group')->select(['method_row','status'])->groupBy('method_row');
=======
        return $this->hasMany(__CLASS__, 'method_group', 'method_group')->select(['method_row','status','lottery_id'])->groupBy('method_row');
>>>>>>> master
    }

    public function methodDetails()
    {
        return $this->hasMany(__CLASS__, 'lottery_id', 'lottery_id')->select([
            'id',
            'lottery_name',
            'method_group',
            'method_id',
            'method_row',
            'method_name',
            'status',
            'created_at',
            'updated_at',
        ]);
    }
}