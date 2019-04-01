<?php

namespace App\Http\Controllers;

use App\models\PartnerMenus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class ApiMainController extends Controller
{
    protected $inputs;
    protected $partnerAdmin;//当前的商户用户
    protected $currentOptRoute;//目前路由
    protected $fullMenuLists;//所有的菜单
    protected $currentPlatformEloq;//当前商户存在的平台
    protected $currentPartnerAccessGroup;//当前商户的权限组
    protected $partnerMenulists; //目前所有的菜单为前端展示用的
    protected $eloqM='';// 当前的eloquent

    /**
     * AdminMainController constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->partnerAdmin = Auth::guard('api')->user();
            $this->currentPlatformEloq = $this->partnerAdmin->platform; //获取目前账号用户属于平台的对象
            $this->currentPartnerAccessGroup = $this->partnerAdmin->accessGroup;
            $this->inputs = Input::all(); //获取所有相关的传参数据
            $this->currentOptRoute = Route::getCurrentRoute();
            $this->adminOperateLog();
            $partnerEloq = new PartnerMenus();
            $this->fullMenuLists = $partnerEloq->forStar();//所有的菜单
            $this->partnerMenulists = $partnerEloq->menuLists($this->currentPartnerAccessGroup);//目前所有的菜单为前端展示用的
            $this->eloqM = 'App\\models\\' . $this->eloqM;// 当前的eloquent
            return $next($request);
        });
    }

    /**
     *记录后台管理员操作日志
     */
    private function adminOperateLog(): void
    {
        $datas['input'] = $this->inputs;
        $datas['route'] = $this->currentOptRoute;
        $log = json_encode($datas,JSON_UNESCAPED_UNICODE);
        Log::channel('apibyqueue')->info($log);
    }
}
