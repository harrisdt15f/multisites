<?php namespace App\Lib\Logic;

use App\Models\Game\Issue;
use App\Models\Game\IssueRule;
use Illuminate\Support\Facades\DB;

/**
 * 奖期相关功能
 * Class IssueFun
 * @package App\Lib\Logic
 */
class IssueFun {

    /** ================================= 奖期生成 ================================== */

    /**
     * 根据开始时间和结束时间生成奖期
     * type:day 每日增加型
     * type:increase 递增型 需要初始奖期
     * type:random 日期随机型　需要开奖时间
     * @param $lottery  object 彩种
     * @param $startDay string 开始时间 2019-05-21
     * @param $endDay   string 结束时间 2019-05-29
     * @param $openTime string 开奖时间 六合彩专用
     * @return array|string
     */
    static function genIssue($lottery, $startDay, $endDay, $openTime = null) {
        // 是否开启
        if ($lottery->status != 1) {
            return "对不起,彩种{$lottery->cn_name}未开启!!";
        }

        // 时间范围
        if (strtotime($startDay) > strtotime($endDay)) {
            return "对不起,结束时间不能小于开始时间!!";
        }

        // 是否选择了开始奖期
        if ($lottery->issue_type == 'random' && !$openTime) {
            return "对不起, 您选择的彩种需要开奖时间!";
        }

        $rules  = IssueRule::where('lottery_id', $lottery->en_name)->orderBy('id', "ASC")->get();

        $daySet = self::getDaySet($startDay, $endDay);

        $return = [];
        foreach ($daySet as $day) {
            $return[$day] = self::_genIssue($lottery, $day, $rules);
        }

        return $return;
    }

    /**
     * 更具规则 日期 生成奖期
     * @param $lottery
     * @param $day
     * @param $rules
     * @return bool|string
     */
    public static function _genIssue($lottery, $day, $rules) {
        if (!$rules) {
            return "对不起, 彩种{$lottery->cn_name}未配置奖期规则!!";
        }
        // 整数形式的日期
        $intDay = date('Ymd', strtotime($day));
        // 检查是否存在奖期
        $issueCount = Issue::where('lottery_id', $lottery->en_name)->where('day', $intDay)->count();
        // 删除重新来
        if ($issueCount > 0 && $issueCount < $lottery->day_issue) {
            Issue::where('lottery_id', $lottery->en_name)->where('day', $intDay)->delete();
        } else if ($issueCount == $lottery->day_issue) {
            return "对不起, 彩种{$lottery->cn_name}-{$intDay}-已经生成!!";
        }
        $firstIssueNo   = '';
        $data           = [];
        // 累加型的获取
        if ($lottery->issue_type == 'increase') {
            $config = config('game.issue.issue_fix');
            if (isset($config[$lottery->en_name])) {
                $_config    = $config[$lottery->en_name];
                $_day       = (strtotime($day) - strtotime($_config['day'])) / 86400;
                $_day       = ceil($_day);
                if (isset($_config['zero_start'])) {
                    $firstIssueNo = intval($_config['start_issue']) + $_day * $lottery->day_issue;
                    $firstIssueNo = $_config['zero_start'] . $firstIssueNo;
                } else {
                    $firstIssueNo = $_config['start_issue'] + $_day * $lottery->day_issue;
                }
            }
        }
        // 生成
        $issueNo = $firstIssueNo ? $firstIssueNo : '';
        foreach ($rules as $index => $rule) {

            $adjustTime = $rule->adjust_time;
            $beginTime  = strtotime($day .' '. $rule['begin_time']);

            // 结束时间的修正
            if ($rule['end_time'] == "00:00:00") {
                $endTime    = strtotime($day . " " . $rule['end_time']) + 86400   - $adjustTime;
            } else {
                $endTime    = strtotime($day .' '. $rule['end_time'])   - $adjustTime;
                // 如果跨天
                if (strtotime($day .' '. $rule['begin_time']) > strtotime($day . " " . $rule['end_time'])) {
                    $endTime = $endTime + 86400;
                }
            }
            $issueTime  = $rule['issue_seconds'];
            $index   = 1;
            do {
                if (1 == $index) {
                    $issueEnd           = strtotime($day . " " . $rule['first_time']) - $adjustTime;
                    $officialOpenTime   = strtotime($day . " " . $rule['first_time']);
                } else {
                    $issueEnd           = $beginTime + $issueTime;
                    $officialOpenTime   = $beginTime + $issueTime + $adjustTime;
                }
                $item = [
                    'lottery_id'            => $lottery->en_name,
                    'issue_rule_id'         => $rule->id,
                    'lottery_name'          => $lottery->cn_name,
                    'begin_time'            => $beginTime,
                    'end_time'              => $issueEnd,
                    'official_open_time'    => $officialOpenTime,
                    'allow_encode_time'     => $officialOpenTime + $rule['encode_time'],
                    'day'                   => $intDay,
                ];
                if ($firstIssueNo) {
                    $item['issue'] = $issueNo;
                    $issueNo = self::getNextIssueNo($issueNo, $lottery, $rule, $day);
                } else {
                    $issueNo = self::getNextIssueNo($issueNo, $lottery, $rule, $day);
                    $item['issue'] = $issueNo;
                }
                $data[] = $item;
                $beginTime = $issueEnd;
                $index ++;
            }while($beginTime < $endTime);
        }
        $totalGenCount  = count($data);
        if ($totalGenCount != $lottery->day_issue) {
            return "生成的期数不正确, 应该：{$lottery->day_issue} - 实际:{$totalGenCount}";
        }
        // 插入
        $res = DB::table('issues')->insert($data);
        if ($res) {
            return true;
        }
        return '插入数据失败!!';
    }

    /**
     * 获取下一期的
     * @param $issueNo
     * @param $lottery
     * @param $day
     * @return mixed
     */
    public static function getNextIssueNo($issueNo, $lottery, $rule, $day) {
        $dayTime        = strtotime($day);
        $issueFormat    = $lottery->issue_format;
        $formats = explode('|', $issueFormat);
        // C 开头
        if (count($formats) == 1 and strpos($formats[0], 'C') !== false) {
            $currentIssueNo = intval($issueNo);
            $nextIssue      = $currentIssueNo + 1;
            if (strlen($currentIssueNo) == strlen($issueNo)) {
                return $nextIssue;
            } else {
                return str_pad($nextIssue, strlen($issueNo), '0', STR_PAD_LEFT);
            }
        }

        // 日期型
        if (count($formats) == 2) {
            $numberLength = substr($formats[1], -1);
            // 时时彩 / 乐透
            if (strpos($formats[1], 'N') !== false) {
                $suffix = date($formats[0], $dayTime);
                if ($issueNo) {
                    return $suffix . self::getNextNumber($issueNo, $numberLength);
                } else {
                    return $suffix . str_pad(1, $numberLength, '0', STR_PAD_LEFT);
                }
            }

            // 特殊号
            if (strpos($formats[1], 'T') !== false) {

                $suffix = date($formats[0], $dayTime);

                if ($issueNo) {
                    return $suffix . self::getNextNumber($issueNo, $numberLength);
                } else {
                    return $suffix . str_pad(1, $numberLength, '0', STR_PAD_LEFT);
                }
            }
        }
    }

    /**
     * 获取下一个
     * @param $issueNo
     * @param $count
     * @return string
     */
    public static function getNextNumber($issueNo, $count) {
        $currentNo  = substr($issueNo, -$count);
        $nextNo     = intval($currentNo) + 1;
        return str_pad($nextNo, $count, '0', STR_PAD_LEFT);
    }

    /**
     * 获取时间集合
     * @param $startDay
     * @param $endDay
     * @return array
     */
    public static function getDaySet($startDay, $endDay) {
        $data = [];
        $dtStart = strtotime($startDay);
        $dtEnd   = strtotime($endDay);

        if ($dtStart > $dtEnd) {
            return $data;
        }

        do {
            $data[] = date('Y-m-d', $dtStart);
        } while (($dtStart += 86400) <= $dtEnd);

        return $data;
    }
}
