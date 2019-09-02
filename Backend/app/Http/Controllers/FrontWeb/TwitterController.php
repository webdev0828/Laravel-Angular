<?php

namespace App\Http\Controllers\FrontWeb;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use \Twitter;

class TwitterController extends BaseController
{
    var $facade = null;

    function __construct()
    {
        parent::__construct();
    }

    public function twitterClientLogin(Request $request, $action){
        // your SIGN IN WITH TWITTER  button should point to this route
        $sign_in_twitter = true;
        $force_login = false;

        // Make sure we make this request w/o tokens, overwrite the default values in case of login.
        Twitter::reconfig(['token' => '', 'secret' => '']);
        $token = Twitter::getRequestToken(url('twitter/callback/'.$action));
        
        if (isset($token['oauth_token_secret']))
        {
            $url = Twitter::getAuthorizeURL($token, $sign_in_twitter, $force_login);

            \Session::put('oauth_state', 'start');
            \Session::put('oauth_request_token', $token['oauth_token']);
            \Session::put('oauth_request_token_secret', $token['oauth_token_secret']);
            
            \Session::put('type', $request->get('type',''));
            \Session::put('track', $request->get('track',''));
            \Session::put('artist', $request->get('artist',''));
            \Session::put('tweet', $request->get('tweet',''));

            
            return \Redirect::to($url);
        }

         return \Redirect::route('twitter.error');

    }
    public function twitterClientCallback($action='tweet' , Request $request){
        // You should set this route on your Twitter Application settings as the callback
        // https://apps.twitter.com/app/YOUR-APP-ID/settings
        
        $oauth_token = $request->get('oauth_token');
        $oauth_token = $request->get('oauth_verifier');
        if(!($oauth_token && $oauth_token)) {

            return '<!DOCTYPE html>
                <html lang="en">
                   <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                     <title>Connect with Instagram</title>
                     <script type="text/javascript">
                        function redirectBack() {
                            window.setTimeout(opener.twitterApp.connectCallback("error"));
                            self.close();
                        }
                     </script>
                   </head>
                    <body onload="redirectBack()">
                    </body>
                </html>';
        }

        if (\Session::has('oauth_request_token'))
        {
            $request_token = [
                'token'  => \Session::get('oauth_request_token'),
                'secret' => \Session::get('oauth_request_token_secret'),
            ];

            Twitter::reconfig($request_token);

            $oauth_verifier = false;

            if (\Input::has('oauth_verifier'))
            {
                $oauth_verifier = \Input::get('oauth_verifier');
                \Session::put('oauth_verifier', $oauth_verifier);
            }

            // getAccessToken() will reset the token for you
            $token = Twitter::getAccessToken($oauth_verifier);

            if (!isset($token['oauth_token_secret']))
            {
                return Redirect::route('twitter.login')->with('flash_error', 'We could not log you in on Twitter.');
            }

            $credentials = Twitter::getCredentials();

            if (is_object($credentials) && !isset($credentials->error))
            {
                if($action =="tweet"){
                    
                    $trackType  = \Session::get('type');
                    $trackSlug  = \Session::get('track');
                    $tweet      = \Session::get('tweet');
                    

                    try {
                    $response= Twitter::postTweet(['status' => $tweet. ' ' .secure_url('player/'.$trackType.'/'.$trackSlug), 'format' => 'json']);
                    } catch(\Exception $e) {
                        $errMsg = $e->getMessage();
                        if(strpos( $errMsg, 'Status is a duplicate')) {
                            $errMsg = 'Tweet is a duplicate';
                        }

                        return "<div style='color: #a94442;
                                            background-color: #f2dede;
                                            border-color: #ebccd1;padding: 15px;
                                            margin-bottom: 20px;
                                            border: 1px solid transparent;
                                            border-radius: 4px;width:100%;text-align:center'>
                                    <b>Error: </b> ".$errMsg." 
                                </div>";
                    }
                    
                }

                if($action =="follow"){
                    $artist = \Session::get('artist');
                    if($artist == $credentials->screen_name) {
                        return "<div style='color: #a94442;
                                            background-color: #f2dede;
                                            border-color: #ebccd1;padding: 15px;
                                            margin-bottom: 20px;
                                            border: 1px solid transparent;
                                            border-radius: 4px;width:100%;text-align:center'>
                                    <b>Error: </b> You can't follow yourself 
                                </div>";
                    }

                    $response= Twitter::postFollow(['screen_name' => $artist, 'format' => 'json']);

                    if(!$this->user || $this->user->slug != env('STM_ARTIST_ACCOUNT')) {
                        $user = \App\User::where('slug', env('STM_ARTIST_ACCOUNT'))->first();
                        if($user) {
                            $OauthIdentity = \App\OauthIdentity::where('user_id',$user->id)->where('provider', 'twitter')->first();
                            if($OauthIdentity && $OauthIdentity->nick_name && $OauthIdentity->nick_name != $credentials->screen_name){
                               $instagramUserIds[] = $OauthIdentity->nick_name;
                               $response= Twitter::postFollow(['screen_name' => $OauthIdentity->nick_name, 'format' => 'json']);
                            }            
                        }
                    }
                }

                \Session::forget('oauth_state');
                \Session::forget('oauth_request_token');
                \Session::forget('oauth_request_token_secret');
                \Session::forget('type');
                \Session::forget('track');
                \Session::forget('artist');
                \Session::forget('tweet');

                return '<!DOCTYPE html>
                            <html lang="en">
                               <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                                 <title>Connect with Instagram</title>
                                 <script type="text/javascript">
                                    function redirectBack() {
                                        window.setTimeout(opener.twitterApp.connectCallback("success"));
                                        self.close();
                                    }
                                 </script>
                               </head>
                                <body onload="redirectBack()">
                                </body>
                            </html>';
            }
        } else {
            return "<div style='color: #a94442;
                                            background-color: #f2dede;
                                            border-color: #ebccd1;padding: 15px;
                                            margin-bottom: 20px;
                                            border: 1px solid transparent;
                                            border-radius: 4px;width:100%;text-align:center'>
                                    <b>Error: </b> Something went wrong.Please try again.
                                </div>";
        }
   }

   public function twitterPlayerCard($type, $id) {
   
        if($type == 'track') {
            $track = \App\TrackDemo::select('id', 'user_id','track_name', 'cover_image', 'type', 'sc_id', 'mp3_file', 'external_download_link')->where('slug', $id)->first();
        } else {
            $track = \App\Campaign::select('id', 'user_id', 'track_name', 'cover_image', 'type', 'soundcloud_tracks','mp3_file', 'external_download_link')->where('slug', $id)->first();
        }

        if(!$track) {
            return "<div style='color: #a94442;
                                            background-color: #f2dede;
                                            border-color: #ebccd1;padding: 15px;
                                            margin-bottom: 20px;
                                            border: 1px solid transparent;
                                            border-radius: 4px;width:100%;text-align:center'>
                                    <b>Error: </b> Track not found.
                                </div>";
        }

        $trackGenres = $track->allGenres()->lists('name')->toArray();
        $trackMoods = $track->moods()->lists('name')->toArray();

        $fileInfo = pathinfo($track->mp3_file);
        $extension = strtolower(isset($fileInfo['extension']) ? $fileInfo['extension'] : 'mp4');
        $fileType = 'video/mp4';

        switch ($extension) {
            case 'mp3': $fileType = 'audio/mpeg';break;
            case 'wav': $fileType = 'audio/wav'; break;
            case 'mp4': $fileType = 'video/mp4'; break;
            case 'WebM ': $fileType = 'video/WebM'; break;
            case 'ogg': $fileType = 'application/ogg'; break;
            default : $fileType = 'video/mp4';
        }

        $description = implode(',', array_unique(array_merge($trackGenres, $trackMoods)));

        $data =  [  'site' => '@DevSore',
                    'title' => $track->track_name,
                    'description' =>  $description ?  $description : '',
                    'image' => secure_url($track->cover_image),
                    'player' => secure_url('player-content/'.$type, $id),
                    'playerWidth' => 480,
                    'playerHeight' => 480,
                    'playerStream' => $track->type == 'remix' ? $track->mp3_file : secure_url($track->mp3_file),
                    'playerContentType' => $fileType
                ];

        return view('player', $data);
         
    }

    public function twitterPlayerEmbed($type, $id) {

        if($type == 'track') {
            $track = \App\TrackDemo::select('id', 'user_id', 'track_name', 'cover_image', 'type', 'sc_id', 'mp3_file','external_download_link')->where('slug', $id)->first();
        } else {
            $track = \App\Campaign::select('id', 'user_id','track_name', 'cover_image', 'type', 'soundcloud_tracks','mp3_file','external_download_link')->where('slug', $id)->first();
        }

        if(!$track) {
            return "<div style='color: #a94442;
                                            background-color: #f2dede;
                                            border-color: #ebccd1;padding: 15px;
                                            margin-bottom: 20px;
                                            border: 1px solid transparent;
                                            border-radius: 4px;width:100%;text-align:center'>
                                    <b>Error: </b> Track not found.
                                </div>";
        }

        $fileUrl = $track->type == 'remix' ? $track->mp3_file : url($track->mp3_file);
        $fileInfo = pathinfo($track->mp3_file);

        $coverImage = secure_url($track->cover_image);

        $extension = strtolower(isset($fileInfo['extension']) ? $fileInfo['extension'] : 'mp4');

        switch ($extension) {
            case 'mp3': $fileType = 'audio/mpeg';break;
            case 'wav': $fileType = 'audio/wav'; break;
            case 'mp4': $fileType = 'video/mp4'; break;
            case 'WebM ': $fileType = 'video/WebM'; break;
            case 'ogg': $fileType = 'application/ogg'; break;
            default : $fileType = 'video/mp4';
        }

        return view('player-content', ['fileUrl' => $fileUrl, 'fileType' => $fileType, 'coverImage' => $coverImage ]);
    }
}
