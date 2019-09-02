<?php

namespace App\Http\Controllers\FrontWeb;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Njasm\Soundcloud\SoundcloudFacade;
// or soundcloud if you don't need a facade for specific tasks
use Njasm\Soundcloud\Soundcloud;
use \Mailjet\Resources;
use Session;

class GatingController extends BaseController
{
    var $facade = null;
    function __construct()
    {
        parent::__construct();

        $clientId = env('GATING_SOUNDCLOUD_KEY');
        $clientSecret = env('GATING_SOUNDCLOUD_SECRET');
        // $redirectUrl = env('GATING_SOUNDCLOUD_REDIRECT_URI');
        $redirectUrl = url('/'). '/'.env('GATING_SOUNDCLOUD_REDIRECT_ROUTE');//env('GATING_SOUNDCLOUD_REDIRECT_URI');

        $this->facade = new SoundcloudFacade($clientId, $clientSecret, $redirectUrl);

        // $clientId = 'c26eda2e655a55f499189136255b9314';
        // $clientSecret = '56c8ce4585e5662694bcf7c550ac4827';
        // $redirectUrl = url('sc/callback');
        
        // $this->facade = new SoundcloudFacade($clientId, $clientSecret, $redirectUrl);

    }
    public function instaFollow(Request $request){
        
        $instagramUserIds = [];

        $instagramUserToken = $request->input('access_token');
        $instagramUserId = $request->input('instagram_user_id');

        if($instagramUserId) {
             $instagramUserIds[] = $instagramUserId;
        }

        if(!$this->user || $this->user->slug != env('STM_ARTIST_ACCOUNT')) {
            $user = \App\User::where('slug', env('STM_ARTIST_ACCOUNT'))->first();
            if($user) {
                $OauthIdentity = \App\OauthIdentity::where('user_id',$user->id)->where('provider', 'instagram')->first();

                if($OauthIdentity &&  $instagramUserId != $OauthIdentity->provider_user_id){
                   $instagramUserIds[] = $OauthIdentity->provider_user_id;
                }            
            }
        }

        if(!empty($instagramUserIds)) {
            try {
                foreach ($instagramUserIds as $instagramUserId) {
                    $ch = curl_init();
                    // set URL and other appropriate options
                    curl_setopt($ch, CURLOPT_URL,"https://api.instagram.com/v1/users/".$instagramUserId."/relationship?access_token=".$instagramUserToken);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($ch, CURLOPT_POST, TRUE);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, 'action=follow');
                    // grab URL and pass it to the browser
                    $result = curl_exec($ch);
                    // close cURL resource, and free up system resources
                    curl_close($ch);
                    // print_r($result);
                    // "meta": {"code": 200}, "data": {"outgoing_status": "follows", "target_user_is_private": false, "incoming_status": "none"}}
                }
                return response()->json(['status'=>'success','data'=>$result,'code'=>'200'],200);
            } catch( \Exception $e){
                return response()->json(['status'=>'error','message'=>'Something went wrong.Please try again.','code'=>'500'],422);
            }
        }
        else{
            return response()->json(['status'=>'error','message'=>'Something went wrong.Please try again.','code'=>'500'],422);
        }
       
    }
    // public function instaLoad(){
    //     if(intval(isset($_GET['error']))) {
    //         echo '<script>';
    //             self.close();
    //         echo '</script>';
    //     }
    // }
    // public function sendMail(){
   
    //     // use your saved credentials
    //     $mj = new \Mailjet\Client('203dfa22e16795cf18d5548d390e0520', '5120d09e58df31abcff37c9051cc69df');


    //     $body = [
    //         'FromEmail' => "james@sorethumbmedia.co.uk",
    //         'FromName' => "STM",
    //         'Subject' => "Your email flight plan!",
    //         'Text-part' => "Dear passenger, welcome to Mailjet! May the delivery force be with you!",
    //         'Html-part' => "<h3>Dear passenger, welcome to Mailjet!</h3><br />May the delivery force be with you!",
    //         'Recipients' => [['Email' => "sandy@code23.com"]]
    //     ];

    //     $response = $mj->post(Resources::$Email, ['body' => $body]);

    //     dd($response);
      

    // }

    // public function index()
    // {
    //     return view('gate.demo',compact('title','artistList','mainTitle'));
    // }

    public function processGate()
    {
        return view('gate.callback',compact('title','artistList','mainTitle'));
    }

    public function instagramProcess() {
        return view('gate.instagram-callback',compact('title','artistList','mainTitle'));
    }

    // ########## Youtube gating ##############
    public function youtubeSubscribe(Request $request) {
        
        $OAUTH2_CLIENT_ID = env('YOUTUBE_KEY');
        $OAUTH2_CLIENT_SECRET = env('YOUTUBE_SECRET');

        $client = new \Google_Client();
        $client->setClientId($OAUTH2_CLIENT_ID);
        $client->setClientSecret($OAUTH2_CLIENT_SECRET);
        $client->setScopes('https://www.googleapis.com/auth/youtube');
        $redirect = url('youtube/process'); //filter_var('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'], FILTER_SANITIZE_URL);
        $client->setRedirectUri($redirect);

        // Define an object that will be used to make all API requests.
        $youtube = new \Google_Service_YouTube($client);

        $errMsgHtml = "<div style='color: #a94442;
                                        background-color: #f2dede;
                                        border-color: #ebccd1;padding: 15px;
                                        margin-bottom: 20px;
                                        border: 1px solid transparent;
                                        border-radius: 4px;width:100%%;text-align:center'>
                                <b>Error: </b> %s 
                            </div>";
        
        if ($request->has('code')) {
            if (strval(Session::get('yt_state')) !== strval($request->get('state'))) {
                return  sprintf($errMsgHtml,'The session state did not match');
            }

            $client->authenticate($request->get('code'));
            Session::put('yt_token', $client->getAccessToken());
          
            return redirect($redirect);
        } else {
            if($request->has('youtube_channel')) {
                Session::put('youtube_channel', $request->get('youtube_channel'));  
            }
        }

        if(Session::has('yt_token')) {
            $client->setAccessToken(Session::get('yt_token'));
        }

        // Check to ensure that the access token was successfully acquired.
        if ($client->getAccessToken()) {

            try {
                $youtubeChannel = Session::get('youtube_channel');

                if(!$youtubeChannel) {
                    return "<div style='color: #a94442;
                                        background-color: #f2dede;
                                        border-color: #ebccd1;padding: 15px;
                                        margin-bottom: 20px;
                                        border: 1px solid transparent;
                                        border-radius: 4px;width:100%;text-align:center'>
                                <b>Error: </b> Youtube channel not found 
                            </div>";
                }

                $youtubeChannels = [$youtubeChannel];

                if(!$this->user || $this->user->slug != env('STM_ARTIST_ACCOUNT')) {
                    $user = \App\User::where('slug', env('STM_ARTIST_ACCOUNT'))->first();
                    if($user) {
                        $stmArtist = \App\ArtistsProfile::where('user_id', $user->id)->first();
                        if($stmArtist && $stmArtist->youtube_channel && $stmArtist->youtube_channel != $youtubeChannel){
                           $youtubeChannels[] = $stmArtist->youtube_channel;
                        }            
                    }
                }

                    // echo '<pre>'; print_r($youtubeChannel); 
                    // This code subscribes the authenticated user to the specified channel.
                    // Identify the resource being subscribed to by specifying its channel ID
                    // and kind.
                    // Execute the request and return an object containing information
                    // about the new subscription.
                
                foreach($youtubeChannels as $youtubeChannel) {
                    $resourceId = new \Google_Service_YouTube_ResourceId();
                    $resourceId->setChannelId($youtubeChannel);
                    $resourceId->setKind('youtube#channel');

                    // Create a snippet object and set its resource ID.
                    $subscriptionSnippet = new \Google_Service_YouTube_SubscriptionSnippet();
                    $subscriptionSnippet->setResourceId($resourceId);

                    // Create a subscription request that contains the snippet object.
                    $subscription = new \Google_Service_YouTube_Subscription();
                    $subscription->setSnippet($subscriptionSnippet);
                
                    $subscriptionResponse = $youtube->subscriptions->insert('id,snippet',
                    $subscription, array());

                }

                // $htmlBody .= "<h3>Subscription</h3><ul>";
                // $htmlBody .= sprintf('<li>%s (%s)</li>', $subscriptionResponse['snippet']['title'], $subscriptionResponse['id']); 
                // $htmlBody .= '</ul>';


                Session::forget('yt_token');
                Session::forget('yt_state');
                Session::forget('youtube_channel');

                return '<!DOCTYPE html>
                                <html lang="en">
                                   <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                                     <title>Connect with Youtube</title>
                                     <script type="text/javascript">
                                        function redirectBack() {
                                            window.setTimeout(opener.youtubeApp.connectCallback());
                                            self.close();
                                        }
                                     </script>
                                   </head>
                                    <body onload="redirectBack()">
                                    </body>
                                </html>';

            } catch (\Google_Service_Exception $e) {
                return  sprintf($errMsgHtml, '<p><code>'.htmlspecialchars($e->getMessage()).'</code></p>' );
                return "<div style='color: #a94442;
                                        background-color: #f2dede;
                                        border-color: #ebccd1;padding: 15px;
                                        margin-bottom: 20px;
                                        border: 1px solid transparent;
                                        border-radius: 4px;width:100%;text-align:center'>
                                <b>Error: </b> Youtube channel not found 
                            </div>";
            } catch (Google_Exception $e) {
                return  sprintf($errMsgHtml, '<p><code>'.htmlspecialchars($e->getMessage()).'</code></p>' );
                return "<div style='color: #a94442;
                                        background-color: #f2dede;
                                        border-color: #ebccd1;padding: 15px;
                                        margin-bottom: 20px;
                                        border: 1px solid transparent;
                                        border-radius: 4px;width:100%;text-align:center'>
                                <b>Error: </b> Youtube channel not found 
                            </div>";
            }

            // Session::put('token', $client->getAccessToken());

        } else {
            // If the user has not authorized the application, start the OAuth 2.0 flow.
            $state = mt_rand();
            $client->setState($state);
            Session::put('yt_state', $state);

            $authUrl = $client->createAuthUrl();
            // $htmlBody = '<h3>Authorization Required</h3>
            // <p>You need to <a href="'.$authUrl.'">authorize access</a> before proceeding.<p>
            // ';
            return redirect($authUrl);
        }
    }

    // ########## FACEBOOK gating ##############

    public function fbConnect(\SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb)
    {
        $login_link = $fb->getRedirectLoginHelper()->getLoginUrl(url('fb/callback'), ['email','publish_actions']);
        return redirect($login_link);
    }

    public function fbConnectCallback(\SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb) {

        // Obtain an access token.
        try {
            $token = $fb->getAccessTokenFromRedirect();
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            dd($e->getMessage());
        }

        // Access token will be null if the user denied the request
        // or if someone just hit this URL outside of the OAuth flow.
        if (! $token) {
            // Get the redirect helper
            $helper = $fb->getRedirectLoginHelper();

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
            $oauth_client = $fb->getOAuth2Client();

            // Extend the access token.
            try {
                $token = $oauth_client->getLongLivedAccessToken($token);
            } catch (\Facebook\Exceptions\FacebookSDKException $e) {
                dd($e->getMessage());
            }
        }
        // Save for later
        Session::put('fb_user_access_token', (string) $token);

        
        return '<!DOCTYPE html>
                <html lang="en">
                   <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                     <title>Connect with Facebook</title>
                     <script type="text/javascript">
                        function redirectBack() {
                            window.setTimeout(opener.facebookApp.connectCallback(\''.(string) $token.'\'),10);
                            self.close();
                        }
                     </script>
                   </head>
                    <body onload="redirectBack()">
                    <b style="width: 100%; text-align: center;">This popup should automatically close in a few seconds</b>
                    </body>
                </html>';

    }

    public function fbLike(\SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb, Request $request) {

        $token =  $request->get('fb_token');
        
        if(!$token) {
            return response()->json('Unauthorized access');
        }

        $fb->setDefaultAccessToken($token);

        
        // Facebook share post and like it
        $linkData = [
            'link' => secure_url('/fb/player') .'/' .$request->get('track_type') .  '/'.$request->get('track_slug')
            // 'link' => $request->get('post_link'), 
            // 'name' => $request->get('caption','Sore Thumb Media music'),
            // 'message' => 'STM music',
            // 'picture' => $request->get('picture', 'http://app.sorethumbmedia.co.uk/images/logo_crop.jpg'),
            // 'caption' => 'Sore Thumb Media music',
            // 'description'   => $request->get('description', 'Sore Thumb Media is a quality controlled, promotional platform aimed at producers of all genres in electronic music.'),
            // 'url'  => 'http://app.sorethumbmedia.co.uk/track/sample-track',
            // 'source' => 'http://app.sorethumbmedia.co.uk/uploads/campaign/music-files/02082016115406.mp3',
            // 'attachment' => [
            //                     "media" => [
            //                                     'type' => 'mp3',
            //                                     "src" => 'http://app.sorethumbmedia.co.uk/uploads/campaign/music-files/02082016115406.mp3', 
            //                                     "title" => "STM", 
            //                                     "artist" => "STM", 
            //                                     "album" => "STM"
            //                                 ]
            //                 ]
        ];

        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->post('/me/feed', $linkData, $token);
            $responseData = json_decode($response->getBody());

            $response = $fb->post('/'.$responseData->id.'/likes');            
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            return response()->json(['error' => $e->getMessage()]);
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        /*  //facebook like
        $data = [
            'profile' => (string)$request->get('fb_user')   //'330722220650287'
        ];

        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->post('/me/og.follows', $data, $token);
        
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            return response()->json(['error' => $e->getMessage()]);
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
        */
        
        return response()->json(['success' => 'success']);
    }

    // public function fbShare(\SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb, Request $request) {

    //     // $token =  $request->get('fb_token');
    //     $token = Session::get('fb_user_access_token');

    //     if(!$token) {
    //         return response()->json(['error' => 'Unauthorized access']);
    //     }

    //     $fb->setDefaultAccessToken($token);
        
    //     $linkData = [
    //         'link' => 'http://app.sorethumbmedia.co.uk/track/sample-track', // $request->get('share_link')  //'http://app.sorethumbmedia.co.uk',
    //         'message' => 'STM music',
    //         'picture' => 'http://app.sorethumbmedia.co.uk/images/logo_crop.jpg',
    //         'caption' => 'Sore Thumb Media',
    //         'description'   => 'Sore Thumb Media is a quality controlled, promotional platform aimed at producers of all genres in electronic music.',
    //         // 'url'  => 'http://app.sorethumbmedia.co.uk/track/sample-track',
    //         'attachment' => [
    //                             "media" => [
    //                                             'type' => 'mp3',
    //                                             "src" => 'http://app.sorethumbmedia.co.uk/uploads/campaign/music-files/02082016115406.mp3', 
    //                                             "title" => "STM", 
    //                                             "artist" => "STM", 
    //                                             "album" => "STM"
    //                                         ]
    //                         ]
    //     ];

    //     try {
    //         // Returns a `Facebook\FacebookResponse` object
    //         $response = $fb->post('/me/feed', $linkData, $token);

    //         // 264209647301545_338665873189255
    //         $responseData = json_decode($response->getBody());

    //         $response = $fb->post('/'.$responseData->id.'/likes');            
    //     } catch(\Facebook\Exceptions\FacebookResponseException $e) {
    //         return response()->json(['error' => 'Graph returned an error: ' . $e->getMessage()]);
    //     } catch(\Facebook\Exceptions\FacebookSDKException $e) {
    //         return response()->json(['error' => 'Facebook SDK returned an error: ' . $e->getMessage()]);
    //     }
        
    //     return response()->json(['success' => 'success']);
    // }

    // public function fbGetUser(\SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb) {
    //     $token = Session::get('fb_user_access_token');
    //     $fb->setDefaultAccessToken($token);


    //     try {
            
    //         $response = $fb->get('me/og.follows');
    //     } catch (\Facebook\Exceptions\FacebookSDKException $e) {
    //         dd($e->getMessage());
    //     }

    //     // Convert the response to a `Facebook/GraphNodes/GraphUser` collection
    //     // $facebook_user = $response->getGraphUser();
    //     echo '<pre>'; print_r( $response); exit;
    //     return response()->json(['success' => 'success', 'data' => $facebook_user]);

    //     // try {
    //     //     $response = $fb->get('/me?fields=id,name,email');
    //     // } catch (\Facebook\Exceptions\FacebookSDKException $e) {
    //     //     dd($e->getMessage());
    //     // }

    //     // // Convert the response to a `Facebook/GraphNodes/GraphUser` collection
    //     // $facebook_user = $response->getGraphUser();
    //     // return response()->json(['success' => 'success', 'data' => $facebook_user]);
    // }

    // public function fbUnfollowUser(\SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb){
    //     // 155144558259812

    //     $token = Session::get('fb_user_access_token');
    //     $fb->setDefaultAccessToken($token);


    //     try {
            
    //         $response = $fb->delete('155362614904673');
    //     } catch (\Facebook\Exceptions\FacebookSDKException $e) {
    //         dd($e->getMessage());
    //     }

    //     // Convert the response to a `Facebook/GraphNodes/GraphUser` collection
    //     // $facebook_user = $response->getGraphUser();
    //     echo '<pre>'; print_r( $response); exit;
    //     return response()->json(['success' => 'success', 'data' => $facebook_user]);
    // }


    // ########## soundcloud gating ##############
    public function scConnect() {
        // $clientId = 'c26eda2e655a55f499189136255b9314';
        // $clientSecret = '56c8ce4585e5662694bcf7c550ac4827';
        // $redirectUrl = url('sc/callback');
        
        // $this->facade = new SoundcloudFacade($clientId, $clientSecret, $redirectUrl);

        return redirect($this->facade->getAuthUrl());
    }

    public function scConnectCallback(Request $request) {
        
        $status = '';
        $token = null;
        $user = null;
        if($request->get('error')){
            $status = 'error';
        } else {

            $code = $request->get('code');
            if($code) {
                $response = json_decode($this->facade->codeForToken($code)->bodyRaw());
                $token =  $response && isset($response->access_token) ? $response->access_token : '';

                if($token) {
                    $user = json_encode(json_decode($this->facade->get('/me')->asJson()->request()->bodyRaw()));
                    //Session::put('sc_user_access_token', (string) $response->access_token );
                    $status = 'success';
                } else {
                    $status = 'error';
                }
            } else {
                $status = 'error';
            }    
        }
        

        return '<!DOCTYPE html>
                <html lang="en">
                   <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                     <title>Connect with Soundcloud</title>
                     <script type="text/javascript">
                        function redirectBack() {
                            window.setTimeout(window.opener.soundcloudApp.connectCallback("'.$status.'","'.(string) $token.'",'.( $user ? $user : '""').'),10);
                            self.close();
                        }
                        function closeManually(){
                            var status ="'.$status.'";
                            var userObject ='.( $user ? $user : '""').';
                            var scToken = "'.(string)$token.'";
                            window.opener.soundcloudApp.connectCallback(status,scToken,userObject);
                            self.close();
                        }
                     </script>
                   </head>
                    <body onload="redirectBack()">
                    <b style="width: 100%; text-align: center;">This popup should automatically close in a few seconds. <a href="#" onClick="closeManually();">Click here</a> to close manually.</b>
                    </body>
                </html>';
    }

    public function scArtistFollow(Request $request) {
        $accessToken = $request->get('token');
        $scArtistId = $request->get('artist_id');
        // $trackType = $request->get('track_type');
        $this->facade->setAccessToken($accessToken);
        try{
            if($scArtistId) {
                $response = $this->facade->put('/me/followings/'.$scArtistId)->asJson()->request();
                 return response()->json(['success' => 'success']);
            } else{
                return response()->json(['error' => 'No artist found for this track to follow']);
            }
        } catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        };
    }

    public function scFollow(Request $request) {
        $accessToken = $request->get('token');
        $artistId = $request->get('artist_id');
        $trackId = $request->get('track_id');
        $trackType = $request->get('track_type');

        $scArtists = [];
        // add artist soundcloud account
        if($artistId) {
            $scArtists[] = $artistId;
        }
        // add sorethumbmedia soundcloud account
        if(!$this->user || $this->user->slug != env('STM_ARTIST_ACCOUNT')) {
            $user = \App\User::where('slug', env('STM_ARTIST_ACCOUNT'))->first();
            if($user) {
                $OauthIdentity = \App\OauthIdentity::where('user_id',$user->id)->where('provider', 'soundcloud')->first();
                if($OauthIdentity && $OauthIdentity->provider_user_id != $artistId){
                   $scArtists[] = $OauthIdentity->provider_user_id;
                }
            }
        }
        // add campaign soundcloud artist links
        if($trackType == 'track') {
            $track = \App\TrackDemo::select('id', 'artist_links')->where('id', $trackId)->first();
        } else {
            $track = \App\Campaign::select('id', 'artist_links')->where('id', $trackId)->first();
        }
        $artistLinks = $track->artist_links ? json_decode($track->artist_links) : [];
        foreach($artistLinks as $linkObj) {
            if(isset($linkObj->id))
                $scArtists[] = $linkObj->id;
        }

        $this->facade->setAccessToken($accessToken);

        try{
            if( $scArtists && count($scArtists)) {
                foreach($scArtists as $scArtistId) {
                    $response = $this->facade->put('/me/followings/'.$scArtistId)->asJson()->request();
                }
                return response()->json(['success' => 'success']);
            } else{
                return response()->json(['error' => 'No artist found for this track to follow']);
            }
        } catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        };
        
    }

    public function scLike(Request $request) {

        $accessToken = $request->get('token');
        $trackId = $request->get('track_id');

        $this->facade->setAccessToken($accessToken);

        try{
            $response = $this->facade->put('/me/favorites/'.$trackId)->asJson()->request();
            return response()->json(['success' => 'success']);
        } catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        };
    }

    public function scComment(Request $request) {

        $accessToken = $request->get('token');
        $trackId = $request->get('track_id');
        $msg = $request->get('comment');

        $this->facade->setAccessToken($accessToken);
        
        try{
            // echo '<pre>'; print_r('/me/'.$trackId.'/comments'); exit;
            $response = $this->facade->post('/tracks/'.$trackId.'/comments', ['comment' => ['body' => $msg, 'timestamp' => rand(1500, 10000)]])->asJson()->request();

            return response()->json(['success' => 'success']);
        } catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        };
    }

     public function scRepost(Request $request) {
        $accessToken = $request->get('token');
        $trackId = $request->get('track_id');
        

        $this->facade->setAccessToken($accessToken);

        try{
            $response = $this->facade->put('/e1/me/track_reposts/'.$trackId)->asJson()->request();
            return response()->json(['success' => 'success']);
        } catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        };
    }

    public function fbPlayerEmbed($type, $id){

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

        $trackGenres = $track->allGenres()->lists('name')->toArray();
        $trackMoods = $track->moods()->lists('name')->toArray();
        $description = implode(',', array_unique(array_merge($trackGenres, $trackMoods)));

        
        $data = [   "og_title"          =>  $track->track_name,
                    "og_type"           =>  "music.song",
                    "og_image"          =>   secure_url($track->cover_image) ,
                    "og_description"    => $description ?  $description : '' ,
                    "og_video_url"      =>$track->type == 'remix' ? $track->mp3_file : secure_url($track->mp3_file),
                    "og_video_secure_url" => $track->type == 'remix' ? $track->mp3_file : secure_url($track->mp3_file),
                    "og_video_type"     =>"video/mp4",
                    "fb_app_id"         => env("FACEBOOK_CLIENT_ID")
                ];

        return view('fb-player', $data);
    }



}
