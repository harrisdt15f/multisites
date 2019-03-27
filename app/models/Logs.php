<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    protected $table = 'logs';
    protected $fillable = [
        'description', 'origin', 'type','result','level','token','ip','user_agent','session','created_at','updated_at'
    ];
    /**
     * @var array $guarded
     */
//    protected $guarded = ['id'];
}
