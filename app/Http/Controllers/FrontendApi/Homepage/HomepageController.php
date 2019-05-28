<?php

namespace App\Http\Controllers\FrontendApi\Homepage;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\ActivityInfos;
use App\Models\HomepageRotationChart;
use App\Models\Notice;
use App\Models\PopularLotteries;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class HomepageController extends FrontendApiMainController
{
    protected $eloqM = 'HomepageModel';
    protected $offMsg = '当前模块为关闭状态';

    //需要展示的前台模块
    public function showHomepageModel()
    {
        if (Cache::has('showModel')) {
            $data = Cache::get('showModel');
        } else {
            $HomepageModel = $this->eloqM::select('key', 'status')->where('pid', '!=', 0)->orWhere('key', '=', 'banner')->get();
            $data = [];
            foreach ($HomepageModel as $value) {
                $data[$value->key] = $value->status;
            }
            Cache::forever('showModel', $data);
        }
        return $this->msgOut(true, $data);
    }

    //轮播图
    public function banner()
    {
        if (Cache::has('homepageBanner')) {
            $datas = Cache::get('homepageBanner');
        } else {
            $status = $this->eloqM::select('status')->where('key', 'banner')->first();
            if ($status->status !== 1) {
                return $this->msgOut(false, [], '400', $this->offMsg);
            }
            $datas = HomepageRotationChart::select('id', 'title', 'pic_path', 'content', 'type', 'redirect_url', 'activity_id')
                ->with(['activity' => function ($query) {
                    $query->select('id', 'redirect_url');
                }])
                ->where('status', 1)->get()->toArray();
            foreach ($datas as $key => $data) {
                if ($data['type'] === 2) {
                    $datas[$key]['redirect_url'] = $data['activity']['redirect_url'];
                }
                unset($datas[$key]['activity']);
                unset($datas[$key]['activity_id']);
            }
            Cache::forever('homepageBanner', $datas);
        }
        return $this->msgOut(true, $datas);
    }

    //热门彩种一
    public function popularLotteriesOne()
    {
        if (Cache::has('popularLotteriesOne')) {
            $datas = Cache::get('popularLotteriesOne');
        } else {
            $lotteriesEloq = $this->eloqM::select('show_num', 'status')->where('key', 'popularLotteries.one')->first();
            if ($lotteriesEloq->status !== 1) {
                return $this->msgOut(false, [], '400', $this->offMsg);
            }
            $dataEloq = PopularLotteries::select('id', 'lotteries_id', 'pic_path')->with(['lotteries' => function ($query) {
                $query->select('id', 'day_issue', 'en_name');
            }])->where('type', 1)->limit($lotteriesEloq->show_num)->get();
            $datas = [];
            foreach ($dataEloq as $key => $dataIthem) {
                $datas[$key]['en_name'] = $dataIthem->lotteries->en_name;
                $datas[$key]['pic_path'] = $dataIthem->pic_path;
                $datas[$key]['day_issue'] = $dataIthem->lotteries->day_issue;
            }
            Cache::forever('popularLotteriesOne', $datas);
        }
        return $this->msgOut(true, $datas);
    }

    //热门彩种二
    public function popularLotteriesTwo()
    {
        if (Cache::has('popularLotteriesTwo')) {
            $datas = Cache::get('popularLotteriesTwo');
        } else {
            $lotteriesEloq = $this->eloqM::select('show_num', 'status')->where('key', 'popularLotteries.two')->first();
            if ($lotteriesEloq->status !== 1) {
                return $this->msgOut(false, [], '400', $this->offMsg);
            }
            $dataEloq = PopularLotteries::select('id', 'lotteries_id')->with(['lotteries' => function ($query) {
                $query->select('id', 'cn_name', 'en_name');
            }])->where('type', 2)->limit($lotteriesEloq->show_num)->get();
            $datas = [];
            foreach ($dataEloq as $dataIthem) {
                $datas[$dataIthem->lotteries->en_name] = $dataIthem->lotteries->cn_name;
            }
            Cache::forever('popularLotteriesTwo', $datas);
        }
        return $this->msgOut(true, $datas);
    }

    //二维码
    public function qrCode()
    {
        if (Cache::has('homepageQrCode')) {
            $data = Cache::get('homepageQrCode');
        } else {
            $data = $this->eloqM::select('value', 'status')->where('key', 'qr.code')->first()->toArray();
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
            $activityEloq = $this->eloqM::select('show_num', 'status')->where('key', 'activity')->first();
            if ($activityEloq->status !== 1) {
                return $this->msgOut(false, [], '400', $this->offMsg);
            }
            $data = ActivityInfos::select('id', 'title', 'content', 'thumbnail_path', 'redirect_url')->where('status', 1)->orderBy('sort', 'asc')->limit($activityEloq->show_num)->get()->toArray();
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
            $data = $this->eloqM::select('value', 'status')->where('key', 'logo')->first()->toArray();
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
            echo 1;
            $datas = Cache::get('homepageNotice');
        } else {
            echo 2;
            $noticeEloq = $this->eloqM::select('show_num', 'status')->where('key', 'notice')->first();
            if ($noticeEloq->status !== 1) {
                return $this->msgOut(false, [], '400', $this->offMsg);
            }
            $datas = Notice::select('id', 'title')->where('status', 1)->orderBy('sort', 'asc')->limit($noticeEloq->show_num)->get();
            Cache::forever('homepageNotice', $datas);
        }
        return $this->msgOut(true, $datas);
    }
}
