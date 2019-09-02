<?php namespace App\Http\Controllers\FrontWeb;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use GrahamCampbell\Dropbox\Facades\Dropbox;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\URL;
use Njasm\Soundcloud\SoundcloudFacade;
// or soundcloud if you don't need a facade for specific tasks
use Njasm\Soundcloud\Soundcloud;

use DB;

class DashboardController extends BaseController {

    function __construct()
    {
        // construct
        parent::__construct();
    }

	public function index() {

        $limit = 10; //$request->get('limit',10) ;
        $userId = $this->user->id;

        //tracks from sound-cloud
        $soundCloudTracks =[];// \App\SoundcloudArtist::where('artist_id',$userId)->get();
        $soundCloudTracks =\App\SoundcloudArtist::where('artist_id',$userId)->get();
        $campaigns = \App\Campaign::where('user_id', $userId)
                                    ->orderBy('created_at','desc')
                                    ->paginate($limit);

        $streamlines = \App\Streamline::where('user_id', $userId)
                                        ->orderBy('created_at', 'desc')
                                        ->paginate($limit);

        // $trackIds = \App\Campaign::lists('track_id');
        // echo '<pre>'; print_r($campaigns->toArray()); die;

        $demoTracks = \App\TrackDemo::select('id','track_name','status', 'user_id', 'type', 'repost_track_id','isSignupTrack')
                                    // ->whereNotIn('id', $trackIds)
                                    ->where('user_id',$userId)
                                    ->orderBy('created_at','desc')
                                    ->paginate($limit);
        return response()->json(['soundCloudTracks' => $soundCloudTracks, 'campaigns' => $campaigns, 'demoTracks' => $demoTracks, 'streamlines' => $streamlines]);
	}

    public function getCampaigns() {

        $limit = 10;
        $userId = $this->user->id;

        $campaigns = \App\Campaign::where('user_id', $userId)
                                    ->orderBy('created_at','desc')
                                    ->paginate($limit);

        return response()->json(['campaigns' => $campaigns]);
    }

    public function getRemixCampaigns() {
        $userId = $this->user->id;

        $campaigns = \App\Campaign::where('user_id', $userId)
            ->where('type', 'remix')
            ->orderBy('track_name','asc')
            ->get();

        return response()->json(['remixCampaigns' => $campaigns]);
    }

    public function getDemoTracks() {
        $limit = 10;
        $userId = $this->user->id;

        $demoTracks = \App\TrackDemo::select('id','track_name','status', 'user_id', 'type', 'repost_track_id')
                                    // ->whereNotIn('id', $trackIds)
                                    ->where('user_id',$userId)
                                    ->orderBy('created_at','desc')
                                    ->paginate($limit);
        return response()->json(['demoTracks' => $demoTracks]);
    }

    public function getStreamlines() {
        $limit = 10;
        $userId = $this->user->id;

        $streamlines = \App\Streamline::where('user_id', $userId)
                                        ->orderBy('created_at','desc')
                                        ->paginate($limit);
        return response()->json(['streamlines' => $streamlines]);
    }

    public function storeStreamline(Request $request) {
        $inputs = \Input::all();

        $social_terms = [
            'youtube' => $request->get('youtube') ? $request->get('youtube') : 'false',
            'soundcloud' => $request->get('soundcloud') ? $request->get('soundcloud') : 'false',
            'itune' => $request->get('itune') ? $request->get('itune') : 'false',
            'apple' => $request->get('apple') ? $request->get('apple') : 'false',
            'spotify' => $request->get('spotify') ? $request->get('spotify') : 'false',
            'googleplay' => $request->get('googleplay') ? $request->get('googleplay') : 'false',
            'tidal' => $request->get('tidal') ? $request->get('tidal') : 'false',
            'deezer' => $request->get('deezer') ? $request->get('deezer') : 'false',
            'beatport' => $request->get('beatport') ? $request->get('beatport') : 'false',
            'amazon' => $request->get('amazon') ? $request->get('amazon') : 'false',
            'amazonmp3' => $request->get('amazonmp3') ? $request->get('amazonmp3') : 'false',
            'bandcamp' => $request->get('bandcamp') ? $request->get('bandcamp') : 'false',
            'juno' => $request->get('juno') ? $request->get('juno') : 'false',
            'trackitdown' => $request->get('trackitdown') ? $request->get('trackitdown') : 'false',
            'stm' => $request->get('stm') ? $request->get('stm') : 'false',
            'custom' => $request->get('custom') ? $request->get('custom') : 'false',
        ];

        $data = [
            'track_link' => $request->get('track_link'),
            'artwork_link' => str_replace('large', 't500x500', $request->get('artwork_link')),
            'artist_name' => $request->get('artist_name'),
            'track_name' => $request->get('track_name'),
            'track_id' => $request->get('track_id'),
            'social_terms' => json_encode($social_terms),
            'yt_link' => $request->get('youtube') ? $request->get('yt_link') : '',
            'sc_link' => $request->get('soundcloud') ? $request->get('sc_link') : '',
            'itune_link' => $request->get('itune') ? $request->get('itune_link') : '',
            'apple_link' => $request->get('apple') ? $request->get('apple_link') : '',
            'sp_link' => $request->get('spotify') ? $request->get('sp_link') : '',
            'gp_link' => $request->get('googleplay') ? $request->get('gp_link') : '',
            'tidal_link' => $request->get('tidal') ? $request->get('tidal_link') : '',
            'deezer_link' => $request->get('deezer') ? $request->get('deezer_link') : '',
            'beatport_link' => $request->get('beatport') ? $request->get('beatport_link') : '',
            'amazon_link' => $request->get('amazon') ? $request->get('amazon_link') : '',
            'amazon_mp3_link' => $request->get('amazonmp3') ? $request->get('amazon_mp3_link') : '',
            'bandcamp_link' => $request->get('bandcamp') ? $request->get('bandcamp_link') : '',
            'juno_link' => $request->get('juno') ? $request->get('juno_link') : '',
            'trackitdown_link' => $request->get('trackitdown') ? $request->get('trackitdown_link') : '',
            'stm_track_id' => $request->get('stm') ? $request->get('stm_track_id') : null,
            'custom_title' => $request->get('custom') ? $request->get('custom_title') : '',
            'custom_link' => $request->get('custom') ? $request->get('custom_link') : '',
            'status' => $request->get('status') ? $request->get('status') : '0',
            'blur' => '1',
        ];

        if (!$request->get('custom')) {
            $data['custom_art_file'] = '';
        } else {
            if ($request->hasFile('custom_art_file')) {
                $file = $request->file('custom_art_file');
                $destinationPath = 'uploads/customSocial/logo';
                $fileName = \App\libraries\GlobalHelper::uploadFile($file, $destinationPath, $inputs['track_name'], "Background");
                $data['custom_art_file'] = $destinationPath.'/'.$fileName;
            } else if ($request->get('custom_art_file')) {
                $data['custom_art_file'] = $request->get('custom_art_file');
            }
        }

        if ($request->hasFile('background')) {
            $file = $request->file('background');
            $destinationPath = 'uploads/campaign/background';
            $fileName = \App\libraries\GlobalHelper::uploadFile($file, $destinationPath, $inputs['track_name'], "Background");
            $data['bg_file'] = $destinationPath.'/'.$fileName;
        } else if ($request->get('bg_file')) {
            $fileUrl = $request->get('bg_file');
            $data['bg_file'] = $fileUrl;
        }

        $user = $this->user;
        $streamline = \App\Streamline::findOrNew($request->get('id'));

        $streamline->fill($data);
        $streamline->user_id = $user->id;

        $streamline->save();

        $oauthInfo = \App\OauthIdentity::where('provider', 'soundcloud')->where('user_id', $this->user->id)->first();
        \App\libraries\SoundCloudHelper::setMetaDataForStreamline($oauthInfo, $request->get('track_id'), URL::to('/') . '/streamline/' .$streamline->slug);

        $streamlines = \App\Streamline::all();
        return response()->json(['success'=>'Streamline saved successfully.', 'streamlines'=>$streamlines]);
    }


	public function store(Request $request){

		$inputs = \Input::all();

        $spotify =  [ 'follow'	=> $request->get('spotifyFollow') ? $request->get('spotifyFollow') : 'false' ];

		$souncloud = [	'follow'	=> $request->get('soundcloudFollow') ? $request->get('soundcloudFollow') : 'false',
						'like' 		=> $request->get('soundcloudLike') ? $request->get('soundcloudLike') : 'false',
						'comment' 	=> $request->get('soundcloudComment') ? $request->get('soundcloudComment') : 'false',
						'repost' 	=> $request->get('soundcloudRepost') ? $request->get('soundcloudRepost') : 'false'
					];


		$facebook = [	'share'		=> $request->get('facebookShare') ? $request->get('facebookShare') : 'false',
						'like' 		=> $request->get('facebookLike') ? $request->get('facebookLike') : 'false'
					];

		$twitter =  [	'follow'	=> $request->get('twitterFollow') ? $request->get('twitterFollow') : 'false',
						'tweet' 	=> $request->get('twitterTweet') ? $request->get('twitterTweet') : 'false'
					];

		$youtube =  [	'subscribe'	=> $request->get('youtubeSubscribe') ? $request->get('youtubeSubscribe') : 'false'
					];

		$instagram =  [	'subscribe'	=> $request->get('instagramSubscribe') ? $request->get('instagramSubscribe') : 'false'
					  ];


		$data = [
					'track_name'  		=> $request->get('track_name') ? $request->get('track_name') :'' ,
                    'type'              => $request->get('type'),
                    // 'track_id'          => $request->get('track_id'),
                    'spotify_terms' 	=> json_encode($spotify),
					'souncloud_terms' 	=> json_encode($souncloud),
					'facebook_terms' 	=> json_encode($facebook),
					'twitter_terms' 	=> json_encode($twitter),
					'youtube_terms' 	=> json_encode($youtube),
					'instagram_terms' 	=> json_encode($instagram)
                ];

        $data['status'] = $request->get('status') ? $request->get('status') : '0';
        $data['blur'] = $request->get('blur') ? $request->get('blur') : '0';

        if($request->get('playlists_link')){
            $data['playlists_link'] = $request->get('playlists_link');
        }

        if($request->get('track_link')){
            $data['track_link'] = $request->get('track_link');
        }

        if($request->get('album_link')){
            $data['album_link'] = $request->get('album_link');
        }


        if($request->get('spotify_links')){
            // $links = explode(",", $request->get('links'));
            // $links = [  'links'    =>
            //             ['url' => $links ],
            //         ];
            $data['spotify_artist_links'] = $request->get('spotify_links');
            // $data['artist_links'] = $request->get('links');
        }

        if($request->get('links')){
            // $links = explode(",", $request->get('links'));
            // $links = [  'links'    =>
            //             ['url' => $links ],
            //         ];
            $data['artist_links'] = $request->get('links');
            // $data['artist_links'] = $request->get('links');
        }

        if($request->get('soundcloud_track')){
            $tracks =\App\SoundcloudArtist::where('sc_id',$request->get('soundcloud_track'))->where('artist_id',$this->user->id)->first();
            // $tracks = json_decode($request->get('tracks'));
            if($tracks) {
                if(!$request->hasFile('artwork') && !$request->get('cover_image') && $tracks && !$tracks->cover_image){
                    return response()->json(['status'=>'artwork_file','message'=>'Please select track artwork file'], 422);
                }
                /*if(!$request->get('external_link') && $tracks && $tracks->downloadable == '0'){
                    return response()->json(['status'=>'external_link','message'=>'Please enter external link'], 422);
                }*/
                $data['track_name'] = $tracks->track_name;
                $data['mp3_file'] = $tracks->url;
                $data['soundcloud_tracks'] = $tracks->sc_id;
                $data['download_url'] = $tracks->download_url;
                $data['cover_image'] = $tracks->cover_image ? $tracks->cover_image : '';
            }
        }
        if($data['track_name']){
           $inputs['track_name'] =  $data['track_name'];
        }

        if($request->hasFile('mp3_file')){
            $file = $request->file('mp3_file');
            if ($request->get('type') == 'original') {
                $destinationPath = 'uploads/campaign/music-files';
                $fileName = \App\libraries\GlobalHelper::uploadFile($file, $destinationPath, $inputs['track_name'], "Mp3");
                /*$data['mp3_file'] = $destinationPath.'/'.$fileName;*/
                $data['external_download_link'] = $destinationPath.'/'.$fileName;
            } else {
                $destinationPath = 'uploads/campaign/music-files';
                $fileName = \App\libraries\GlobalHelper::uploadFile($file, $destinationPath, $inputs['track_name'], "Mp3");

                /*$tmpFile = fopen($destinationPath.'/'.$fileName, 'rb');
                $metaData = Dropbox::uploadFile('/SORE THUMB MEDIA/REMIX CAMPIAGN TRACKS/'.$file->getClientOriginalName(), \Dropbox\WriteMode::add(), $tmpFile, $file->getClientSize());
                fclose($tmpFile);
                unlink($destinationPath.'/'.$fileName);

                $sharedLink = Dropbox::createShareableLink($metaData['path']);*/
                //$data['external_download_link'] = substr_replace($sharedLink, '1', -1);
                $data['external_download_link'] = $destinationPath.'/'.$fileName;
            }

        }

        if($request->hasFile('background')){
            $file = $request->file('background');
            $destinationPath = 'uploads/campaign/background';
            $fileName = \App\libraries\GlobalHelper::uploadFile($file, $destinationPath, $inputs['track_name'], "Background");
            $data['background_file'] = $destinationPath.'/'.$fileName;
        }

        else if($request->get('backgroundImg')) {

            $fileUrl = $request->get('backgroundImg');
            // $fileInfo = explode('.', $fileUrl);
            // $extension = end($fileInfo);
            // $filename = strtolower(date("dmYhis").'.'.$extension);
            // $destinationPath = public_path('uploads/campaign/background').'/'.$filename;
            // @copy($fileUrl,$destinationPath);
            $data['background_file'] = $fileUrl;
        }

        // if($request->get('external_link')){
        //$data['external_download_link'] = $request->get('external_link') ? $request->get('external_link') : null;
        // }
        if($request->hasFile('artwork')){
            $file = $request->file('artwork');
            $destinationPath = 'uploads/campaign/cover-image';
            $fileName = \App\libraries\GlobalHelper::uploadFile($file, $destinationPath, $inputs['track_name'], "Artwork");
            $data['cover_image'] = $destinationPath.'/'.$fileName;
        }

        // if(!empty($soundCloudTrack)){
        //     $data['track_name'] = $soundCloudTrack->track_name;
        //     $data['mp3_file'] = $soundCloudTrack->['stream_url'];
        // }
        $user = $this->user;
        $isNew = $request->get('id') == null ? true : false;
        $campaign = \App\Campaign::findOrNew($request->get('id')); //new \App\Campaign;

        $campaign->fill($data);
        $campaign->user_id = $user->id;
        // $campaign->links = $data['links'];
        $campaign->save();


        if($request->get('action') && $request->get('action')=='add' ){
            $genres = $request->get('campaignGenreModel');
            if($genres){

                $CampaignGenre = new \App\CampaignGenres;

                $CampaignGenre->campaign_id = $campaign->id;
                $CampaignGenre->genre_id = $request->get('campaignGenreModel');
                $CampaignGenre->type = 'parent';
                $CampaignGenre->save();
            }

            $subGenres = $request->get('subGenreModel');
            if($subGenres){
                $CampaignSubGenre = new \App\CampaignGenres;

                $CampaignSubGenre->campaign_id = $campaign->id;
                $CampaignSubGenre->genre_id = $request->get('subGenreModel');
                $CampaignSubGenre->type = 'sub';
                $CampaignSubGenre->save();
            }

            $moods = $request->get('vibeModel');

            if($moods){
                $CampaignMoods = new \App\CampaignMoods;

                $CampaignMoods->campaign_id = $campaign->id;
                $CampaignMoods->mood_id = $request->get('vibeModel');
                $CampaignMoods->save();
            }
        }

        //////////////////////////////////////////////////////////////////////////////////////////////
        //  Update the track demo info if there is same track demo
        $demoTrack = \App\TrackDemo::where('slug', $campaign->slug)
                                    ->with('genres','subGenres','moods')
                                    ->first();

        if (!$isNew && $demoTrack) {
            $campaignObject = \App\Campaign::where('slug', $campaign->slug)
                                    ->with('genres','subGenres','moods')
                                    ->first();

            $campaignData = $campaignObject->toArray();

            $demoTrack->fill($campaignData);
            $demoTrack->type = $campaignData['type'] == 'original' ? 'discover' : 'remix';

            if($campaignData['soundcloud_tracks']){
                $demoTrack->sc_id = $campaignData['soundcloud_tracks'];
            }
            $demoTrack->campaign_id = $campaignData['id'];
            $demoTrack->save();

            $genres = $campaignData['genres'];
            $genres = array_column($genres, 'id');

            if($genres){
                $pivotData = array_fill(0, count($genres), ['type' => 'parent']);
                $syncData  = array_combine($genres, $pivotData);
                $demoTrack->genres()->sync($syncData);
                $demoTrack->genres;

            }else{
                $genres = [];
                $demoTrack->genres()->sync($genres);
            }


            $subGenres = $campaignData['sub_genres'];
            $subGenres = array_column($subGenres, 'id');
            if($subGenres){
                $pivotData = array_fill(0, count($subGenres), ['type' => 'sub']);
                $syncData  = array_combine($subGenres, $pivotData);
                $demoTrack->subGenres()->sync($syncData);
                $demoTrack->subGenres;

            }else{
                $subGenres = [];
                $demoTrack->subGenres()->sync($subGenres);
            }

            $moods = $campaignData['moods'];
            $moods = array_column($moods, 'id');
            if($moods){
                $demoTrack->moods()->sync($moods);
                $demoTrack->moods;

            }else{
                $demoTrack->moods()->sync([]);
            }
        }
        //  Update the track demo info if there is same track demo
        //////////////////////////////////////////////////////////////////////////////////////////////

        $oauthInfo = \App\OauthIdentity::where('provider', 'soundcloud')->where('user_id', $this->user->id)->first();
        \App\libraries\SoundCloudHelper::setMetaData($oauthInfo, $request->get('soundcloud_track'), URL::to('/') . '/campaign/' .$campaign->slug);

        //////////////////////////////////////////////////////////////////////////////////////////////

        $campaigns = \App\Campaign::all();
        return response()->json(['success'=>'Campaign saved successfully.','campaigns'=>$campaigns]);
	}


	public function destroy($id){
		if($id){
			$campaign = \App\Campaign::find($id);
            $campaignGenres = $campaign->genres()->detach();
            \App\Favourite::where('track_id', $campaign->id)->delete();
            \App\PlaylistTrack::where('track_id', $campaign->id)->delete();

            \App\TrackDemo::where('slug', $campaign->slug)->delete();
            $campaign->delete();
			return response()->json(['success'=>'Campaign deleted successfully.']);

		}
	}

    public function destroyStreamline(Request $request) {
        if ($request->get('id')) {
            $streamline = \App\Streamline::find($request->get('id'));
            $streamline->delete();

            return response()->json(['success'=>'Streamline deleted successfully.']);
        }
    }
}