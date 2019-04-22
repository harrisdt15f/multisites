<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class LotteriesModel extends BaseModel
{
    protected $table = 'lotteries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cn_name', 'en_name', 'series_id', 'is_fast', 'auto_open', 'max_trace_number', 'day_issue', 'issue_format', 'issue_type', 'valid_code', 'code_length', 'positions', 'min_prize_group', 'max_prize_group', 'min_times', 'max_times', 'valid_modes', 'status',
    ];

//    public function getIssueFormatAttribute()
//    {
//        return  Carbon::now()->format($this->attributes['issue_format']);
//    }
}
