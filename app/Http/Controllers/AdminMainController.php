<?php

namespace App\Http\Controllers;

use App\Menus;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class AdminMainController extends Controller
{
    protected $inputs;
    protected $user;
    protected $currentOptRoute;

    /**
     * AdminMainController constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            $this->inputs = Input::all();
            $this->currentOptRoute = Route::getCurrentRoute();
            $this->adminOperateLog();
            $menuObj = new Menus();
            $menulists = $menuObj->menuLists();
            View::share('menulists', $menulists);
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
        $datas['user'] = $this->user;
        $datas['ip'] = Request::ip();
        $datas['ips'] = Request::ips();
        $log = json_encode($datas,JSON_UNESCAPED_UNICODE);
//        Log::channel('operate')->debug($log);
        Log::stack(['operate','graylog'])->debug($log);
    }
}
