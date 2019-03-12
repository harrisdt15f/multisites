<?php

namespace App\Http\Controllers;

use App\Menus;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AdminMainController extends Controller
{

    /**
     * AdminMainController constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            View::share('user', $this->user);
            $menuObj = new Menus();
            $menulists = $menuObj->menuLists();
            View::share('menulists', $menulists);
            return $next($request);
        });

    }
}
