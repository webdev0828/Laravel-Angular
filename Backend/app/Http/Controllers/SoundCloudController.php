<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Plans;
use App\OauthIdentity;
use App\User;
use App\Http\Requests;
use Socialite;

use Njasm\Soundcloud\SoundcloudFacade;

class SoundCloudController extends Controller
{
   	public function index()
    {   
        $title = 'Soundcloud Setting';
        $user = \App\User::where('user_type', 'admin')->first();
        $OauthIdentity = OauthIdentity::where('user_id',$user->id)->first();
        $isGating =  $OauthIdentity ? true : false;
        return view('admin.sound_cloud.index',compact('title','isGating'));
    }
    public function getScUrl(Request $request) {
        $userId = $request->get('user_id');
        $oauthInfo = \App\OauthIdentity::where('provider', 'soundcloud')->where('user_id',$userId)->first();

        $token = $oauthInfo->access_token;
        $id = $oauthInfo->provider_user_id;
        $clientId = env('SOUNDCLOUD_KEY');
        $clientSecret = env('SOUNDCLOUD_SECRET');
        $redirectUrl = env('SOUNDCLOUD_REDIRECT_URI');

        $facade = new SoundcloudFacade($clientId, $clientSecret, $redirectUrl);

        $response  = $facade->get('/users/'.$id, array())->asJson()->request();
        $encodedJson = json_decode($response->bodyRaw());
        $permalink_url = $encodedJson->permalink_url;

        return response()->json(['soundcloud_url' => $permalink_url]);
    }
    function connectSoundcloud(){
        session(['socialite-redirect' => '/admin/soundcloud-gating']);
        return redirect('admin/connect/soundcloud');
    }

    public function redirectToProvider($provider)
    {
      session(['socialite-redirect' => '/admin/soundcloud-gating']);
      return Socialite::driver($provider)->redirect();
    }

    function removeProviderToken($provider) {
      $user = \Auth::user();
      if($user) {
        \App\OauthIdentity::where('provider', $provider)->where('user_id', $user->id)->delete();
        return response()->json(['success'=>'success'],200);
      } else {
        return response()->json(['error'=>'user not found'],401);
      }
    }    
}