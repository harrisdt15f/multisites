<?php

namespace App\Models\Admin\Homepage\Logics;

use App\Lib\Common\CacheRelated;
use App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel;
use App\Models\Game\Lottery\LotteryIssue;
use App\Models\Game\Lottery\LotteryList;

trait FrontendLotteryNoticeListTraits
{
    //获取pc端开奖公告列表
    public static function getWebLotteryNoticeList()
    {
        $lotteryNoticeEloq = FrontendAllocatedModel::select('status', 'show_num')
            ->where('en_name', 'lottery.notice')
            ->first();
        if ($lotteryNoticeEloq === null) {
            $lotteryNoticeEloq = FrontendAllocatedModel::createMobileLotteryNotice();
        }
        if ($lotteryNoticeEloq->status === 1) {
            return self::getLotteryNoticeList($lotteryNoticeEloq->show_num);
        }
    }

    //获取手机端开奖公告列表
    public static function getMobileLotteryNoticeList()
    {
        $lotteryNoticeEloq = FrontendAllocatedModel::select('status', 'show_num')
            ->where('en_name', 'mobile.lottery.notice')
            ->first();
        if ($lotteryNoticeEloq === null) {
            $lotteryNoticeEloq = FrontendAllocatedModel::createMobileLotteryNotice();
        }
        if ($lotteryNoticeEloq->status === 1) {
            return self::getLotteryNoticeList($lotteryNoticeEloq->show_num);
        }
    }

    public static function getLotteryNoticeList($count)
    {
        $lotterys = self::where('status', 1)
            ->orderBy('sort', 'asc')
            ->limit($count)
            ->pluck('lotteries_id');
        $tags = 'lottery';
        $redisKey = 'lottery_notice';
        $lotteryNoticelist = CacheRelated::getTagsCache($tags, $redisKey);
        $data = [];
        foreach ($lotterys as $sign) {
            if (isset($lotteryNoticelist[$sign])) {
                $data[$sign] = $lotteryNoticelist[$sign];
            }
        }
        return $data;
    }

    //更新开奖公告的缓存
    public static function updateLotteryNotice($issue)
    {
        $tags = 'lottery';
        $redisKey = 'lottery_notice';
        $lotteryNoticelist = CacheRelated::getTagsCache($tags, $redisKey);
        if (!isset($lotteryNoticelist[$issue->lottery_id])) {
            $lottery = LotteryList::where('en_name', $issue->lottery_id)->first();
            if ($lottery !== null) {
                $lotteryNoticelist[$issue->lottery_id]['series'] = $lottery->series_id;
                $lotteryNoticelist[$issue->lottery_id]['cn_name'] = $lottery->cn_name;
                $lotteryNoticelist[$issue->lottery_id]['lotteries_id'] = $issue->lottery_id;
                $lotteryNoticelist[$issue->lottery_id]['icon'] = $lottery->icon_path;
            }
        }
        $lotteryNoticelist[$issue->lottery_id]['issue'] = $issue->issue;
        $lotteryNoticelist[$issue->lottery_id]['official_code'] = $issue->official_code;
        $lotteryNoticelist[$issue->lottery_id]['encode_time'] = $issue->encode_time;
        CacheRelated::setTagsCache($tags, $redisKey, $lotteryNoticelist);
        return $lotteryNoticelist[$issue->lottery_id];
    }
}
