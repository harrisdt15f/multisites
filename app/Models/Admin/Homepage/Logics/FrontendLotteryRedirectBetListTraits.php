<?php

namespace App\Models\Admin\Homepage\Logics;

use App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

trait FrontendLotteryRedirectBetListTraits
{
    /**
     * 更新首页热门彩票缓存
     */
    public static function updatePopularLotteriesCache(): void
    {
        self::webPopularLotteriesCache();
        self::mobilePopularLotteriesCache();
    }

    /**
     * 更新 web端热门彩种缓存
     * @return array
     */
    public static function webPopularLotteriesCache(): array
    {
        $cacheKey = 'popular_lotteries';
        $lotteriesEloq = FrontendAllocatedModel::select('show_num', 'status')->where('en_name', 'popularLotteries.one')->first();
        if ($lotteriesEloq === null) {
            $lotteriesEloq = FrontendAllocatedModel::createPopularLotteries();
        }
        return self::updateCache($cacheKey, $lotteriesEloq->show_num);
    }

    /**
     * 更新 app端热门彩种缓存
     * @return array
     */
    public static function mobilePopularLotteriesCache(): array
    {
        $cacheKey = 'mobile_popular_lotteries';
        $lotteriesEloq = FrontendAllocatedModel::select('show_num', 'status')->where('en_name', 'mobile.popular.lotteries.one')->first();
        if ($lotteriesEloq === null) {
            $lotteriesEloq = FrontendAllocatedModel::createMobilePopularLotteries();
        }
        return self::updateCache($cacheKey, $lotteriesEloq->show_num);
    }

    /**
     * @param  $cacheKey
     * @param  $showNum
     * @return array
     */
    public static function updateCache($cacheKey, $showNum): array
    {
        $dataEloq = self::select('id', 'lotteries_id', 'lotteries_sign', 'pic_path')->with(['lotteries:id,day_issue,en_name,cn_name', 'issueRule:lottery_id,issue_seconds'])->orderBy('sort', 'asc')->limit($showNum)->get();
        $datas = [];
        foreach ($dataEloq as $key => $dataIthem) {
            $datas[$key]['cn_name'] = $dataIthem->lotteries->cn_name ?? null;
            $datas[$key]['en_name'] = $dataIthem->lotteries->en_name ?? null;
            $datas[$key]['pic_path'] = $dataIthem->pic_path ?? null;
            $datas[$key]['issue_seconds'] = $dataIthem->issueRule->issue_seconds ?? null;
            $datas[$key]['day_issue'] = $dataIthem->lotteries->day_issue ?? null;
        }
        $expiresAt = Carbon::now()->addHours(24);
        $aa = Cache::put($cacheKey, $datas, $expiresAt);
        return $datas;
    }
}
