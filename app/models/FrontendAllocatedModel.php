<?php

namespace App\models;

class FrontendAllocatedModel extends BaseModel
{
    protected $table = 'frontend_allocated_model';

    protected $fillable = [
        'label', 'en_name', 'pid', 'type', 'updated_at', 'created_at',
    ];

    public function childs()
    {
        $data = $this->hasMany(__CLASS__, 'pid', 'id');
        return $data;
    }
}
