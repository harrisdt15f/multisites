<?php

namespace App\Http\Controllers\BackendApi\DeveloperUsage\Backend\Routes;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\Menu\PartnerMenus;
use Illuminate\Support\Facades\Validator;

class RouteController extends BackEndApiMainController
{
    protected $eloqM = 'DeveloperUsage\Backend\PartnerAdminRoute';

    public function detail()
    {
        $datas = $this->eloqM::with('menu')->get();
        return $this->msgOut(true, $datas);
    }

    public function add()
    {
        $validator = Validator::make($this->inputs, [
            'route_name' => 'required|string',
            'controller' => 'required|string',
            'method' => 'required|string',
            'menu_group_id' => 'required|numeric',
            'title' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $checkTitle = $this->eloqM::where('title', $this->inputs['title'])->first();
        if (!is_null($checkTitle)) {
            return $this->msgOut(false, [], '101400');
        }
        $checkData = $this->eloqM::where('route_name', $this->inputs['route_name'])->first();
        if (!is_null($checkData)) {
            return $this->msgOut(false, [], '101403');
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
        $checkMeun = PartnerMenus::where('id', $this->inputs['menu_group_id'])->first();
        if (is_null($checkMeun)) {
            return $this->msgOut(false, [], '101402');
        }
        $checkTitle = $this->eloqM::where(function ($query) {
            $query->where('title', $this->inputs['title'])
                ->where('id', '!=', $this->inputs['id']);
        })->first();
        if (!is_null($checkTitle)) {
            return $this->msgOut(false, [], '101400');
        }
        $editData = $this->inputs;
        unset($editData['id']);
        if (array_key_exists('route_name', $editData)) {
            unset($editData['route_name']);
        }
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
