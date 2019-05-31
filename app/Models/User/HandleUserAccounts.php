<?php

namespace App\Models\User;

use App\Models\User\Fund\Logics\UserAccountLogics;
use LaravelArdent\Ardent\Ardent;

class HandleUserAccounts extends Ardent
{
    use UserAccountLogics;

    protected $table = 'user_accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'balance',
        'frozen',
        'status',
    ];

    public const FROZEN_STATUS_OUT = 1;
    public const FROZEN_STATUS_BACK = 2;
    public const FROZEN_STATUS_TO_PLAYER = 3;
    public const FROZEN_STATUS_TO_SYSTEM = 4;
    public const FROZEN_STATUS_BONUS = 5;


    public const MODE_CHANGE_AFTER = 2;
    public const MODE_CHANGE_NOW = 1;

    public $mode = 1;

    public function user()
    {
        return $this->belongsTo(UserHandleModel::class, 'id', 'user_id');
    }
}
