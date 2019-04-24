<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class IssueRulesModel extends Model
{
    protected $table = 'issue_rules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lottery_id', 'lottery_name', 'begin_time', 'end_time', 'issue_seconds', 'first_time', 'adjust_time', 'encode_time', 'issue_count', 'status',
    ];
}
