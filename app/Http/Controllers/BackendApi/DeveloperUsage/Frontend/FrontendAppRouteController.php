<?php

namespace App\Http\Controllers\BackendApi\DeveloperUsage\Frontend;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\DeveloperUsage\Frontend\FrontendAppRouteAddRequest;
use App\Http\Requests\Backend\DeveloperUsage\Frontend\FrontendAppRouteDeleteRequest;
use App\Http\Requests\Backend\DeveloperUsage\Frontend\FrontendAppRouteIsOpenRequest;

class FrontendAppRouteController extends BackEndApiMainController
{
    protected $eloqM = 'DeveloperUsage\Frontend\FrontendAppRoute';

    //APP路由列表
    public function detail()
    {
        $datas = $this->eloqM::select('id', 'route_name', 'frontend_model_id', 'title', 'description', 'is_open')->get()->toArray();
        return $this->msgOut(true, $datas);
    }

    //添加APP路由
    public function add(FrontendAppRouteAddRequest $request)
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

    //删除APP路由
    public function delete(FrontendAppRouteDeleteRequest $request)
    {
        $inputDatas = $request->validated();
        try {
            $this->eloqM::where('id', $inputDatas)->delete();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //设置APP路由是否开放
    public function isOpen(FrontendAppRouteIsOpenRequest $request)
    {
        $inputDatas = $request->validated();
        $pastData = $this->eloqM::find($inputDatas['id']);
        try {
            $pastData->is_open = $inputDatas['is_open'];
            $pastData->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
}
