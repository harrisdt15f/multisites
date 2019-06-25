<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-25 16:26:27
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-25 17:53:57
 */
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
