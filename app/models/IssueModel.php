<?php

namespace App\models;

class IssueModel extends BaseModel
{
    protected $table = 'issues';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lottery_id', 'lottery_name', 'issue', 'issue_rule_id', 'begin_time', 'end_time', 'official_open_time', 'allow_encode_time', 'official_code', 'status_encode', 'status_calculated', 'status_prize', 'status_commission', 'status_trace', 'encode_time', 'calculated_time', 'prize_time', 'commission_time', 'trace_time', 'encode_id', 'encode_username', 'day'
    ];


    /**
     * 获取所有可投奖期
     * @param $lotteryId
     * @param int $count
     * @return mixed
     */
    public static function getCanBetIssue ($lotteryId, $count = 50) {
        $time = time();
        return self::where('lottery_id', $lotteryId)->where('end_time', '>', $time)->orderBy('id', 'ASC')->skip(0)->take($count)->get();
    }
    /**
     * 获取上一期
     * @param string $lotterySign
     * @return mixed
     */
    public static function getLastIssue ($lotterySign) {
        $time = time();
        return self::where('lottery_id', $lotterySign)->where('end_time', '<=', $time)->orderBy('id', 'DESC')->first();
    }
}
