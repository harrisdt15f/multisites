<?php

namespace App\models;


class PartnerLogsApi extends BaseModel
{
    protected $table = 'parter_logs_api';

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
        'admin_id',
        'admin_name',
        'user_id',
        'username',
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
        'route_id',
        'menu_id',
        'menu_label',
        'menu_path',
        'created_at',
        'updated_at',
    ];
    /**
     * @var array $guarded
     */
    protected $guarded = ['id'];
}
