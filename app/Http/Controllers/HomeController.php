<?php

namespace App\Http\Controllers;

use App\Menus;
use Illuminate\Http\Request;

class HomeController extends Controller
{

  public function index()
  {
      $menulists = Menus::menuLists();
      return view('index',['menulists' => $menulists]);
  }

    public function login()
    {
        return view('user.login');
    }
}
