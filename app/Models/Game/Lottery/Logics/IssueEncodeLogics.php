<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 6/17/2019
 * Time: 9:02 PM
 */

namespace App\Models\Game\Lottery\Logics;

use App\Jobs\Lottery\Encode\IssueEncoder;
use App\Models\Admin\Homepage\FrontendLotteryNoticeList;
use App\Models\Game\Lottery\LotteryIssue;
use Illuminate\Support\Arr;

trait IssueEncodeLogics
{

    /**
     * @param  string  $openCodeStr
     */
    public function recordEncodeNumber(string $openCodeStr): void
    {
        $this->status_encode = LotteryIssue::ENCODED;
        $this->encode_time = time();
        $this->official_code = $openCodeStr;

        if ($this->save()) {
            FrontendLotteryNoticeList::updateLotteryNotice($this); //开奖公告缓存更新
            //趋势分析记录
            LotteryIssue::cacheRe($this);

            dispatch(new IssueEncoder($this->toArray()))->onQueue('open_numbers');
        }
    }

    /**
     * 生成一个奖期合法的随机开奖号码
     * @param  int  $codeLength  [开奖号码的长度]
     * @param  string  $validCode  [合法开奖号码]
     * @param  int  $lotteryType  [开奖号码是否可以重复 ？ 1可重复 2不可重复]
     * @param  string|null  $splitter  [该彩种分割开奖号码的方式]
     * @param  string  $series  [彩种系列]
     * @return string  $openCodeStr  [开奖号码string]
     */
    public static function getOpenNumber($codeLength, $validCode, $lotteryType, $splitter, $series): string
    {
        $validCodeArr = explode(',', $validCode); //合法开奖号码arr
        if ($lotteryType === 2 || $series === 'pk10') {
            $openCodeStr = self::getOpenArr($validCodeArr, $codeLength, $splitter);
        } elseif ($lotteryType === 1) {
            $openCodeStr = self::getOpenArr($validCodeArr, $codeLength, $splitter, 1);
        } else {
            $openCodeStr = ''; //开奖号码string
        }
        return $openCodeStr;
    }

    /**
     * @param $validCodeArr
     * @param $codeLength
     * @param $splitter
     * @param  int  $duplicate
     * @return string
     */
    public static function getOpenArr($validCodeArr, $codeLength, $splitter, $duplicate = 0): string
    {
        $openCodeArr = [];//开奖号码array
        if ($duplicate === 1) {
            for ($length = 0; $length < $codeLength; $length++) {
                $openCodeArr[] = Arr::random($validCodeArr);
            }
        } else {
            $openCodeArr = Arr::random($validCodeArr, $codeLength);
        }
        shuffle($openCodeArr); //打乱号码顺序
        //开奖号码string
        return implode($splitter, $openCodeArr);
    }

    /**
     * 奖期录号
     * @param  string  $lotteryId
     * @param  int  $issue
     * @param  string  $code  开奖号码
     * @return void
     */
    public static function encode($lotteryId, $issue, $code): void
    {
        $lotteryIssueEloq = self::where([
            ['lottery_id', $lotteryId],
            ['issue', $issue],
            ['status_encode', self::ENCODE_NONE],
        ])->first();
        if ($lotteryIssueEloq !== null) {
            $lotteryIssueEloq->recordEncodeNumber($code);
        }
    }
}
