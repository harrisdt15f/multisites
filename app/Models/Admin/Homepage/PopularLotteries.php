<?php

namespace App\Models\Admin\Homepage;

use App\Models\BaseModel;
use App\Models\Game\Lottery\LotteriesModel;

class PopularLotteries extends BaseModel
{
    protected $table = 'popular_lotteries';

    protected $fillable = [
        'lotteries_id', 'pic_path', 'sort', 'created_at', 'updated_at',
    ];
    public function lotteries()
    {
        $data = $this->hasOne(LotteriesModel::class, 'id', 'lotteries_id');
        return $data;
    }
}
