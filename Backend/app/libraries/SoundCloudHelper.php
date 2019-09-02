<?php namespace App\libraries;
	

use Illuminate\Support\Str;
use File;
use Njasm\Soundcloud\SoundcloudFacade;
// or soundcloud if you don't need a facade for specific tasks
use Njasm\Soundcloud\Soundcloud;

class SoundCloudHelper {
	
	public static function GetScArtistTracks($oauthInfo,$userId)
    {
    	
        $clientId = env('SOUNDCLOUD_KEY');
        $clientSecret = env('SOUNDCLOUD_SECRET');
        $redirectUrl = env('SOUNDCLOUD_REDIRECT_URI');
        
        $facade = new SoundcloudFacade($clientId, $clientSecret, $redirectUrl);


        if($oauthInfo){
            $accessToken = $oauthInfo->access_token;
            $ScUserId = $oauthInfo->provider_user_id;
            $facade->setAccessToken($accessToken);
            $soundcloudArtist = \App\SoundcloudArtist::where('artist_id',$userId);
            $soundcloudArtist->delete();
            $response  = $facade->get('/tracks', array('user_id' => $ScUserId,'limit'=> 200,'linked_partitioning'=> 1))->asJson()->request();
            $encodedJson = json_decode($response->bodyRaw());
            $soundCloudTracksData = $encodedJson;

            \App\libraries\SoundCloudHelper::saveTracks($soundCloudTracksData,$userId);
            // $this->saveTracks($soundCloudTracksData);
            // print_r($soundCloudTracksData);exit;
            // if($soundCloudTracksData){
            //     foreach ($soundCloudTracksData->collection as $tracks) {
            //         // if($tracks->sharing == 'public'){
            //             $soundcloudArtist = \App\SoundcloudArtist::firstOrCreate(['sc_id' => $tracks->id, 'artist_id' => $userId]);
            //             $soundcloudArtist->artist_id = $userId;
            //             $soundcloudArtist->sc_id = $tracks->id;
            //             $soundcloudArtist->track_name = $tracks->title;
            //             $soundcloudArtist->description = $tracks->description;
            //             $soundcloudArtist->url = $tracks->stream_url;
            //             $soundcloudArtist->cover_image = str_replace('large', 't500x500', $tracks->artwork_url);
            //             $soundcloudArtist->download_url = $tracks->download_url;
            //             $soundcloudArtist->sharing = $tracks->sharing;
            //             $soundcloudArtist->track_type = $tracks->track_type;
            //             $soundcloudArtist->streamable = $tracks->streamable ? $tracks->streamable : '0' ;
            //             $soundcloudArtist->downloadable = $tracks->downloadable ? $tracks->downloadable : '0';
            //             $soundcloudArtist->secret_token = $tracks->secret_token;
            //             $soundcloudArtist->save();
            //             // save genres if not in genres table & save sc track genres
            //             // if($tracks->genre){
            //             //     $genres = \App\Genres::firstOrCreate(['name' => trim($tracks->genre)]);
            //             //     $genres->name = $tracks->genre;
            //             //     $genres->description = $tracks->genre;
            //             //     $genres->save();


            //             //     $scTrackGenre = \App\ScTrackGenres::firstOrCreate(['sc_track_id' => $soundcloudArtist->id]);
            //             //     $scTrackGenre->sc_track_id = $soundcloudArtist->id;
            //             //     $scTrackGenre->genre_id = $genres->id;
                            
            //             //     $scTrackGenre->save();
            //             // }                                         
            //             // save track in sc_artist table
            //         // }
            //     }
            // }

            $soundCloudTracks =\App\SoundcloudArtist::where('artist_id',$userId)->get();
            return response()->json(['soundCloudTracks' => $soundCloudTracks]);
        }
    }

    public static function setMetaData($oauthInfo, $trackId, $url) {
        $clientId = env('SOUNDCLOUD_KEY');
        $clientSecret = env('SOUNDCLOUD_SECRET');
        $redirectUrl = env('SOUNDCLOUD_REDIRECT_URI');

        $facade = new SoundcloudFacade($clientId, $clientSecret, $redirectUrl);
        $facade->setAccessToken($oauthInfo->access_token);

        $data = array('purchase_url' => $url, 'purchase_title' => 'Free Download');
        if ($oauthInfo) {
            $facade->put('/tracks/' . $trackId, array('track' => $data))->asJson()->request();
        }

        return true;
    }

    public static function setMetaDataForStreamline($oauthInfo, $trackId, $url) {
        $clientId = env('SOUNDCLOUD_KEY');
        $clientSecret = env('SOUNDCLOUD_SECRET');
        $redirectUrl = env('SOUNDCLOUD_REDIRECT_URI');

        $facade = new SoundcloudFacade($clientId, $clientSecret, $redirectUrl);
        $facade->setAccessToken($oauthInfo->access_token);

        $data = array('purchase_url' => $url, 'purchase_title' => 'Stream or Download');
        if ($oauthInfo) {
            $facade->put('/tracks/' . $trackId, array('track' => $data))->asJson()->request();
        }

        return true;
    }


    public static function saveTracks($obj,$userId)
    {
        if(isset($obj->next_href)){
            \App\libraries\SoundCloudHelper::loadMore($obj->next_href,$userId);
        }

        foreach ($obj->collection as $tracks) {
            $soundcloudArtist = \App\SoundcloudArtist::firstOrCreate(['sc_id' => $tracks->id, 'artist_id' => $userId]);
            $soundcloudArtist->artist_id = $userId;
            $soundcloudArtist->sc_id = $tracks->id;
            $soundcloudArtist->track_name = $tracks->title;
            $soundcloudArtist->description = $tracks->description;
            $soundcloudArtist->url = $tracks->stream_url;
            $soundcloudArtist->cover_image = str_replace('large', 't500x500', $tracks->artwork_url);
            $soundcloudArtist->download_url = $tracks->download_url;
            $soundcloudArtist->sharing = $tracks->sharing;
            $soundcloudArtist->track_type = $tracks->track_type;
            $soundcloudArtist->streamable = $tracks->streamable ? $tracks->streamable : '0' ;
            $soundcloudArtist->downloadable = $tracks->downloadable ? $tracks->downloadable : '0';
            $soundcloudArtist->secret_token = $tracks->secret_token;
            $soundcloudArtist->save();
        }
    }

    public static function loadMore($url,$userId){
        $soundcloud_url = $url;
        $tracks_json = file_get_contents($soundcloud_url);
        $tracks = json_decode($tracks_json);
        \App\libraries\SoundCloudHelper::saveTracks($tracks,$userId);
    }

}
