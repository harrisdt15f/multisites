<?php

namespace App\Http\Controllers\BackendApi;

use App\Http\Controllers\Controller;
use App\Models\Admin\BackendAdminAccessGroup;
use App\Models\DeveloperUsage\Backend\BackendAdminRoute;
use App\Models\DeveloperUsage\Menu\BackendSystemMenu;
use App\Models\SystemPlatform;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

class BackEndApiMainController extends Controller
{
    public $inputs;
    public $partnerAdmin; //当前的商户用户
    protected $currentOptRoute; //目前路由
    protected $fullMenuLists; //所有的菜单
    public $currentPlatformEloq; //当前商户存在的平台
    public $currentPartnerAccessGroup; //当前商户的权限组
    protected $partnerMenulists; //目前所有的菜单为前端展示用的
    protected $eloqM = ''; // 当前的eloquent
    protected $currentRouteName; //当前的route name;
    protected $routeAccessable = false;
    public $log_uuid; //当前的logId
    protected $currentGuard = 'backend';
    public $currentAuth;
    public $minClassicPrizeGroup; //平台最低投注奖金组
    public $maxClassicPrizeGroup; //平台最高投注奖金组
    /**
     * @var Agent
     */
    public $userAgent;

    /**
     * AdminMainController constructor.
     */
    public function __construct()
    {
        $this->handleEndUser();
        $this->middleware(function ($request, $next) {
            $this->currentAuth = auth($this->currentGuard);
            $this->partnerAdmin = $this->currentAuth->user();
            if ($this->partnerAdmin !== null) {
                //登录注册的时候是没办法获取到当前用户的相关信息所以需要过滤
                $this->currentPartnerAccessGroup = new BackendAdminAccessGroup();
                $this->currentPlatformEloq = new SystemPlatform();
                if ($this->partnerAdmin->platform()->exists()) {
                    $this->currentPlatformEloq = $this->partnerAdmin->platform; //获取目前账号用户属于平台的对象
                    if ($this->partnerAdmin->accessGroup()->exists()) {
                        $this->currentPartnerAccessGroup = $this->partnerAdmin->accessGroup;
                    }
                    $this->menuAccess();
                    $this->routeAccessCheck();
                    if ($this->routeAccessable === false) {
                        return $this->msgOut($this->routeAccessable, [], '100001');
                    }
                }
            }
            $this->inputs = Input::all(); //获取所有相关的传参数据
            //登录注册的时候是没办法获取到当前用户的相关信息所以需要过滤
            $this->adminOperateLog();
            $this->minClassicPrizeGroup = (int) configure('min_bet_prize_group', 1800); //平台最低投注奖金组
            $this->maxClassicPrizeGroup = (int) configure('max_bet_prize_group', 1960); //平台最高投注奖金组
            $this->eloqM = 'App\\Models\\' . $this->eloqM; // 当前的eloquent
            return $next($request);
        });
    }

    /**
     *处理客户端
     */
    private function handleEndUser()
    {
        $result = false;
        $open_route = [];
        $this->userAgent = new Agent();
        if ($this->userAgent->isDesktop()) {
            $open_route = BackendAdminRoute::where('is_open', 1)->pluck('method')->toArray();
            $result = true;
        } elseif (Request::header('from') === 'Lottery Center System v3.0.0.0') {
            $open_route = BackendAdminRoute::where('is_open', 1)->pluck('method')->toArray();
            $result = true;
        } else {
            Log::info('robot attacks: ' . json_encode(Input::all()) . json_encode(Request::header()));
            echo '机器人禁止操作';
            die();
        }
        if ($result === true) {
            $this->middleware('auth:' . $this->currentGuard, ['except' => $open_route]);
        }
    }

    /**
     *　初始化所有菜单，目前商户该拥有的菜单与权限
     */
    private function menuAccess()
    {
        $partnerEloq = new BackendSystemMenu();
        $this->fullMenuLists = $partnerEloq->forStar(); //所有的菜单
        $this->partnerMenulists = $partnerEloq->menuLists($this->currentPartnerAccessGroup); //目前所有的菜单为前端展示用的
    }

    /**
     *　检测目前的路由是否有权限访问
     */
    private function routeAccessCheck(): void
    {
        $this->currentOptRoute = Route::getCurrentRoute();
        $this->currentRouteName = $this->currentOptRoute->action['as']; //当前的route name;
        //$partnerAdREloq = BackendAdminRoute::where('route_name',$this->currentRouteName)->first()->parentRoute->menu;
        $partnerAdREloq = BackendAdminRoute::where('route_name', $this->currentRouteName)->first();
        if ($partnerAdREloq !== null) {
            $partnerMenuEloq = $partnerAdREloq->menu;
            //set if it is accissable or not
            if (!empty($this->currentPartnerAccessGroup->role)) {
                $this->accessGroupCheck($partnerMenuEloq);
            }
        }
    }

    private function accessGroupCheck($partnerMenuEloq)
    {
        if ($this->currentPartnerAccessGroup->role === '*') {
            $this->routeAccessable = true;
        } else {
            $currentRouteGroup = json_decode($this->currentPartnerAccessGroup->role, true);
            if (in_array($partnerMenuEloq->id, $currentRouteGroup)) {
                $this->routeAccessable = true;
            } elseif (in_array($this->currentRouteName, Config::get('routelistexclude'))) {
                $this->routeAccessable = true;
            }
        }
    }
    /**
     *记录后台管理员操作日志
     */
    private function adminOperateLog(): void
    {
        $this->log_uuid = Str::orderedUuid()->getNodeHex();
        $datas['input'] = $this->inputs;
        $datas['route'] = $this->currentOptRoute;
        $datas['log_uuid'] = $this->log_uuid;
        $logData = json_encode($datas, JSON_UNESCAPED_UNICODE);
        Log::channel('apibyqueue')->info($logData);
    }

    /**
     * @param  bool    $success
     * @param  mixed   $data
     * @param  string  $code
     * @param  mixed   $message
     * @param  string  $placeholder
     * @param  mixed   $substituted
     * @return JsonResponse
     */
    public function msgOut(
        $success = false,
        $data = [],
        $code = '',
        $message = '',
        $placeholder = '',
        $substituted = ''
    ): JsonResponse {
        /*if ($this->currentAuth->user())
        {
        $data['access_token']=$this->currentAuth->user()->remember_token;
        }*/
        $defaultSuccessCode = '200';
        $defaultErrorCode = '404';
        if ($success === true) {
            $code = $code === '' ? $defaultSuccessCode : $code;
        } else {
            $code = $code === '' ? $defaultErrorCode : $code;
        }
        if ($placeholder === '' || $substituted === '') {
            $message = $message === '' ? __('codes-map.' . $code) : $message;
        } else {
            $message = $message === '' ? __('codes-map.' . $code, [$placeholder => $substituted]) : $message;
        }
        $datas = [
            'success' => $success,
            'code' => $code,
            'data' => $data,
            'message' => $message,
        ];
        return response()->json($datas);
    }

    public function modelWithNameSpace($eloqM = null)
    {
        return $eloqM !== null ? 'App\\Models\\' . $eloqM : $eloqM;
    }

    /**
     * Generate Search Query
     * @param  object  $eloqM
     * @param  array   $searchAbleFields
     * @param  int     $fixedJoin
     * @param  mixed   $withTable
     * @param  array   $withSearchAbleFields
     * @param  string  $orderFields
     * @param  string  $orderFlow
     * @return mixed
     */
    public function generateSearchQuery(
        $eloqM,
        $searchAbleFields,
        $fixedJoin = 0,
        $withTable = null,
        $withSearchAbleFields = [],
        $orderFields = 'updated_at',
        $orderFlow = 'desc'
    ) {
        $searchCriterias = Arr::only($this->inputs, $searchAbleFields);
        $queryConditionField = $this->inputs['query_conditions'] ?? '';
        $queryConditions = Arr::wrap(json_decode($queryConditionField, true));
        $timeConditionField = $this->inputs['time_condtions'] ?? '';
        $timeConditions = Arr::wrap(json_decode($timeConditionField, true));
        $extraWhereContitions = $this->inputs['extra_where'] ?? [];
        $extraWhereIn = $this->inputs['where_in'] ?? [];
        $extraContitions = $this->inputs['extra_column'] ?? [];
        $queryEloq = new $eloqM();
        $sizeOfInputs = count($searchCriterias);
        //with Criterias  现在需要多表查询  所以放到连表的时候获取
        // $withSearchCriterias = Arr::only($this->inputs, $withSearchAbleFields);
        // $sizeOfWithInputs = count($withSearchCriterias);

        $pageSize = $this->inputs['page_size'] ?? 20;
        //where
        $whereData = $this->getWhereData(
            $extraContitions,
            $sizeOfInputs,
            $searchCriterias,
            $queryConditions,
            $timeConditions,
        );
        if (!empty($whereData)) {
            $queryEloq = $eloqM::where($whereData); //$extraContitions
        }
        //join
        if ($fixedJoin > 0) {
            $queryEloq = $this->eloqToJoin(
                $queryEloq,
                $fixedJoin,
                $withTable,
                $withSearchAbleFields,
                $queryConditions
            );
        }
        //whereIn
        if (!empty($extraWhereIn)) {
            $queryEloq = $queryEloq->whereIn($extraWhereIn['key'], $extraWhereIn['value']);
        }
        //extra wherein condition
        if (!empty($extraWhereContitions)) {
            $method = $extraWhereContitions['method'];
            $queryEloq = $queryEloq->$method($extraWhereContitions['key'], $extraWhereContitions['value']);
        }
        return $queryEloq->orderBy($orderFields, $orderFlow)->paginate($pageSize);
    }

    /**
     * Join Table with Eloquent
     * @param  object  $queryEloq
     * @param  int     $fixedJoin
     * @param  mixed   $withTable
     * @param  array   $queryConditions
     * @return mixed
     */
    public function eloqToJoin(
        $queryEloq,
        $fixedJoin,
        $withTable,
        $withSearchAbleFields,
        $queryConditions
    ) {
        $queryEloq = $queryEloq->with($withTable);
        if (!empty($withSearchAbleFields)) {
            for ($joinNum=0; $joinNum < $fixedJoin; $joinNum++) {
                $sizeOfWithInputs = 0;
                $whereHasTable = '';
                $withSearchCriterias = [];
                if ($fixedJoin > 1) {
                    $withSearchAbleField = $withSearchAbleFields[$joinNum];
                    $withSearchCriterias = Arr::only($this->inputs, $withSearchAbleField);
                    $sizeOfWithInputs = count($withSearchCriterias);
                    //截取whereHas表名
                    $interceptLenght = strrpos($withTable[$joinNum], ':') === false ?
                        strlen($withTable[$joinNum]) : strrpos($withTable[$joinNum], ':');
                    $whereHasTable = substr($withTable[$joinNum], 0, $interceptLenght);
                } elseif ($fixedJoin === 1) {
                    $withSearchCriterias = Arr::only($this->inputs, $withSearchAbleFields);
                    $sizeOfWithInputs = count($withSearchAbleFields);
                    //截取whereHas表名
                    $interceptLenght = strrpos($withTable, ':') === false ?
                        strlen($withTable) : strrpos($withTable, ':');
                    $whereHasTable = substr($withTable, 0, $interceptLenght);
                }
                $this->eloqWhereHas($queryEloq, $whereHasTable, $sizeOfWithInputs, $withSearchCriterias, $queryConditions);
            }
        }
        return $queryEloq;
    }

    /**
     * 获取查询的表的where条件
     * @param  array $extraContitions
     * @param  int $sizeOfInputs
     * @param  array $searchCriterias
     * @param  array $queryConditions
     * @param  array $timeConditions
     * @return array
     */
    private function getWhereData(
        $extraContitions,
        $sizeOfInputs,
        $searchCriterias,
        $queryConditions,
        $timeConditions
    ) {
        $whereData = [];
        if (!empty($extraContitions)) {
            $whereData = array_merge($whereData, $extraContitions);
        }

        if ($sizeOfInputs > 1) {
            //for multiple where condition searching
            foreach ($searchCriterias as $field => $value) {
                $sign = array_key_exists($field, $queryConditions) ? $queryConditions[$field] : '=';
                if ($sign === 'LIKE') {
                    $sign = strtolower($sign);
                    $value = '%' . $value . '%';
                }
                $whereCriteria = [];
                $whereCriteria[] = $field;
                $whereCriteria[] = $sign;
                $whereCriteria[] = $value;
                $whereData[] = $whereCriteria;
            }
            if (!empty($timeConditions)) {
                $whereData = array_merge($whereData, $timeConditions);
            }
        } else {
            $whereData = array_merge($whereData, $searchCriterias);
            if (!empty($timeConditions)) {
                $whereData = array_merge($whereData, $timeConditions);
            }
        }
        return $whereData;
    }

    /**
     * 关联的表的where条件
     * @param  object $queryEloq
     * @param  string $whereHasTable
     * @param  int $sizeOfWithInputs
     * @param  array $withSearchCriterias
     * @param  array $queryConditions
     */
    private function eloqWhereHas(
        $queryEloq,
        $whereHasTable,
        $sizeOfWithInputs,
        $withSearchCriterias,
        $queryConditions
    ) {
        if (!empty($withSearchCriterias)) {
            $queryEloq = $queryEloq->whereHas(
                $whereHasTable,
                static function ($query) use (
                    $sizeOfWithInputs,
                    $withSearchCriterias,
                    $queryConditions
                ) {
                    foreach ($withSearchCriterias as $field => $value) {
                        if ($value === '*') {
                            continue;
                        }
                        if ($sizeOfWithInputs > 1) {
                            $whereData = [];
                            $whereCriteria = [];
                            $whereCriteria[] = $field;
                            $whereCriteria[] = array_key_exists(
                                $field,
                                $queryConditions
                            ) ? $queryConditions[$field] : '=';
                            $whereCriteria[] = $value;
                            $whereData[] = $whereCriteria;
                            $query->where($whereData);
                        } elseif ($sizeOfWithInputs === 1) {
                           $sign = array_key_exists(
                               $field,
                               $queryConditions
                           ) ? $queryConditions[$field] : '=';
                           if ($sign === 'LIKE') {
                               $sign = strtolower($sign);
                               $value = '%' . $value . '%';
                           }
                           $query->where($field, $sign, $value);
                        }
                    }
                }
            );
        }
    }

    /**
     * @param  object $eloqM
     * @param  array  $datas
     */
    public function editAssignment($eloqM, $datas)
    {
        foreach ($datas as $field => $value) {
            $eloqM->$field = $value;
        }
        return $eloqM;
    }
}
