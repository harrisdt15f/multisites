<?php

namespace App\Http\Controllers\FrontendApi;

use App\Http\Controllers\Controller;
use App\Models\DeveloperUsage\Frontend\FrontendAppRoute;
use App\Models\DeveloperUsage\Frontend\FrontendWebRoute;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

class FrontendApiMainController extends Controller
{
    public $inputs;
    public $partnerUser; //当前的用户
    protected $currentOptRoute; //目前路由
    public $currentPlatformEloq; //当前商户存在的平台
    public $eloqM = ''; // 当前的eloquent
    //当前的route name;
    protected $log_uuid; //当前的logId
    protected $currentGuard;
    public $currentAuth;
    public $userAgent;
    public $minClassicPrizeGroup; //平台最低投注奖金组
    public $maxClassicPrizeGroup; //平台最高投注奖金组

    /**
     * AdminMainController constructor.
     */
    public function __construct()
    {
        $this->handleEndUser();
        $this->middleware(function ($request, $next) {
            $this->userOperateLog();
            if (($this->partnerUser !== null) && $this->partnerUser->platform()->exists()) {
                $this->currentPlatformEloq = $this->partnerUser->platform; //获取目前账号用户属于平台的对象
            }
            $this->minClassicPrizeGroup = (int)configure('min_bet_prize_group', 1800);//平台最低投注奖金组
            $this->maxClassicPrizeGroup = (int)configure('max_bet_prize_group', 1960);//平台最高投注奖金组
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
            $open_route = FrontendWebRoute::where('is_open', 1)->pluck('method')->toArray();
            $this->currentGuard = 'frontend-web';
            $result = true;
        } elseif ($this->userAgent->isRobot()) {
            Log::info('robot attacks: ' . json_encode(Input::all()) . json_encode(Request::header()));
            die();
        } else {
            $open_route = FrontendAppRoute::where('is_open', 1)->pluck('method')->toArray();
            $this->currentGuard = 'frontend-mobile';
            $result = true;
        }
        if ($result === true) {
            $this->middleware('auth:' . $this->currentGuard, ['except' => $open_route]);
        }
    }

    /**
     *记录后台管理员操作日志
     */
    private function userOperateLog(): void
    {
        $this->inputs = Input::all(); //获取所有相关的传参数据
        $this->currentAuth = auth($this->currentGuard);
        $this->partnerUser = $this->currentAuth->user();
        //登录注册的时候是没办法获取到当前用户的相关信息所以需要过滤
        $this->currentOptRoute = Route::getCurrentRoute();
        $this->log_uuid = Str::orderedUuid()->getNodeHex();
        $datas['input'] = $this->inputs;
        $datas['route'] = $this->currentOptRoute;
        $datas['log_uuid'] = $this->log_uuid;
        $logData = json_encode($datas, JSON_UNESCAPED_UNICODE);
        Log::channel('frontend-by-queue')->info($logData);
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
        $defaultSuccessCode = '200';
        $defaultErrorCode = '404';
        if ($success === true) {
            $code = $code === '' ? $defaultSuccessCode : $code;
        } else {
            $code = $code === '' ? $defaultErrorCode : $code;
        }
        if ($placeholder === '' || $substituted === '') {
            $message = $message === '' ? __('frontend-codes-map.' . $code) : $message;
        } else {
            $message = $message === '' ? __('frontend-codes-map.' . $code, [$placeholder => $substituted]) : $message;
        }
        $datas = [
            'success' => $success,
            'code' => $code,
            'data' => $data,
            'message' => $message,
        ];
        return response()->json($datas);
    }

    protected function modelWithNameSpace($eloqM = null)
    {
        return $eloqM !== null ? 'App\\Models\\' . $eloqM : $eloqM;
    }

    /**
     * Generate Search Query
     * @param  mixed   $eloqM
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
