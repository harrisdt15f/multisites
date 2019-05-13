<?php

namespace App\Http\Controllers\BackendApi;

use Illuminate\Support\Facades\Validator;

class RouteController extends BackEndApiMainController
{
    protected $eloqM = 'PartnerAdminRoute';

    public function detail()
    {
        $fixedJoin = 1;
        $withTable = 'menu';
        $withSearchAbleFields = ['label'];
        $searchAbleFields = ['route_name', 'menu_group_id', 'title'];
        $datas = $this->generateSearchQuery($this->eloqM, $searchAbleFields, $fixedJoin, $withTable, $withSearchAbleFields);
        return $this->msgOut(true, $datas);
    }

    public function add()
    {
        $validator = Validator::make($this->inputs, [
            'route_name' => 'required|string',
            'menu_group_id' => 'required|numeric',
            'title' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $checkData = $this->eloqM::where('route_name', $this->inputs['route_name'])->first();
        if (!is_null($checkData)) {
            return $this->msgOut(false, [], '101400');
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

    public function edit()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'route_name' => 'required|string',
            'menu_group_id' => 'required|numeric',
            'title' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastEloq = $this->eloqM::find($this->inputs['id']);
        if (is_null($pastEloq)) {
            return $this->msgOut(false, [], '101401');
        }
        $checkRouteName = $this->eloqM::where(function ($query) {
            $query->where('route_name', $this->inputs['route_name'])
                ->where('id', '!=', $this->inputs['id']);
        })->first();
        if (!is_null($checkRouteName)) {
            return $this->msgOut(false, [], '101400');
        }
        $editData = $this->inputs;
        unset($editData['id']);
        try {
            $this->editAssignment($pastEloq, $editData);
            $pastEloq->save();
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
        $pastEloq = $this->eloqM::find($this->inputs['id']);
        if (is_null($pastEloq)) {
            return $this->msgOut(false, [], '101401');
        }
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
