<?php

namespace App\Http\Controllers\FrontWeb;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Socialite;

class SocialAuthController extends BaseController
{
    public function redirectToSpotifyProvider($provider) {
        session(['socialite-redirect' => '/gating']);

        $scopes = array('user-follow-modify', 'playlist-modify-public', 'playlist-modify-private', 'user-library-modify');
        Socialite::driver($provider)->scopes($scopes);

        return Socialite::driver($provider)->redirect();
    }

    public function redirectToProvider($provider)
    {
      session(['socialite-redirect' => '/dashboard']);
      return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(Request $request, $provider)
    {

        if (session('socialite-redirect') == '/gating') {
            $status = 'error';
            $token = null;
            $user = null;
            
            if ($request->get('code')) {
                try {
                    $provider_user = Socialite::driver($provider)->user();
                    $token = $provider_user->token;
                    $user = json_encode($provider_user->user);

                    $status = 'success';
                } catch(\Exception $e) {}

                return '<!DOCTYPE html>
                        <html lang="en">
                           <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                             <title>Connect with Spotify</title>
                             <script type="text/javascript">
                                function redirectBack() {
                                    window.setTimeout(window.opener.spotifyApp.connectCallback("'.$status.'","'.(string) $token.'",'.( $user ? $user : '""').'), 500);
                                    self.close();
                                }
                                function closeManually(){
                                    var status ="'.$status.'";
                                    var userObject ='.( $user ? $user : '""').';
                                    var scToken = "'.(string)$token.'";
                                    window.opener.spotifyApp.connectCallback(status,scToken,userObject);
                                    self.close();
                                }
                             </script>
                           </head>
                            <body onload="redirectBack()">
                            <b style="width: 100%; text-align: center;">This popup should automatically close in a few seconds. <a href="#" onClick="closeManually();">Click here</a> to close manually.</b>
                            </body>
                        </html>';

            }
        } else {
            if($request->get('error') && $provider == "soundcloud") {
                if(session('socialite-redirect'))
                    return redirect(session('socialite-redirect'));
                return redirect('/');
            }

            try {
                $provider_user  = Socialite::driver($provider)->user();
            } catch(\Exception $e) {
                if(session('socialite-redirect'))
                    return redirect(session('socialite-redirect'));
                return redirect('/');
            }

            $provider_user_id = $provider_user->id;
            $token_secret = null;
            $nickName = '';

            if($provider =="instagram"){
                $provider_user_id = $provider_user->id;
                $token_secret = '';
                $nickName = $provider_user->user && $provider_user->user['username'] ? $provider_user->user['username'] : '';
            }

            if($provider =="twitter"){
                $provider_user_id = $provider_user->user['id_str'];
                $token_secret = $provider_user->tokenSecret ? $provider_user->tokenSecret : '';
                $nickName = $provider_user->nickname ? $provider_user->nickname : '';
            }

            if ($provider == 'spotify') {
                $token_secret = $provider_user->refreshToken ? $provider_user->refreshToken : '';
            }

            session(['socialite-user' => $provider_user]);

            if(\Auth::check()) {
                $oauth_identity = \App\OauthIdentity::firstOrNew([ 'provider' => $provider,
                    'provider_user_id' => $provider_user_id,
                    'user_id' => \Auth::user()->id
                ]);

                $oauth_identity->access_token = $provider_user->token;
                $oauth_identity->token_secret = $token_secret;
                $oauth_identity->nick_name = $nickName;

                $oauth_identity->save();

                if($provider == "soundcloud"){
                    $oauthInfo = \App\OauthIdentity::where('provider', 'soundcloud')->where('user_id',\Auth::user()->id)->first();
                    if($oauthInfo){
                        //????? delete old tracks from soundcloud track table.
                        $soundCloudTracks = \App\libraries\SoundCloudHelper::GetScArtistTracks($oauthInfo,\Auth::user()->id);
                    }
                }

                return redirect(session('socialite-redirect'));
            } else {
                return redirect(session('socialite-redirect'));
            }
        }
      
    }

     // Login with facebook
    public function fbLogin()
    {
      session(['socialite-redirect' => 'facebook/callback']);
      return Socialite::driver('facebook')->redirect();   
    }   

    // callback for login with facebook
    public function fbLoginCallback()
    {
        // when facebook call us a with token  
        $fbuser = session('socialite-user');
        \Session::forget('socialite-user'); // forget socialite session

        if($fbuser) {        
          $user = \App\User::where('email',$fbuser->email)->first(); //->where('provider','facebook')
          
          if($user){
            \Auth::login($user, true);
            if($user->user_type == 'artist')
            return redirect()->to('/dashboard');
            if($user->user_type == 'stm_user')
            return redirect()->to('/home');
          }

          // If new registration then there is need to define user type "user / artist". To get this information from user we need to redirect to front ui and make choices available to user "select user type" and after that signup process will be completed.
          \Session::put('fbdata', $fbuser);
        }
        return redirect()->to('/facebook-redirect');
    }

    // registration with facebook
    public function fbRegister(Request $request)
    {

        $type = $request->get('user_type') ;
        $value = \Session::get('fbdata');

        $user = new \App\User;

        $user->name = $value->name;
        $user->email = $value->email;
        $user->provider_user_id = $value->id;
        $user->provider = 'facebook';
        $user->user_type = $type;
        $user->status = 1;
        $user->save();

        $destinationPath = public_path('uploads/artist/profiles/');

        $extension = pathinfo($value->avatar,PATHINFO_EXTENSION);
        
        // $filename= strtolower(rand(11111,99999)).'.jpg';
        // $file = file_get_contents($value->avatar);
        // $save = file_put_contents($destinationPath.$filename , $file);
        
        if($type == 'artist'){
          $artist_profile = new \App\ArtistsProfile;
          $artist_profile->user_id = $user->id;
          $artist_profile->name = $user->name;
          $artist_profile->slug = $user->slug;
          $artist_profile->save();
        }

        if($type == 'stm_user'){
          $user_profile = new \App\UserProfile;
          $user_profile->user_id = $user->id;
          $user_profile->name = $user->name;
          $user_profile->slug = $user->slug;
          $user_profile->save();
        }

        // $user->avatar = $filename;
        $user->save();
        $releaseTracks = new \App\ReleaseTracks;

        $releaseTracks->user_id = $user->id;
        $releaseTracks->save();

        if($user){
            \Auth::login($user, true);
            \Session::forget('fbdata');
            // $request->session()->forget('fbdata');
            return response()->json(['status'=>'success', 'data' =>$user],200);
        }
        // return $this->res->json($user, Response::HTTP_OK);
    }

    function removeProviderToken($provider) {
      $user = \Auth::user();
      if($user) {
        \App\OauthIdentity::where('provider', $provider)->where('user_id', $user->id)->delete();
        return response()->json(['status'=>'success'],200);
      } else {
        return response()->json(['error'=>'user not found'],422);
      }
    }

}
