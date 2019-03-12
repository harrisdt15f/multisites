<?php

namespace App\Http\Controllers;

use App\Menus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class MenuSettingController extends AdminMainController
{

    public function index()
    {
        $firstlevelmenus = Menus::getFirstLevelList();
        $routeCollection = Route::getRoutes()->get();
        $editMenu = Menus::all();
        $rname = [];
        foreach ($routeCollection as $key => $r) {
            if (isset($r->action['as'])) {
                if ($r->action['prefix'] !== '_debugbar') {
                    $rname[$r->action['as']] = $r->uri;
                }
            }
        }
        return view('superadmin.menu-setting.index', ['firstlevelmenus' => $firstlevelmenus, 'rname' => $rname,'editMenu'=> $editMenu]);
    }
}
