<?php

namespace App\Models\User\Fund;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use LaravelArdent\Ardent\Ardent;

class FrontendUsersBankCard extends Ardent
{
    public const INITIALIZATION_STATUS = 1;
    protected $appends = ['card_num'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function getCardNumAttribute()
    {
        $lastFour = mb_substr($this->card_number,-4);
        return '**** **** **** ' . $lastFour;
    }
}
