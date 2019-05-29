<?php

namespace App\Models;

use App\Models\Traits\TraceTraits;

class Trace extends BaseModel
{
    use TraceTraits;
    protected $table = 'traces';

}
