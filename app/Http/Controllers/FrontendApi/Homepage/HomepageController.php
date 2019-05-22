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
        $showNum = $this->eloqM::select('show_num')->where('key', 'popularLotteries.one')->first();
        $dataEloq = PopularLotteries::select('id', 'lotteries_id', 'pic_path')->with(['lotteries' => function ($query) {
            $query->select('id', 'day_issue');
        }])->where('type', 1)->limit($showNum->show_num)->get();
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
        $showNum = $this->eloqM::select('show_num')->where('key', 'popularLotteries.two')->first();
        $dataEloq = PopularLotteries::select('id', 'lotteries_id')->with(['lotteries' => function ($query) {
            $query->select('id', 'cn_name');
        }])->where('type', 2)->limit($showNum->show_num)->get();
        $datas = [];
        foreach ($dataEloq as $value) {
            $datas[$value->lotteries->id] = $value->lotteries->cn_name;
        }
        return $this->msgOut(true, $datas);
    }

    //二维码
    public function qrCode()
    {
        $qrCode = $this->eloqM::select('value')->where('key', 'qr.code')->get()->toArray();
        return $this->msgOut(true, $qrCode);
    }

    //活动
    public function activity()
    {
        $showNum = $this->eloqM::select('show_num')->where('key', 'activity')->first();
        $data = ActivityInfos::select('id', 'title', 'content', 'thumbnail_path', 'redirect_url')->where('status', 1)->orderBy('sort', 'asc')->limit($showNum->show_num)->get()->toArray();
        return $this->msgOut(true, $data);
    }

    //LOGO
    public function logo()
    {
        $logo = $this->eloqM::select('value')->where('key', 'logo')->get()->toArray();
        return $this->msgOut(true, $logo);
    }
}
