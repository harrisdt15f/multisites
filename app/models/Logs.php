<?php

namespace App\models;


class Logs extends BaseModel
{
    protected $table = 'logs';
    protected $fillable = [
        'description', 'origin', 'type','result','level','token','ip','user_id','user_agent','session','created_at','updated_at'
    ];
    /**
     * @var array $guarded
     */
    protected $guarded = ['id'];
}
