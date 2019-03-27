<?php

namespace App\models;
use LaravelArdent\Ardent\Ardent;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class BaseModel extends Ardent
{
    use Cachable;
}
