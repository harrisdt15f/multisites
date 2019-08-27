<?php

namespace App\Models\User\Fund;

use LaravelArdent\Ardent\Ardent;

class FrontendUsersBankCard extends Ardent
{
    public const NATURAL_STATUS = 1;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
