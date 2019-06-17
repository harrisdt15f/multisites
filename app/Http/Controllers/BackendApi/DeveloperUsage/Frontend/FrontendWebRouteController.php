<?php

namespace App\Http\Controllers\BackendApi\DeveloperUsage\Frontend;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\DeveloperUsage\Frontend\FrontendWebRouteAddRequest;
use App\Http\Requests\Backend\DeveloperUsage\Frontend\FrontendWebRouteDeleteRequest;
use App\Http\Requests\Backend\DeveloperUsage\Frontend\FrontendWebRouteIsOpenRequest;
use Exception;
use Illuminate\Http\JsonResponse;

class FrontendWebRouteController extends BackEndApiMainController
{
    protected $eloqM = 'DeveloperUsage\Frontend\FrontendWebRoute';

    /**
     * web路由列表
     * @return JsonResponse
     */
    public function detail(): JsonResponse
    {
        $datas = $this->eloqM::select('id', 'route_name', 'frontend_model_id', 'title', 'description', 'is_open')->get()->toArray();
        return $this->msgOut(true, $datas);
    }

    /**
     * 添加web路由
     * @param FrontendAppRouteAddRequest $request
     * @return JsonResponse
     */
    public function add(FrontendWebRouteAddRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        try {
            $routeEloq = new $this->eloqM;
            $routeEloq->fill($inputDatas);
            $routeEloq->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 删除web路由
     * @param  FrontendAppRouteDeleteRequest $request
     * @return JsonResponse
     */
    public function delete(FrontendWebRouteDeleteRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        try {
            $this->eloqM::where('id', $inputDatas['id'])->delete();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 设置web路由是否开放
     * @param  FrontendAppRouteIsOpenRequest $request
     * @return JsonResponse
     */
    public function isOpen(FrontendWebRouteIsOpenRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $pastDataEloq = $this->eloqM::find($inputDatas['id']);
        try {
            $pastDataEloq->is_open = $inputDatas['is_open'];
            $pastDataEloq->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
}
