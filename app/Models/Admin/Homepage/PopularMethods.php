<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-04 14:41:55
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-04 16:33:31
 */

namespace App\Models\Admin\Homepage;

use App\Models\BaseModel;
use App\Models\Game\Lottery\LotteriesModel;

class PopularMethods extends BaseModel
{
    protected $table = 'popular_methods';

    protected $fillable = [
        'method_id', 'sort', 'created_at', 'updated_at',
    ];

    public function method()
    {
        return $this->hasOne(HomeDefaultBetMethods::class, 'id', 'method_id');
    }
}
