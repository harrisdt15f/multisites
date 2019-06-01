<?php

namespace App\Models;

use App\Models\Logics\TraceTraits;

class Trace extends BaseModel
{
    use TraceTraits;
    protected $table = 'traces';

}
