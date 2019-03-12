<?php

namespace App\Http\Controllers;

use App\Menus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /*public function __construct()
    {
        $this->middleware('auth');
    }*/


    /**
     * Show the application dashboard.
     *
     */
    public function index()
    {
        $menulists = Menus::menuLists();
        return view('index',['menulists' => $menulists]);
    }
}
