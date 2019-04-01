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

    /**
     * AdminMainController constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('api')->user();
            $this->inputs = Input::all();
            $this->currentOptRoute = Route::getCurrentRoute();
            $this->adminOperateLog();
            $partnerEloq = new PartnerMenus();
            $this->fullMenuLists = $partnerEloq->forStar();
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
