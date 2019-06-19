<?php

namespace App\Http\Controllers\FrontendApi\Homepage;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Http\SingleActions\HompageBannerAction;
use App\Models\Admin\Activity\FrontendActivityContent;
use App\Models\Admin\Homepage\FrontendLotteryFnfBetableList;
use App\Models\Admin\Homepage\FrontendLotteryRedirectBetList;
use App\Models\Admin\Notice\FrontendMessageNotice;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class HomepageController extends FrontendApiMainController
{
    public $eloqM = 'DeveloperUsage\Frontend\FrontendAllocatedModel';
    public $offMsg = '当前模块为关闭状态';

    //需要展示的前台模块
    public function showHomepageModel()
    {
        if (Cache::has('showModel')) {
            $data = Cache::get('showModel');
        } else {
            $homepageModel = $this->eloqM::select('en_name', 'status')->where('is_homepage_display', 1)->get();
            $data = [];
            foreach ($homepageModel as $value) {
                $data[$value->en_name] = $value->status;
            }
            Cache::forever('showModel', $data);
        }
        return $this->msgOut(true, $data);
    }


    /**
     * 轮播图
     * @param  HompageBannerAction  $action
     * @return JsonResponse
     */
    public function banner(HompageBannerAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    //热门彩票
    public function popularLotteries()
    {
        if (Cache::has('popularLotteries')) {
            $datas = Cache::get('popularLotteries');
        } else {
            $lotteriesEloq = $this->eloqM::select('show_num', 'status')->where('en_name', 'popularLotteries.one')->first();
            if ($lotteriesEloq->status !== 1) {
                return $this->msgOut(false, [], '400', $this->offMsg);
            }
            $dataEloq = FrontendLotteryRedirectBetList::select('id', 'lotteries_id', 'pic_path')->with(['lotteries' => function ($query) {
                $query->select('id', 'day_issue', 'en_name');
            }])->orderBy('sort', 'asc')->limit($lotteriesEloq->show_num)->get();
            $datas = [];
            foreach ($dataEloq as $key => $dataIthem) {
                $datas[$key]['en_name'] = $dataIthem->lotteries->en_name;
                $datas[$key]['pic_path'] = $dataIthem->pic_path;
                $datas[$key]['day_issue'] = $dataIthem->lotteries->day_issue;
            }
            Cache::forever('popularLotteries', $datas);
        }
        return $this->msgOut(true, $datas);
    }

    //热门玩法
    public function popularMethods()
    {
        if (Cache::has('popularMethods')) {
            $datas = Cache::get('popularMethods');
        } else {
            $lotteriesEloq = $this->eloqM::select('show_num', 'status')->where('en_name', 'popularLotteries.two')->first();
            if ($lotteriesEloq->status !== 1) {
                return $this->msgOut(false, [], '400', $this->offMsg);
            }
            $methodsEloq = FrontendLotteryFnfBetableList::orderBy('sort', 'asc')->limit($lotteriesEloq->show_num)->with('method')->get();
            $datas = [];
            foreach ($methodsEloq as $method) {
                $data = [
                    'method_id' => $method->method_id,
                    'lottery_name' => $method->method->lottery_name,
                    'method_name' => $method->method->method_name,
                ];
                $datas[] = $data;
            }
            Cache::forever('popularMethods', $datas);
        }
        return $this->msgOut(true, $datas);
    }

    //二维码
    public function qrCode()
    {
        if (Cache::has('homepageQrCode')) {
            $data = Cache::get('homepageQrCode');
        } else {
            $data = $this->eloqM::select('value', 'status')->where('en_name', 'qr.code')->first()->toArray();
            if ($data['status'] !== 1) {
                return $this->msgOut(false, [], '400', $this->offMsg);
            }
            unset($data['status']);
            Cache::forever('homepageQrCode', $data);
        }
        return $this->msgOut(true, $data);
    }

    //活动
    public function activity()
    {
        if (Cache::has('homepageActivity')) {
            $data = Cache::get('homepageActivity');
        } else {
            $activityEloq = $this->eloqM::select('show_num', 'status')->where('en_name', 'activity')->first();
            if ($activityEloq->status !== 1) {
                return $this->msgOut(false, [], '400', $this->offMsg);
            }
            $data = FrontendActivityContent::select('id', 'title', 'content', 'thumbnail_path', 'redirect_url')->where('status', 1)->orderBy('sort', 'asc')->limit($activityEloq->show_num)->get()->toArray();
            Cache::forever('homepageActivity', $data);
        }
        return $this->msgOut(true, $data);
    }

    //LOGO
    public function logo()
    {
        if (Cache::has('homepageLogo')) {
            $data = Cache::get('homepageLogo');
        } else {
            $data = $this->eloqM::select('value', 'status')->where('en_name', 'logo')->first()->toArray();
            if ($data['status'] !== 1) {
                return $this->msgOut(false, [], '400', $this->offMsg);
            }
            unset($data['status']);
            Cache::forever('homepageLogo', $data);
        }
        return $this->msgOut(true, $data);
    }

    //公告
    public function notice()
    {
        if (Cache::has('homepageNotice')) {
            $datas = Cache::get('homepageNotice');
        } else {
            $noticeEloq = $this->eloqM::select('show_num', 'status')->where('en_name', 'notice')->first();
            if ($noticeEloq->status !== 1) {
                return $this->msgOut(false, [], '400', $this->offMsg);
            }
            $datas = FrontendMessageNotice::select('id', 'title')->where('status', 1)->orderBy('sort', 'asc')->limit($noticeEloq->show_num)->get();
            Cache::forever('homepageNotice', $datas);
        }
        return $this->msgOut(true, $datas);
    }

    //前台网站头ico
    public function ico()
    {
        if (Cache::has('homepageIco')) {
            $data = Cache::get('homepageIco');
        } else {
            $icoEloq = $this->eloqM::select('value', 'status')->where('en_name', 'frontend.ico')->first();
            if ($icoEloq->status !== 1) {
                return $this->msgOut(false, [], '400', $this->offMsg);
            };
            $data = $icoEloq->value;
            Cache::forever('homepageIco', $data);
        }
        return $this->msgOut(true, $data);
    }
}
