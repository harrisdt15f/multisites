<?php

namespace App\Models\DeveloperUsage\Frontend;

use App\Models\BaseModel;
use App\Models\DeveloperUsage\Frontend\Traits\FrontendModelTraits;

class FrontendAllocatedModel extends BaseModel
{
    use FrontendModelTraits;

    protected $fillable = [
        'label', 'en_name', 'pid', 'type', 'level', 'updated_at', 'created_at',
    ];

    public function childs()
    {
        return $this->hasMany(__CLASS__, 'pid', 'id');
    }
}
