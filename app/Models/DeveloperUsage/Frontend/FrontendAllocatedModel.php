<?php

namespace App\Models\DeveloperUsage\Frontend;

use App\Models\BaseModel;
use App\Models\DeveloperUsage\Frontend\Traits\FrontendModelTraits;

class FrontendAllocatedModel extends BaseModel
{
    use FrontendModelTraits;
    protected $table = 'frontend_allocated_model';

    protected $fillable = [
        'label', 'en_name', 'pid', 'type', 'level', 'updated_at', 'created_at',
    ];

    public function childs()
    {
        $data = $this->hasMany(__CLASS__, 'pid', 'id');
        return $data;
    }
}
