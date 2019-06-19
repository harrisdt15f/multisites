<?php

namespace App\Http\Controllers\FrontendApi;

use App\Http\Controllers\Controller;
use App\Models\DeveloperUsage\Frontend\FrontendWebRoute;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FrontendApiMainController extends Controller
{
    protected $inputs;
    protected $partnerAdmin; //当前的商户用户
    protected $currentOptRoute; //目前路由
    protected $currentPlatformEloq = null; //当前商户存在的平台
    public $eloqM = ''; // 当前的eloquent
    //当前的route name;
    protected $log_uuid; //当前的logId
    protected $currentGuard = 'frontend-web';
    protected $currentAuth;

    /**
     * AdminMainController constructor.
     */
    public function __construct()
    {
        $open_route = FrontendWebRoute::where('is_open', 1)->pluck('method')->toArray();
        $this->middleware('auth:frontend-web', ['except' => $open_route]);
        $this->middleware(function ($request, $next) {
            $this->currentAuth = auth($this->currentGuard);
            $this->partnerAdmin = $this->currentAuth->user();
            $this->inputs = Input::all(); //获取所有相关的传参数据
            //登录注册的时候是没办法获取到当前用户的相关信息所以需要过滤
            $this->userOperateLog();
            $this->eloqM = 'App\\Models\\' . $this->eloqM; // 当前的eloquent
            return $next($request);
        });
    }

    /**
     *记录后台管理员操作日志
     */
    private function userOperateLog(): void
    {
        $this->log_uuid = Str::orderedUuid()->getNodeHex();
        $datas['input'] = $this->inputs;
        $datas['route'] = $this->currentOptRoute;
        $datas['log_uuid'] = $this->log_uuid;
        $log = json_encode($datas, JSON_UNESCAPED_UNICODE);
        Log::channel('frontend-by-queue')->info($log);
    }

    /**
     * @param  bool  $success
     * @param  array  $data
     * @param  string  $message
     * @param  string  $code
     * @return JsonResponse
     */
    public function msgOut($success = false, $data = [], $code = '', $message = ''): JsonResponse
    {
        $defaultSuccessCode = '200';
        $defaultErrorCode = '404';
        if ($success === true) {
            $code = $code == '' ? $defaultSuccessCode : $code;
        } else {
            $code = $code == '' ? $defaultErrorCode : $code;
        }
        $message = $message == '' ? __('frontend-codes-map.' . $code) : $message;
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
     * @param $eloqM
     * @param $searchAbleFields
     * @param  int  $fixedJoin
     * @param $withTable
     * @param $withSearchAbleFields
     * @param  string  $orderFields
     * @param  string  $orderFlow
     * @return mixed
     */
    public function generateSearchQuery(
        $eloqM,
        $searchAbleFields,
        $fixedJoin = 0,
        $withTable = null,
        $withSearchAbleFields = null,
        $orderFields = 'updated_at',
        $orderFlow = 'desc'
    ) {
        $searchCriterias = Arr::only($this->inputs, $searchAbleFields);
        $queryConditionField = $this->inputs['query_conditions'] ?? '';
        $queryConditions = Arr::wrap(json_decode($queryConditionField, true));
        $timeConditionField = $this->inputs['time_condtions'] ?? '';
        $timeConditions = Arr::wrap(json_decode($timeConditionField, true));
        $extraWhereContitions = $this->inputs['extra_where'] ?? [];
        $extraContitions = $this->inputs['extra_column'] ?? [];
        $queryEloq = new $eloqM;
        $sizeOfInputs = count($searchCriterias);
        //with Criterias
        $withSearchCriterias = Arr::only($this->inputs, $withSearchAbleFields);
        $sizeOfWithInputs = count($withSearchCriterias);

        $pageSize = $this->inputs['page_size'] ?? 20;
        if ($sizeOfInputs == 1) {
            //for single where condition searching
            if (!empty($searchCriterias)) {
                foreach ($searchCriterias as $key => $value) {
                    $sign = array_key_exists($key, $queryConditions) ? $queryConditions[$key] : '=';
                    if ($sign == 'LIKE') {
                        $sign = strtolower($sign);
                        $value = '%' . $value . '%';
                    }
                    $whereCriteria = [];
                    $whereCriteria[] = $key;
                    $whereCriteria[] = $sign;
                    $whereCriteria[] = $value;
                    $whereData[] = $whereCriteria;
                }
                if (!empty($timeConditions)) {
                    $whereData = array_merge($whereData, $timeConditions);
                }
                if (!empty($extraContitions)) {
                    $whereData = array_merge($whereData, $extraContitions);
                }
                $queryEloq = $eloqM::where($whereData);
                if ($fixedJoin > 0) {
                    $queryEloq = $this->eloqToJoin($queryEloq, $fixedJoin, $withTable, $sizeOfWithInputs,
                        $withSearchCriterias, $queryConditions);
                }
            } else {
                //for default
                if ($fixedJoin > 0) {
                    $queryEloq = $this->eloqToJoin($queryEloq, $fixedJoin, $withTable, $sizeOfWithInputs,
                        $withSearchCriterias, $queryConditions);
                }
            }
        } else {
            if ($sizeOfInputs > 1) {
                //for multiple where condition searching
                if (!empty($searchCriterias)) {
                    $whereData = [];
                    foreach ($searchCriterias as $key => $value) {
                        $sign = array_key_exists($key, $queryConditions) ? $queryConditions[$key] : '=';
                        if ($sign == 'LIKE') {
                            $sign = strtolower($sign);
                            $value = '%' . $value . '%';
                        }
                        $whereCriteria = [];
                        $whereCriteria[] = $key;

                        $whereCriteria[] = $sign;
                        $whereCriteria[] = $value;
                        $whereData[] = $whereCriteria;
                    }
                    if (!empty($timeConditions)) {
                        $whereData = array_merge($whereData, $timeConditions);
                    }
                    if (!empty($extraContitions)) {
                        $whereData = array_merge($whereData, $extraContitions);
                    }
                    $queryEloq = $eloqM::where($whereData);
                    if ($fixedJoin > 0) {
                        $queryEloq = $this->eloqToJoin($queryEloq, $fixedJoin, $withTable, $sizeOfWithInputs,
                            $withSearchCriterias, $queryConditions);
                    }
                } else {
                    if ($fixedJoin > 0) {
                        $queryEloq = $this->eloqToJoin($queryEloq, $fixedJoin, $withTable, $sizeOfWithInputs,
                            $withSearchCriterias, $queryConditions);
                    }
                }
            } else {
                $whereData = [];
                if (!empty($timeConditions)) {
                    $whereData = $timeConditions;
                }
                if (!empty($extraContitions)) {
                    $whereData = array_merge($whereData, $extraContitions);
                }
                if (!empty($whereData)) {
                    $queryEloq = $eloqM::where($whereData); //$extraContitions
                }
                if ($fixedJoin > 0) {
                    $queryEloq = $this->eloqToJoin($queryEloq, $fixedJoin, $withTable, $sizeOfWithInputs,
                        $withSearchCriterias, $queryConditions);
                }
            }
        }
        //extra wherein condition
        if (!empty($extraWhereContitions)) {
            $method = $extraWhereContitions['method'];
            $queryEloq = $queryEloq->$method($extraWhereContitions['key'], $extraWhereContitions['value']);
        }
        $data = $queryEloq->orderBy($orderFields, $orderFlow)->paginate($pageSize);
        return $data;
    }

    /**
     * Join Table with Eloquent
     * @param $queryEloq
     * @param $fixedJoin
     * @param $withTable
     * @param $sizeOfWithInputs
     * @param $withSearchCriterias
     * @param $queryConditions
     * @return mixed
     */
    public function eloqToJoin(
        $queryEloq,
        $fixedJoin,
        $withTable,
        $sizeOfWithInputs,
        $withSearchCriterias,
        $queryConditions
    ) {
        if (empty($sizeOfWithInputs)) //如果with 没有参数可以查询时查询全部
        {
            switch ($fixedJoin) {
                case 1: //有一个连表查询的情况下
                    $queryEloq = $queryEloq->with($withTable);
                    break;
            }
        } else {
            switch ($fixedJoin) {
                case 1: //有一个连表查询的情况下
                    $queryEloq = $queryEloq->with($withTable)->whereHas($withTable,
                        function ($query) use ($sizeOfWithInputs, $withSearchCriterias, $queryConditions) {
                            if ($sizeOfWithInputs > 1) {

                                if (!empty($withSearchCriterias)) {
                                    foreach ($withSearchCriterias as $key => $value) {
                                        $whereCriteria = [];
                                        $whereCriteria[] = $key;
                                        $whereCriteria[] = array_key_exists($key,
                                            $queryConditions) ? $queryConditions[$key] : '=';
                                        $whereCriteria[] = $value;
                                        $whereData[] = $whereCriteria;
                                    }
                                    $query->where($whereData);
                                }
                            } else {
                                if ($sizeOfWithInputs == 1) {
                                    if (!empty($withSearchCriterias)) {
                                        foreach ($withSearchCriterias as $key => $value) {
                                            $sign = array_key_exists($key,
                                                $queryConditions) ? $queryConditions[$key] : '=';
                                            if ($sign == 'LIKE') {
                                                $sign = strtolower($sign);
                                                $value = '%' . $value . '%';
                                            }
                                            $query->where($key, $sign, $value);
                                        }
                                    }
                                }
                            }
                        });
                    break;
            }
        }

        return $queryEloq;
    }

    /**
     * @param $eloqM
     * @param  array  $datas
     */
    public function editAssignment($eloqM, $datas)
    {
        foreach ($datas as $k => $v) {
            $eloqM->$k = $v;
        }
        return $eloqM;
    }
}
