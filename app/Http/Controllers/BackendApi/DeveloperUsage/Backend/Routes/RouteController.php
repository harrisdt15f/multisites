<?php

namespace App\Http\Controllers\BackendApi\DeveloperUsage\Backend\Routes;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\DeveloperUsage\Backend\Routes\RoutesAddRequest;
use App\Http\Requests\Backend\DeveloperUsage\Backend\Routes\RoutesDeleteRequest;
use App\Http\Requests\Backend\DeveloperUsage\Backend\Routes\RoutesEditRequest;
use Exception;
use Illuminate\Http\JsonResponse;

class RouteController extends BackEndApiMainController
{
    protected $eloqM = 'DeveloperUsage\Backend\BackendAdminRoute';

    /**
     * @return JsonResponse
     */
    public function detail(): JsonResponse
    {
        $datas = $this->eloqM::with('menu')->get();
        return $this->msgOut(true, $datas);
    }

    /**
     * 添加路由
     * @param   RoutesAddRequest $request
     * @return  JsonResponse
     */
    public function add(RoutesAddRequest $request): JsonResponse
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
     * 编辑路由
     * @param  RoutesEditRequest $request
     * @return JsonResponse
     */
    public function edit(RoutesEditRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $pastEloq = $this->eloqM::find($inputDatas['id']);
        $checkTitle = $this->eloqM::where('title', $inputDatas['title'])->where('id', '!=', $inputDatas['id'])->first();
        if (!is_null($checkTitle)) {
            return $this->msgOut(false, [], '101400');
        }
        try {
            $this->editAssignment($pastEloq, $inputDatas);
            $pastEloq->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 删除路由
     * @param  RoutesDeleteRequest $request
     * @return JsonResponse
     */
    public function delete(RoutesDeleteRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        try {
            $this->eloqM::find($inputDatas['id'])->delete();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
}
