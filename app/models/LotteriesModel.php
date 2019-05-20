<?php

namespace App\models;

use App\Jobs\IssueInserter;
use App\Jobs\IssueSeparateGenJob;
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

    public function issueRule()
    {
        return $this->hasOne(IssueRulesModel::class, 'lottery_id', 'en_name');
    }

    public function gameMethods()
    {
        return $this->hasOne(MethodsModel::class, 'lottery_id', 'en_name')->where('status',1);
    }

    /** ================================= 奖期生成 ================================== */

    /**
     * 根据开始时间和结束时间生成奖期
     * type:day 每日增加型
     * type:increase 递增型 需要初始奖期
     * type:random 日期随机型　需要开奖时间
     * @param $startDay 开始时间 2019-05-21
     * @param $endDay   结束时间 2019-05-29
     * @param $openTime 开奖时间 六合彩专用
     * @return array|string
     */
    public function genIssue($startDay, $endDay, $openTime = null)
    {
        // 是否开启
        if ($this->status != 1) {
            return "对不起,彩种{$this->cn_name}未开启!!";
        }
        // 时间范围
        if (strtotime($startDay) > strtotime($endDay)) {
            return '对不起,结束时间不能小于开始时间!!';
        }
        // 是否选择了开始奖期
        if ($this->issue_type == 'random' && !$openTime) {
            return '您选择的彩种需要开奖时间!';
        }
        $rules = IssueRulesModel::where('lottery_id', $this->en_name)->orderBy('id', "ASC")->get();
        $daySet = $this->getDaySet($startDay, $endDay);
        foreach ($daySet as $day) {
            dispatch(new IssueSeparateGenJob($day, $rules, $this))->onQueue('issues');
        }
        return true;
    }

    /**
     * 获取时间集合
     * @param $startDay
     * @param $endDay
     * @return array
     */
    public function getDaySet($startDay, $endDay)
    {
        $data = [];
        $today = Carbon::today();
        $dtStartTime = Carbon::parse($startDay);
        $dtEndTime = Carbon::parse($endDay);

        if ($dtStartTime->greaterThan($dtEndTime)) {
            return $data;
        }
        if (!$dtStartTime->lessThan($today) && !$dtEndTime->lessThan($today)) {
            do {
                $data[] = $dtStartTime->format('Y-m-d');
            } while ($dtStartTime->addDay()->lessThanOrEqualTo($dtEndTime));
        }
        return $data;
    }

    // 生成 某天的奖期
    public function _genIssue($day, $rules)
    {
        if (!$rules) {
            return "对不起, 彩种{$this->cn_name}未配置奖期规则!!";
        }
        // 整数形式的日期
        $intDay = date('Ymd', strtotime($day));
        // 检查是否存在奖期
        $issueCount = IssueModel::where('lottery_id', $this->en_name)->where('day', $intDay)->count();
        if ($issueCount > 0 && $issueCount < $this->day_issue) {
            // 删除重新来
            IssueModel::where('lottery_id', $this->en_name)->where('day', $intDay)->delete();
        } else if ($issueCount == $this->day_issue) {
            return "对不起, 彩种{$this->cn_name}-{$intDay}-已经生成!!";
        }
        $firstIssueNo = '';
        $data = [];
        // 累加型的获取
        if ($this->issue_type == 'increase') {
            $config = config('game.issue.issue_fix');
            if (isset($config[$this->en_name])) {
                $_config = $config[$this->en_name];
                $dayTime = Carbon::parse($day);
                $configTime = Carbon::parse($_config['day']);
                $_day = $dayTime->diff($configTime)->days;
                if (isset($_config['zero_start'])) {
                    $firstIssueNo = intval($_config['start_issue']) + $_day * $this->day_issue;
                    $firstIssueNo = $_config['zero_start'] . $firstIssueNo;
                } else {
                    $firstIssueNo = $_config['start_issue'] + $_day * $this->day_issue;
                }
            }
        }

        // 生成
        $issueNo = $firstIssueNo ?: '';
        foreach ($rules as $rule) {
            $adjustTime = $rule->adjust_time;
            $beginTimeString = $day . ' ' . $rule['begin_time'];
            $beginTime = Carbon::parse($beginTimeString);
            // 结束时间的修正
            $endTimeString = $day . ' ' . $rule['end_time'];
            $endTimeOrigin = Carbon::parse($endTimeString);
            $endTime = $endTimeOrigin->copy();
            if ($rule['end_time'] == '00:00:00') {
                $endTime = $endTime->addDay();

            } else if ($beginTime->greaterThan($endTimeOrigin)) {
                $endTime = $endTime->addDay();
            }
            $endTime = $endTime->subSeconds($adjustTime);
            $issueTimeInSeconds = $rule['issue_seconds'];
            $index = 1;
            do {
                if (1 === $index) {
                    $issueTimeString = $day . ' ' . $rule['first_time'];
                    $issueTime = Carbon::parse($issueTimeString);
                    $officialOpenTime = $issueTime->copy();
                    $issueEndTime = $issueTime->copy();
                    $issueEndTime = $issueEndTime->subSeconds($adjustTime);
                } else {
                    $issueEndTime = $beginTime->copy();
                    $issueEndTime = $issueEndTime->addSeconds($issueTimeInSeconds);
                    $officialOpenTime = $beginTime->copy();
                    $officialOpenTime = $officialOpenTime->addSeconds($issueTimeInSeconds)->addSeconds($adjustTime);
                }
                $item = [
                    'lottery_id' => $this->en_name,
                    'issue_rule_id' => $rule->id,
                    'lottery_name' => $this->cn_name,
                    'begin_time' => $beginTime->timestamp,
                    'end_time' => $issueEndTime->timestamp,
                    'official_open_time' => $officialOpenTime->timestamp,
                    'allow_encode_time' => $officialOpenTime->timestamp + $rule['encode_time'],
                    'day' => $intDay,
                    'created_at' => Carbon::now(),
                ];
//                dd($issueEndTime, $issueEndTime->timestamp, $index);
                if ($firstIssueNo) {
                    $item['issue'] = $issueNo;
                    $issueNo = $this->getNextIssueNo($issueNo, $this, $rule, $day);
                } else {
                    $issueNo = $this->getNextIssueNo($issueNo, $this, $rule, $day);
                    $item['issue'] = $issueNo;
                }
                $data[] = $item;
                $beginTime = $issueEndTime->copy();
                $index++;
            } while ($beginTime->lessThan($endTime));

        }
        $totalGenCount = count($data);
        if ($totalGenCount != $this->day_issue) {
            return "生成的期数不正确, 应该：{$this->day_issue} - 实际:{$totalGenCount}";
        }

        try {
            $insert_data = collect($data);
            $chunks = $insert_data->chunk(10);
            foreach ($chunks as $chunk) {
                // 插入
                dispatch(new IssueInserter($chunk->toArray()))->onQueue('issues');
            }
            return true;
        } catch (\Exception $e) {
            return '插入数据失败!!' . $e->getMessage();
        }
    }

    /**
     * 获取下一期的
     * @param $issueNo
     * @param $lottery
     * @param $day
     * @return mixed
     */
    public function getNextIssueNo($issueNo, $lottery, $rule, $day)
    {
        $dayTime = Carbon::parse($day);
        $issueFormat = $lottery->issue_format;
        $formats = explode('|', $issueFormat);
        // C 开头
        if (count($formats) == 1 and strpos($formats[0], 'C') !== false) {
            $currentIssueNo = intval($issueNo);
            $nextIssue = $currentIssueNo + 1;
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
                $suffix = $dayTime->format($formats[0]);
                if ($issueNo) {
                    return $suffix . $this->getNextNumber($issueNo, $numberLength);
                } else {
                    return $suffix . str_pad(1, $numberLength, '0', STR_PAD_LEFT);
                }
            }
            // 特殊号
            if (strpos($formats[1], 'T') !== false) {
                $suffix = $dayTime->format($formats[0]);
                if ($issueNo) {
                    return $suffix . $this->getNextNumber($issueNo, $numberLength);
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
    public function getNextNumber($issueNo, $count)
    {
        $currentNo = substr($issueNo, -$count);
        $nextNo = intval($currentNo) + 1;
        return str_pad($nextNo, $count, '0', STR_PAD_LEFT);
    }

    public function getFormatMode() {
        $modeConfig     = config('game.main.modes');
        $currentModes   = explode(",", $this->valid_modes);

        $data = [];
        foreach ($currentModes as $index) {
            $_mode = $modeConfig[$index];
            $data[$index] = $_mode;
        }

        return $data;
    }
}
