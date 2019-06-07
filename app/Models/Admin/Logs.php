<?php

namespace App\Models\Admin;
use App\Models\BaseModel;

use App\Models\DeveloperUsage\Backend\BackendAdminRoute;
class Logs extends BaseModel
{
    public const PHONE = 1;
    public const DESKSTOP = 2;
    public const ROBOT = 3;
    public const MOBILE = 4;
    public const TABLET = 5;
    public const OTHER = 6;

    protected $fillable = [
        'description',
        'origin',
        'type',
        'result',
        'level',
        'token',
        'ip',
        'ips',
        'user_id',
        'user_agent',
        'session',
        'lang',
        'device',
        'os',
        'os_version',
        'browser',
        'bs_version',
        'device_type',
        'inputs',
        'route',
        'created_at',
        'updated_at',
    ];
    /**
     * @var array $guarded
     */
    protected $guarded = ['id'];


    public function route()
    {
        return $this->hasOne(BackendAdminRoute::class,'id','rout_id');
    }
}
