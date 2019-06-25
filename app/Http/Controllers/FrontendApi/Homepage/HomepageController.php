<?php

namespace App\Http\Controllers\FrontendApi\Homepage;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Http\SingleActions\Frontend\Homepage\HomepageShowHomepageModelAction;
use App\Http\SingleActions\Frontend\Homepage\HompageActivityAction;
use App\Http\SingleActions\Frontend\Homepage\HompageBannerAction;
use App\Http\SingleActions\Frontend\Homepage\HompageIcoAction;
use App\Http\SingleActions\Frontend\Homepage\HompageLogoAction;
use App\Http\SingleActions\Frontend\Homepage\HompageNoticeAction;
use App\Http\SingleActions\Frontend\Homepage\HompagePopularLotteriesAction;
use App\Http\SingleActions\Frontend\Homepage\HompagePopularMethodsAction;
use App\Http\SingleActions\Frontend\Homepage\HompageQrCodeAction;
use Illuminate\Http\JsonResponse;

class HomepageController extends FrontendApiMainController
{
    public $eloqM = 'DeveloperUsage\Frontend\FrontendAllocatedModel';
    public $offMsg = '当前模块为关闭状态';

    /**
     * 需要展示的前台模块
     * @param  HomepageShowHomepageModelAction $action
     * @return JsonResponse
     */
    public function showHomepageModel(HomepageShowHomepageModelAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 首页轮播图列表
     * @param  HompageBannerAction  $action
     * @return JsonResponse
     */
    public function banner(HompageBannerAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 热门彩票一
     * @param  HompagePopularLotteriesAction $action
     * @return JsonResponse
     */
    public function popularLotteries(HompagePopularLotteriesAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 热门彩票二-玩法
     * @param  HompagePopularMethodsAction $action
     * @return JsonResponse
     */
    public function popularMethods(HompagePopularMethodsAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 首页二维码
     * @param  HompageQrCodeAction $action
     * @return JsonResponse
     */
    public function qrCode(HompageQrCodeAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 首页活动列表
     * @param  HompageActivityAction $action
     * @return JsonResponse
     */
    public function activity(HompageActivityAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 首页LOGO
     * @param  HompageLogoAction $action
     * @return JsonResponse
     */
    public function logo(HompageLogoAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 首页公告列表
     * @param  HompageNoticeAction $action
     * @return JsonResponse
     */
    public function notice(HompageNoticeAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 前台网站头ico
     * @param  HompageIcoAction $action
     * @return JsonResponse
     */
    public function ico(HompageIcoAction $action): JsonResponse
    {
        return $action->execute($this);
    }
}
