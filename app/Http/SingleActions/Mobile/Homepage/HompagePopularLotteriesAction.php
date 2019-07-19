<?php

namespace App\Http\SingleActions\Mobile\Homepage;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
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
        if (Cache::has('mobile_popular_lotteries')) {
            $datas = Cache::get('mobile_popular_lotteries');
        } else {
            $datas = FrontendLotteryRedirectBetList::mobilePopularLotteriesCache();
        }
        return $contll->msgOut(true, $datas);
    }
}
