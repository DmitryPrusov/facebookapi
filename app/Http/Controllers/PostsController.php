<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;


class PostsController extends Controller
{
    public function index()
    {
        $fb = App::make('SammyK\LaravelFacebookSdk\LaravelFacebookSdk');

        try {
            $response = $fb->get('/me?fields=id,name,email');
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            dd($e->getMessage());
        }



        $userNode = $response->getGraphUser();
        //printf('Hello, %s!', $userNode->getName());

        return view('posts', compact('userNode'));
    }
    //
}
