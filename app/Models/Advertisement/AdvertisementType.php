<?php

namespace App\Models\Advertisement;

use App\Models\BaseModel;

class AdvertisementType extends BaseModel
{
    protected $table = 'advertisement_type';

    protected $fillable = [
        'name', 'type', 'status', 'ext_type', 'l_size', 'w_size', 'size', 'created_at', 'updated_at',
    ];
}
