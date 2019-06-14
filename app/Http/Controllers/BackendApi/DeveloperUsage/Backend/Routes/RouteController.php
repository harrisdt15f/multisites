<?php

namespace App\Http\Controllers\BackendApi\DeveloperUsage\Backend\Routes;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\DeveloperUsage\Backend\Routes\RoutesAddRequest;
use App\Http\Requests\Backend\DeveloperUsage\Backend\Routes\RoutesDeleteRequest;
use App\Http\Requests\Backend\DeveloperUsage\Backend\Routes\RoutesEditRequest;
use App\Models\DeveloperUsage\Menu\BackendSystemMenu;

class RouteController extends BackEndApiMainController
{
    protected $eloqM = 'DeveloperUsage\Backend\BackendAdminRoute';

    public function detail()
    {
        $datas = $this->eloqM::with('menu')->get();
        return $this->msgOut(true, $datas);
    }

    //添加路由
    public function add(RoutesAddRequest $request)
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

    //编辑路由
    public function edit(RoutesEditRequest $request)
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

    //删除路由
    public function delete(RoutesDeleteRequest $request)
    {
        $inputDatas = $request->validated();
        $pastEloq = $this->eloqM::find($inputDatas['id']);
        try {
            $pastEloq->delete();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
}
