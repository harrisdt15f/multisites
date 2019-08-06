<?php

namespace App\Http\Controllers\FrontendApi\Homepage;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Http\Requests\Frontend\Homepage\HomepageNoticeRequest;
use App\Http\Requests\Frontend\Homepage\HomepageReadMessageRequest;
use App\Http\SingleActions\Frontend\Homepage\HomepageNoticeAction;
use App\Http\SingleActions\Frontend\Homepage\HomepagePopularChessCardsListsAction;
use App\Http\SingleActions\Frontend\Homepage\HomepagePopularEGameListsAction;
use App\Http\SingleActions\Frontend\Homepage\HomepageRankingAction;
use App\Http\SingleActions\Frontend\Homepage\HomepageReadMessageAction;
use App\Http\SingleActions\Frontend\Homepage\HomepageShowHomepageModelAction;
use App\Http\SingleActions\Frontend\Homepage\HompageActivityAction;
use App\Http\SingleActions\Frontend\Homepage\HompageBannerAction;
use App\Http\SingleActions\Frontend\Homepage\HompageIcoAction;
use App\Http\SingleActions\Frontend\Homepage\HompageLogoAction;
use App\Http\SingleActions\Frontend\Homepage\HompageLotteryNoticeListAction;
use App\Http\SingleActions\Frontend\Homepage\HompagePopularLotteriesAction;
use App\Http\SingleActions\Frontend\Homepage\HompagePopularMethodsAction;
use App\Http\SingleActions\Frontend\Homepage\HompageQrCodeAction;
use Illuminate\Http\JsonResponse;
use App\Http\SingleActions\Frontend\Homepage\HomepageActivityListAction;


class HomepageController extends FrontendApiMainController
{
    private $bannerFlag = 1;//网页端banner
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
        return $action->execute($this,$this->bannerFlag);
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
     * 热门活动
     * @param  HompageActivityAction $action
     * @return JsonResponse
     */
    public function activity(HompageActivityAction $action): JsonResponse
    {
        return $action->execute($this,1);
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
     * 公告|站内信 列表
     * @param  HomepageNoticeRequest  $request
     * @param  HomepageNoticeAction   $action
     * @return JsonResponse
     */
    public function notice(HomepageNoticeRequest $request, HomepageNoticeAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 站内信 已读处理
     * @param  HomepageReadMessageRequest $request
     * @param  HomepageReadMessageAction  $action
     * @return JsonResponse
     */
    public function readMessage(HomepageReadMessageRequest $request, HomepageReadMessageAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
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

    /**
     * 首页中奖排行榜
     * @param  HomepageRankingAction $action
     * @return JsonResponse
     */
    public function ranking(HomepageRankingAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 开奖公告列表
     * @param  HompageLotteryNoticeListAction $action
     * @return JsonResponse
     */
    public function lotteryNoticeList(HompageLotteryNoticeListAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 热门棋牌
     * @param  HomepagePopularChessCardsListsAction $action
     * @return JsonResponse
     */
    public function popularChessCardsLists(HomepagePopularChessCardsListsAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 热门电子
     * @param  HomepagePopularEGameListsAction $action
     * @return JsonResponse
     */
    public function popularEGameLists(HomepagePopularEGameListsAction $action): JsonResponse
    {
        return $action->execute($this);
    }
    /**
     * 活动列表
     * @return JsonResponse
     */
    public function activityList(HomepageActivityListAction $action): JsonResponse
    {
        $inputDatas['type'] = '1';
        return $action->execute($this,$inputDatas);
    }

}
