<?php

namespace App\Models;

use LaravelArdent\Ardent\Ardent;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class BaseModel extends Ardent
{
    use Cachable;
}
