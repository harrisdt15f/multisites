<?php

namespace App\Models;

use App\Models\Traits\ProjectTraits;

class Project extends BaseModel
{
    use ProjectTraits;
    protected $table = 'projects';
}
