<?php

namespace App\Http\Controllers\BackendApi\DeveloperUsage\Frontend;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use Illuminate\Support\Facades\Validator;

class FrontendAppRouteController extends BackEndApiMainController
{
    protected $eloqM = 'DeveloperUsage\Frontend\FrontendAppRoute';

    public function detail()
    {
        $datas = $this->eloqM::get();
        return $this->msgOut(true, $datas);
    }

    public function add()
    {
        $validator = Validator::make($this->inputs, [
            'route_name' => 'required|string',
            'frontend_model_id' => 'required|numeric',
            'title' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $checkData = $this->eloqM::where('route_name', $this->inputs['route_name'])->first();
        if (!is_null($checkData)) {
            return $this->msgOut(false, [], 101500);
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

    public function delete()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $checkData = $this->eloqM::find($this->inputs['id']);
        if (is_null($checkData)) {
            return $this->msgOut(false, [], 101501);
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
}
