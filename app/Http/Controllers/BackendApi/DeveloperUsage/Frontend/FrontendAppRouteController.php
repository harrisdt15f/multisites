<?php

namespace App\Http\Controllers\BackendApi\DeveloperUsage\Frontend;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use Illuminate\Support\Facades\Validator;

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
    public function add()
    {
        $validator = Validator::make($this->inputs, [
            'route_name' => 'required|string|unique:frontend_app_routes,route_name',
            'controller' => 'required|string',
            'method' => 'required|string',
            'frontend_model_id' => 'required|numeric|exists:frontend_allocated_models,id',
            'title' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        try {
            $routeEloq = new $this->eloqM;
            $routeEloq->fill($this->inputs);
            $routeEloq->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //删除APP路由
    public function delete()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric|unique:frontend_app_routes,id',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        try {
            $this->eloqM::where('id', $this->inputs['id'])->delete();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //设置APP路由是否开放
    public function isOpen()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric|unique:frontend_app_routes,id',
            'is_open' => 'required|numeric|in:0,1',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastData = $this->eloqM::find($this->inputs['id']);
        try {
            $pastData->is_open = $this->inputs['is_open'];
            $pastData->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
}
