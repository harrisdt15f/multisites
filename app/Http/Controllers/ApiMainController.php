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
    protected $user;
    protected $currentOptRoute;
    protected $fullMenuLists;
    protected $currentPlatformEloq;
    protected $eloqM='';

    /**
     * AdminMainController constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('api')->user();
            $this->currentPlatformEloq = $this->user->platform; //获取目前账号用户属于平台的对象
            $this->inputs = Input::all(); //获取所有相关的传参数据
            $this->currentOptRoute = Route::getCurrentRoute();
            $this->adminOperateLog();
            $partnerEloq = new PartnerMenus();
            $this->fullMenuLists = $partnerEloq->forStar();
            $this->eloqM = 'App\\models\\' . $this->eloqM;
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
