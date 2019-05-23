<?php

namespace App\Http\Controllers\FrontendApi\Homepage;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\models\ActivityInfos;
use App\models\HomepageRotationChart;
use App\models\PopularLotteries;
use Illuminate\Support\Facades\Validator;

class HomepageController extends FrontendApiMainController
{
    protected $eloqM = 'HomepageModel';
    protected $offMsg = '当前模块为关闭状态';

    //需要展示的前台模块
    public function showHomepageModel()
    {
        $HomepageModel = $this->eloqM::select('key', 'status')->where('pid', '!=', 0)->get();
        $data = [];
        foreach ($HomepageModel as $value) {
            $data[$value->key] = $value->status;
        }
        return $this->msgOut(true, $data);
    }

    //轮播图
    public function banner()
    {
        $status = $this->eloqM::select('status')->where('key', 'banner')->first();
        if ($status->status !== 1) {
            return $this->msgOut(false, [], '400', $this->offMsg);
        }
        $data = HomepageRotationChart::select('id', 'title', 'pic_path', 'content', 'type', 'redirect_url', 'activity_id')
            ->with(['activity' => function ($query) {
                $query->select('id', 'redirect_url');
            }])
            ->where('status', 1)->get()->toArray();
        return $this->msgOut(true, $data);
    }

    //热门彩种一
    public function popularLotteriesOne()
    {
        $lotteriesEloq = $this->eloqM::select('show_num', 'status')->where('key', 'popularLotteries.one')->first();
        if ($lotteriesEloq->status !== 1) {
            return $this->msgOut(false, [], '400', $this->offMsg);
        }
        $dataEloq = PopularLotteries::select('id', 'lotteries_id', 'pic_path')->with(['lotteries' => function ($query) {
            $query->select('id', 'day_issue');
        }])->where('type', 1)->limit($lotteriesEloq->show_num)->get();
        $datas = [];
        foreach ($dataEloq as $key => $dataIthem) {
            $datas[$key]['pic_path'] = $dataIthem->pic_path;
            $datas[$key]['day_issue'] = $dataIthem->lotteries->day_issue;
        }
        return $this->msgOut(true, $datas);
    }

    //热门彩种二
    public function popularLotteriesTwo()
    {
        $lotteriesEloq = $this->eloqM::select('show_num', 'status')->where('key', 'popularLotteries.two')->first();
        if ($lotteriesEloq->status !== 1) {
            return $this->msgOut(false, [], '400', $this->offMsg);
        }
        $dataEloq = PopularLotteries::select('id', 'lotteries_id')->with(['lotteries' => function ($query) {
            $query->select('id', 'cn_name');
        }])->where('type', 2)->limit($lotteriesEloq->show_num)->get();
        $datas = [];
        foreach ($dataEloq as $dataIthem) {
            $datas[$dataIthem->lotteries->id] = $dataIthem->lotteries->cn_name;
        }
        return $this->msgOut(true, $datas);
    }

    //二维码
    public function qrCode()
    {
        $qrCodeEloq = $this->eloqM::select('value', 'status')->where('key', 'qr.code')->first()->toArray();
        if ($qrCodeEloq['status'] !== 1) {
            return $this->msgOut(false, [], '400', $this->offMsg);
        }
        unset($qrCodeEloq['status']);
        return $this->msgOut(true, $qrCodeEloq);
    }

    //活动
    public function activity()
    {
        $activityEloq = $this->eloqM::select('show_num', 'status')->where('key', 'activity')->first();
        if ($activityEloq['status'] !== 1) {
            return $this->msgOut(false, [], '400', $this->offMsg);
        }
        $data = ActivityInfos::select('id', 'title', 'content', 'thumbnail_path', 'redirect_url')->where('status', 1)->orderBy('sort', 'asc')->limit($activityEloq->show_num)->get()->toArray();
        return $this->msgOut(true, $data);
    }

    //LOGO
    public function logo()
    {
        $logoEloq = $this->eloqM::select('value', 'status')->where('key', 'logo')->first()->toArray();
        if ($logoEloq['status'] !== 1) {
            return $this->msgOut(false, [], '400', $this->offMsg);
        }
        unset($logoEloq['status']);
        return $this->msgOut(true, $logoEloq);
    }
}
