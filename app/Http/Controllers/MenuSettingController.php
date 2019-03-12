<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuSettingController extends AdminMainController
{
    //
    public function index()
    {
        return view('superadmin.menu-setting.index');
    }
}
