<?php

namespace App\Http\Controllers\FrontWeb;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Njasm\Soundcloud\SoundcloudFacade;
// or soundcloud if you don't need a facade for specific tasks
use Njasm\Soundcloud\Soundcloud;


class SoundCloudController extends BaseController
{
    var $facade = null;
    function __construct()
    {
        parent::__construct();

        $clientId = env('SOUNDCLOUD_KEY');
        $clientSecret = env('SOUNDCLOUD_SECRET');
        $redirectUrl = env('SOUNDCLOUD_REDIRECT_URI');

        $this->facade = new SoundcloudFacade($clientId, $clientSecret, $redirectUrl);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
         
       // $facade = new SoundcloudFacade('a0a6a244c38921e1498b93929c0bfa42', '7593933eeaf6f97d62ef58d0068b3ef7', 'http://localhost:96/sc/token');
        // $url = $this->facade->getAuthUrl();
        // echo '<a href="'.$url.'">Connect</a>';
        // // or inject your specific request params
        // $url = $facade->getAuthUrl(
        //     array(
        //         'response_type' => 'code',
        //         'scope' => '*',
        //         'state' => 'my_app_state_code'
        //     )
       // );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function GetScArtistTracks(Request $request)
    {
        $userId = $this->user->id; 
        $type = $request->get('type');
        $sc_id = $type == 'edit' ?  $request->get('sc_id') : null;

        $oauthInfo = \App\OauthIdentity::where('provider', 'soundcloud')->where('user_id',$userId)->first();

        if($oauthInfo){
            $soundCloudTracks = \App\libraries\SoundCloudHelper::GetScArtistTracks($oauthInfo,$userId);
            $campaignScIds = \App\Campaign::where('user_id',$userId)
                                           ->where('soundcloud_tracks','!=','')
                                           ->where('soundcloud_tracks','!=',$sc_id)
                                           ->lists('soundcloud_tracks');

            $soundCloudTracks = \App\SoundcloudArtist::where('artist_id',$userId)
                                                     ->where('sharing','public')
                                                     ->whereNotIn('sc_id',$campaignScIds)
                                                     ->get();
            return response()->json(['soundCloudTracks' => $soundCloudTracks]);
        }

        else{
            return response()->json(['status'=>'error', 'message'=> 'Please connect to SoundCloud '],422);
        }

    }

    public function GetScArtistRemixTracks()
    {
        $userId = $this->user->id; 

        $oauthInfo = \App\OauthIdentity::where('provider', 'soundcloud')->where('user_id',$userId)->first();

        if($oauthInfo){
            $soundCloudRemix = \App\libraries\SoundCloudHelper::GetScArtistTracks($oauthInfo,$userId);   
            $soundCloudRemix = \App\SoundcloudArtist::where('artist_id',$userId)
                                                    // ->where('downloadable',1)
                                                    // ->where('track_type','remix')
                                                    // ->where('sharing','private')
                                                    ->get();
            return response()->json(['soundCloudRemix' => $soundCloudRemix]);
        }

        else{
            return response()->json(['status'=>'error', 'message'=> 'Please connect to SoundCloud '],422);
        }

    }

    public static function GetScTracks(Request $request)
    {
        $token = $request->get('token');
        $id = $request->get('id');
        $clientId = env('SOUNDCLOUD_KEY');
        $clientSecret = env('SOUNDCLOUD_SECRET');
        $redirectUrl = env('SOUNDCLOUD_REDIRECT_URI');
        
        $facade = new SoundcloudFacade($clientId, $clientSecret, $redirectUrl);


        if($token){
            $accessToken = $token;
            $ScUserId = $id;
            $facade->setAccessToken($accessToken);
            $response  = $facade->get('/tracks', array('user_id' => $ScUserId))->asJson()->request();
            $encodedJson = json_decode($response->bodyRaw());
            $soundCloudTracksData = $encodedJson;
            // print_r($soundCloudTracksData);exit;
            $soundCloudTracks = [];
            if($soundCloudTracksData){
                foreach ($soundCloudTracksData as $tracks) {
                    $soundCloudTracks[] = $tracks;
                }
            }
            // print_r($soundCloudTracks);exit;
            return response()->json(['soundCloudTracks' => $soundCloudTracks]);
        }
    }

    public function getTrackFromUrl(Request $request) {
        $userId = $this->user->id;
        $oauthInfo = \App\OauthIdentity::where('provider', 'soundcloud')->where('user_id',$userId)->first();
        if($oauthInfo) {
            $clientId = env('SOUNDCLOUD_KEY');
            $clientSecret = env('SOUNDCLOUD_SECRET');
            $redirectUrl = env('SOUNDCLOUD_REDIRECT_URI');

            $facade = new SoundcloudFacade($clientId, $clientSecret, $redirectUrl);
            $facade->setAccessToken($oauthInfo->access_token);

            $response = $facade->get('/resolve', array('url' => $request->get('url')))->asJson()->request();
            $encodedJson = json_decode($response->bodyRaw());
            $location = $encodedJson->location;

            $response = $facade->get('/tracks/' . substr($location, strrpos($location, '/') + 1), array())->asJson()->request();
            $encodedJson = json_decode($response->bodyRaw());
            
            return response()->json(['mainInfo' => $encodedJson]);
        }
    }
}
