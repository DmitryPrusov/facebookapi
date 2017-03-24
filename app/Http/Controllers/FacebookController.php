<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk as LaravelFacebookSdk;
//use App\Facebook as fbModel;
use Session;

class FacebookController extends Controller
{

    public function __construct(LaravelFacebookSdk $fb)
    {
        $this->fb = $fb;
    }

    public function login()
    {
        // Send an array of permissions to request
        $login_url = $this->fb->getLoginUrl(['email', 'publish_actions']);
        // print_r($login_url);
        // die();
        return $login_url;
        // Obviously you'd do this in blade :)
        //echo '<a href="' . $login_url . '">Login with Facebook</a>';
    }
    public function callback()
    {
        // Obtain an access token.
        try {
            $token = $this->fb->getAccessTokenFromRedirect();
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            dd($e->getMessage());
        }

        // Access token will be null if the user denied the request
        // or if someone just hit this URL outside of the OAuth flow.
        if (! $token) {
            // Get the redirect helper
            $helper = $this->fb->getRedirectLoginHelper();

            if (! $helper->getError()) {
                abort(403, 'Unauthorized action.');
            }

            // User denied the request
            dd(
                $helper->getError(),
                $helper->getErrorCode(),
                $helper->getErrorReason(),
                $helper->getErrorDescription()
            );
        }

        if (! $token->isLongLived()) {
            // OAuth 2.0 client handler
            $oauth_client = $this->fb->getOAuth2Client();

            // Extend the access token.
            try {
                $token = $oauth_client->getLongLivedAccessToken($token);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                dd($e->getMessage());
            }
        }

        $this->fb->setDefaultAccessToken($token);

        // Save for later
        Session::put('fb_user_access_token', (string) $token);

        // Get basic info on the user from Facebook.
        try {
            $response = $this->fb->get('/me?fields=id,name,email');
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            dd($e->getMessage());
        }

        // Convert the response to a `Facebook/GraphNodes/GraphUser` collection
        $facebook_user = $response->getGraphUser();
        // print_r($facebook_user);
        // die();
        // Create the user if it does not exist or update the existing entry.
        // This will only work if you've added the SyncableGraphNodeTrait to your User model.
        $user = User::createOrUpdateGraphNode($facebook_user);
        Session::put('fb_user_id', (string) $user->id);
        // Log the user into Laravel
        //Auth::login($user);

        return redirect('/home')->with('message', 'Successfully logged in with Facebook');
    }

    public function post($message)
    {

        //print_r($req->input('message'));
        $linkData = [
            'message' => $message
        ];

        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $this->fb->post('/me/feed', $linkData, Session::get('fb_user_access_token'));
            if ($response){
                return true;
                // Session::flash('alert-success', 'Successfully Put status on Social apps');
                // return redirect('/home');
            }
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }

    public function getFreinds()
    {
        $freinds = $this->fb->get('/me/friends', Session::get('fb_user_access_token'));

        echo "<pre>";
        print_r($freinds);
        die();
    }
}