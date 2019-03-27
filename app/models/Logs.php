<?php

namespace App\models;


class Logs extends BaseModel
{
    protected $table = 'logs';

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
        'created_at',
        'updated_at',
    ];
    /**
     * @var array $guarded
     */
    protected $guarded = ['id'];
}
