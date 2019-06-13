<?php

namespace App\Http\Controllers\BackendApi\DeveloperUsage\Backend\Routes;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\Menu\BackendSystemMenu;
use Illuminate\Support\Facades\Validator;

class RouteController extends BackEndApiMainController
{
    protected $eloqM = 'DeveloperUsage\Backend\BackendAdminRoute';

    public function detail()
    {
        $datas = $this->eloqM::with('menu')->get();
        return $this->msgOut(true, $datas);
    }

    public function add()
    {
        $validator = Validator::make($this->inputs, [
            'route_name' => 'required|string|unique:backend_admin_routes,route_name',
            'controller' => 'required|string',
            'method' => 'required|string',
            'menu_group_id' => 'required|numeric|exists:backend_system_menus,id',
            'title' => 'required|string|unique:backend_admin_routes,title',
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

    public function edit()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric|exists:backend_admin_routes,id',
            'controller' => 'required|string',
            'method' => 'required|string',
            'menu_group_id' => 'required|numeric|exists:backend_system_menus,id',
            'title' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastEloq = $this->eloqM::find($this->inputs['id']);
        $checkTitle = $this->eloqM::where('title', $this->inputs['title'])->where('id', '!=', $this->inputs['id'])->first();
        if (!is_null($checkTitle)) {
            return $this->msgOut(false, [], '101400');
        }
        try {
            $this->editAssignment($pastEloq, $this->inputs);
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
            'id' => 'required|numeric|exists:backend_admin_routes,id',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastEloq = $this->eloqM::find($this->inputs['id']);
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
