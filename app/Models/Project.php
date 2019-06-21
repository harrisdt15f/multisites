<?php

namespace App\Models;

use App\Models\Logics\ProjectTraits;

class Project extends BaseModel
{
    use ProjectTraits;

    protected $guarded = ['id'];

    public const STATUS_NORMAL = 0;
    public const STATUS_DROPED = 1;
    public const STATUS_LOST = 2;
    public const STATUS_WON = 3;
    public const STATUS_PRIZE_SENT = 4;
    public const STATUS_DROPED_BY_SYSTEM = 5;

}
