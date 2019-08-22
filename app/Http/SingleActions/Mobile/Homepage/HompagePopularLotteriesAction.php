<?php

namespace App\Http\SingleActions\Mobile\Homepage;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Lib\Common\CacheRelated;
use App\Models\Admin\Homepage\FrontendLotteryRedirectBetList;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class HompagePopularLotteriesAction
{
    /**
     * 热门彩票一
     * @param  FrontendApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        $tags = $contll->tags;
        $redisKey = 'mobile_popular_lotteries';
        $data = CacheRelated::getTagsCache($tags, $redisKey);
        if ($data === false) {
            $data = FrontendLotteryRedirectBetList::mobilePopularLotteriesCache();
        }
        return $contll->msgOut(true, $data);
    }
}
