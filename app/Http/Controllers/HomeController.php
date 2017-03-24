<?php

namespace App\Http\Controllers;
use App\Http\Controllers\FacebookController as fbController;

use SammyK\LaravelFacebookSdk\LaravelFacebookSdk as LaravelFacebookSdk;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(LaravelFacebookSdk $fb){




        return view('home');
//    {
//        //twitter
//        $twController = new twController();
//        $login_url_tw = $twController->login();
//
//        //facebook
//        $fbController = new fbController($fb);
//        $login_url_fb = $fbController->login();
//        return view('home')->with('login_url_fb', $login_url_fb)->with('login_url_tw', $login_url_tw);
//    }
}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
//    public function index(LaravelFacebookSdk $fb)
//    {
//        //twitter
//        $twController = new twController();
//        $login_url_tw = $twController->login();
//
//        //facebook
//        $fbController = new fbController($fb);
//        $login_url_fb = $fbController->login();
//        return view('home')->with('login_url_fb', $login_url_fb)->with('login_url_tw', $login_url_tw);
//    }
}