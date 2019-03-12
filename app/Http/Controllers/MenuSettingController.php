<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuSettingController extends Controller
{
    //
    public function index()
    {
        return view('superadmin.menu-setting.index');
    }
}
