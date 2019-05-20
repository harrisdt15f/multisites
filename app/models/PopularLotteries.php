<?php

namespace App\models;

class PopularLotteries extends BaseModel
{
    protected $table = 'popular_lotteries';

    protected $fillable = [
        'lotteries_id', 'type', 'pic_path', 'sort', 'created_at', 'updated_at',
    ];
}
