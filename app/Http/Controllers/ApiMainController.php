<?php

namespace App\Http\Controllers;

use App\models\PartnerAdminRoute;
use App\models\PartnerMenus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class ApiMainController extends Controller
{
    protected $inputs;
    protected $partnerAdmin;//当前的商户用户
    protected $currentOptRoute;//目前路由
    protected $fullMenuLists;//所有的菜单
    protected $currentPlatformEloq = null;//当前商户存在的平台
    protected $currentPartnerAccessGroup = null;//当前商户的权限组
    protected $partnerMenulists; //目前所有的菜单为前端展示用的
    protected $eloqM = '';// 当前的eloquent
    protected $currentRouteName;//当前的route name;
    protected $routeAccessable = false;

    /**
     * AdminMainController constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->partnerAdmin = Auth::guard('api')->user();
            //登录注册的时候是没办法获取到当前用户的相关信息所以需要过滤
            try {
                $this->currentPlatformEloq = $this->partnerAdmin->platform;//获取目前账号用户属于平台的对象
                $this->currentPartnerAccessGroup = $this->partnerAdmin->accessGroup;
            } catch (\Exception $e) {
            }
            $this->inputs = Input::all(); //获取所有相关的传参数据
            //登录注册的时候是没办法获取到当前用户的相关信息所以需要过滤
            if (!is_null($this->currentPlatformEloq)) {
                $this->menuAccess();
                $this->routeAccessCheck();
                if ($this->routeAccessable === false) {
                    return $this->msgout($this->routeAccessable, [],'您没有访问权限','404');
                }
            }
            $this->adminOperateLog();
            $this->eloqM = 'App\\models\\' . $this->eloqM;// 当前的eloquent
            return $next($request);
        });
    }

    /**
     *　初始化所有菜单，目前商户该拥有的菜单与权限
     */
    private function menuAccess()
    {
        $partnerEloq = new PartnerMenus();
        $this->fullMenuLists = $partnerEloq->forStar();//所有的菜单
        $this->partnerMenulists = $partnerEloq->menuLists($this->currentPartnerAccessGroup);//目前所有的菜单为前端展示用的
    }

    /**
     *　检测目前的路由是否有权限访问
     */
    private function routeAccessCheck(): void
    {
        $this->currentOptRoute = Route::getCurrentRoute();
        $this->currentRouteName = $this->currentOptRoute->action['as']; //当前的route name;
        //$partnerAdREloq = PartnerAdminRoute::where('route_name',$this->currentRouteName)->first()->parentRoute->menu;
        $partnerAdREloq = PartnerAdminRoute::where('route_name', $this->currentRouteName)->first();
        if (!is_null($partnerAdREloq)) {
            $partnerMenuEloq = $partnerAdREloq->menu;
            //set if it is accissable or not
            if (!empty($this->currentPartnerAccessGroup->role)) {
                if ($this->currentPartnerAccessGroup->role == '*') {
                    $this->routeAccessable = true;
                } else {
                    $currentRouteGroup = json_decode($this->currentPartnerAccessGroup->role, true);
                    if (in_array($partnerMenuEloq->id, $currentRouteGroup)) {
                        $this->routeAccessable = true;
                    }
                }
            }
        }

    }

    /**
     *记录后台管理员操作日志
     */
    private function adminOperateLog(): void
    {
        $datas['input'] = $this->inputs;
        $datas['route'] = $this->currentOptRoute;
        $log = json_encode($datas, JSON_UNESCAPED_UNICODE);
        Log::channel('apibyqueue')->info($log);
    }

    /**
     * @param bool $success
     * @param array $data
     * @param string $message
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function msgout($success = false, $data = [], $message = '', $code = '')
    {
        if ($success === true) {
            $message = $message == '' ? '执行成功' : $message;
            $code = $code == '' ? '200' : $code;
        }
        $datas = [
            'success' => $success,
            'code' => $code,
            'data' => $data,
            'message' => $message,
        ];
        return response()->json($datas);
    }
}
