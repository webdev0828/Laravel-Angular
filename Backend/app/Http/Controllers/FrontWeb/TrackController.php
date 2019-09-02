<?php namespace App\Http\Controllers\FrontWeb;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TrackController extends BaseController {


    public function getTracks(Request $request, $type= null,  $slug= null) {
        $trackDemo = \App\TrackDemo::query();
        $genres = $request->get('geners');
        $moods = $request->get('moods');
        $sub_genre = $request->get('sub_genre');
        $order = $request->get('order');
        $limit = 10;//$request->get('limit',10) ;

        $selectedSubGenres = [];
        $spotlights = \App\TrackDemo::join('top_items', 'track_demos.id', '=', 'top_items.object_id')
                                    ->where('top_items.object_type','spotlight_discover')
                                    ->where('track_demos.status', 'approved')
                                    ->select('track_demos.*')
                                    ->orderBy('top_items.created_at', 'DESC');
        if($type){
            $trackDemo = $trackDemo->where('track_demos.type',$type);
        }

        if($order){
            if($order == "-favourite_count" ){
                $trackDemo = $trackDemo->leftJoin(
                                                    \DB::raw("
                                                        (select
                                                            `favourites`.`track_id`
                                                            from `favourites`
                                                            where `favourites`.`type` = 'track'
                                                            group by `favourites`.`track_id`) `favourites`
                                                    "), 'track_demos.id', '=', 'favourites.track_id'
                                                );
            }

            if($order == "-play_count" ){
                $trackDemo = $trackDemo->leftJoin(
                                                    \DB::raw("
                                                        (select
                                                            `play_history`.`track_id`
                                                            from `play_history`
                                                            where `play_history`.`type` = 'track'
                                                            group by `play_history`.`track_id`) `play_history`
                                                    "), 'track_demos.id', '=', 'play_history.track_id'
                                                );
            }

            if($order == "-download_count" ){
                $trackDemo = $trackDemo->leftJoin(
                                                    \DB::raw("
                                                        (select
                                                            `download_history`.`track_id`
                                                            from `download_history`
                                                            group by `download_history`.`track_id`) `download_history`
                                                    "), 'track_demos.id', '=', 'download_history.track_id'
                                                );
            }

            if($order == "DESC" || $order == "ASC"){
                $trackDemo = $trackDemo->orderBy('created_at', $order);
            }
        }

        if($genres){
            $trackGenreIds = \App\TrackDemoGenres::where('genre_id',$genres)
                                                ->lists('demo_track_id');
            $trackDemo = $trackDemo->whereIn('id', $trackGenreIds);

            $spotlights = $spotlights->whereIn('track_demos.id',$trackGenreIds);

            $subGenresId = \App\Genres::where('parent_id',$genres)->lists('id');
            $subGenres = \App\TrackDemoGenres::join('track_demos','track_demo_genres.demo_track_id','=','track_demos.id')
                                              ->whereIn('genre_id',$subGenresId)
                                              ->where('track_demo_genres.type','sub')
                                              ->where('track_demos.status','!=','rejected')
                                              ->lists('track_demo_genres.genre_id');
            $selectedSubGenres = \App\Genres::whereIn('id',$subGenres)->get();
        }

        if($moods){
            $trackMoodIds = \App\TrackDemoMoods::where('mood_id',$moods)
                                                ->lists('demo_track_id');
            $trackDemo = $trackDemo->whereIn('id', $trackMoodIds);
        }

        if($sub_genre){
            $trackSubGenreIds = \App\TrackDemoGenres::where('genre_id', $sub_genre)
                                                    ->where('type', 'sub')
                                                    ->lists('demo_track_id');
            $trackDemo = $trackDemo->whereIn('id', $trackSubGenreIds);
        }

        if($slug){
            $user = \App\User::where('slug',$slug)->first();
            $trackDemo = $trackDemo->where('user_id', $user->id);
        }
        $spotlights = $spotlights->limit(4)->get()->toArray();
        if($spotlights && !$slug){
            foreach ($spotlights as $value) {
                $trackDemo = $trackDemo->where('id', '!=', $value['id']);
            }
        }

        $trackDemo = $trackDemo->where('status', 'approved')->select('track_demos.id','track_demos.sc_id','track_demos.external_download_link','track_demos.user_id','track_demos.track_name', 'track_demos.slug', 'track_demos.mp3_file', 'track_demos.cover_image', 'track_demos.background_file','track_demos.type','track_demos.campaign_id')->paginate($limit);

        foreach($spotlights as &$spotlight) {
            if ($spotlight['type'] == 'discover' && $spotlight['external_download_link'] != null && $spotlight['external_download_link'] != '') {
                $spotlight['mp3_file'] = $spotlight['external_download_link'];
            }
        }

        foreach($trackDemo->items() as $item) {
            if ($item->type == 'discover' && $item->external_download_link != null && $item->external_download_link != '') {
                $item->mp3_file = $item->external_download_link;
            }
        }

        $data = array(
            'data' => $trackDemo->items(),
            'current_page' =>  $trackDemo->currentPage(),
            'last_page' =>  $trackDemo->lastPage(),
            'spotlights' => $spotlights,
            'subGenres' =>  $selectedSubGenres ,
        );
        return response()->json($data);
    }

    public function getMoreTracks(Request $request, $type= null,  $slug= null) {

        $trackDemo = \App\TrackDemo::query();
        $genres = $request->get('geners');
        $moods = $request->get('moods');
        $sub_genre = $request->get('sub_genre');
        $order = $request->get('order');
        $limit = 10;//$request->get('limit',10) ;

        $selectedSubGenres = [];
        $spotlights = \App\TrackDemo::join('top_items', 'track_demos.id', '=', 'top_items.object_id')
                                    ->where('top_items.object_type','spotlight_discover')
                                    ->where('track_demos.status', 'approved')
                                    ->select('track_demos.*')
                                    ->orderBy('top_items.created_at', 'DESC');
        if($type){
            $trackDemo = $trackDemo->where('track_demos.type',$type);
        }

        if($order){
            if($order == "-favourite_count" ){
                $trackDemo = $trackDemo->leftJoin(
                                                    \DB::raw("
                                                        (select
                                                            `favourites`.`track_id`
                                                            from `favourites`
                                                            where `favourites`.`type` = 'track'
                                                            group by `favourites`.`track_id`) `favourites`
                                                    "), 'track_demos.id', '=', 'favourites.track_id'
                                                );
            }

            if($order == "-play_count" ){
                $trackDemo = $trackDemo->leftJoin(
                                                    \DB::raw("
                                                        (select
                                                            `play_history`.`track_id`
                                                            from `play_history`
                                                            where `play_history`.`type` = 'track'
                                                            group by `play_history`.`track_id`) `play_history`
                                                    "), 'track_demos.id', '=', 'play_history.track_id'
                                                );
            }

            if($order == "-download_count" ){
                $trackDemo = $trackDemo->leftJoin(
                                                    \DB::raw("
                                                        (select
                                                            `download_history`.`track_id`
                                                            from `download_history`
                                                            group by `download_history`.`track_id`) `download_history`
                                                    "), 'track_demos.id', '=', 'download_history.track_id'
                                                );
            }

            if($order == "DESC" || $order == "ASC"){
                $trackDemo = $trackDemo->orderBy('created_at', $order);
            }
        }

        if($genres){
            $trackGenreIds = \App\TrackDemoGenres::where('genre_id',$genres)
                                                ->lists('demo_track_id');
            $trackDemo = $trackDemo->whereIn('id', $trackGenreIds);

            $spotlights = $spotlights->whereIn('track_demos.id',$trackGenreIds);

            $subGenresId = \App\Genres::where('parent_id',$genres)->lists('id');
            $subGenres = \App\TrackDemoGenres::join('track_demos','track_demo_genres.demo_track_id','=','track_demos.id')
                                              ->whereIn('genre_id',$subGenresId)
                                              ->where('track_demo_genres.type','sub')
                                              ->where('track_demos.status','!=','rejected')
                                              ->lists('track_demo_genres.genre_id');
            $selectedSubGenres = \App\Genres::whereIn('id',$subGenres)->get();
        }

        if($moods){
            $trackMoodIds = \App\TrackDemoMoods::where('mood_id',$moods)
                                                ->lists('demo_track_id');
            $trackDemo = $trackDemo->whereIn('id', $trackMoodIds);
        }

        if($sub_genre){
            $trackSubGenreIds = \App\TrackDemoGenres::where('genre_id', $sub_genre)
                                                    ->where('type', 'sub')
                                                    ->lists('demo_track_id');
            $trackDemo = $trackDemo->whereIn('id', $trackSubGenreIds);
        }

        if($slug){
            $user = \App\User::where('slug',$slug)->first();
            $trackDemo = $trackDemo->where('user_id', $user->id);
        }
        $spotlights = $spotlights->limit(4)->get()->toArray();
        if($spotlights && !$slug){
            foreach ($spotlights as $value) {
                $trackDemo = $trackDemo->where('id', '!=', $value['id']);
            }
        }

        $trackDemo = $trackDemo->where('status', 'approved')->select('track_demos.id','track_demos.sc_id','track_demos.external_download_link','track_demos.user_id','track_demos.track_name', 'track_demos.slug', 'track_demos.mp3_file', 'track_demos.cover_image', 'track_demos.background_file','track_demos.type','track_demos.campaign_id')->paginate($limit);

        foreach($trackDemo->items() as $item) {
            if ($item->type == 'discover' && $item->external_download_link != null && $item->external_download_link != '') {
                $item->mp3_file = $item->external_download_link;
            }
        }

        $data = array(
            'data' => $trackDemo->items(),
            'current_page' =>  $trackDemo->currentPage(),
            'last_page' =>  $trackDemo->lastPage()
        );
        return response()->json($data);
    }

    public function saveTrackShares(Request $request)
    {
        $user =$this->user;
        $user_id = null;
        $user_type = null;


        if($user){
            $user_id = $user->id;
            $user_type = $user->user_type;
        }

        $trackShares = new \App\TrackShares;

        $trackType = $request->get('track_type');
        if($trackType == 'track') {
            $trackShares->track_id = $request->get('trackId');
        } else if($trackType == 'campaign'){
            $trackShares->campaign_id = $request->get('trackId');
        }

        $trackShares->provider_user_id = $request->get('provider_user_id') ? $request->get('provider_user_id') : null;
        $trackShares->user_id =$user_id;
        $trackShares->artist_id = $request->get('artistId');
        $trackShares->user_type = $user_type;
        $trackShares->share_Action = $request->get('share_Action');
        $trackShares->share_type = $request->get('share_type');

        $trackShares->save();

        // save follow action for STM artist account
        if($trackShares->share_Action == 'follow' || $trackShares->share_Action == 'subscribe') {
            $user = \App\User::where('slug', env('STM_ARTIST_ACCOUNT'))->first();

            if($user && $user->id != $trackShares->artist_id) {
                $stmtrackShares = new \App\TrackShares;

                if($trackType == 'track') {
                    $stmtrackShares->track_id = $request->get('trackId');
                } else if($trackType == 'campaign'){
                    $stmtrackShares->campaign_id = $request->get('trackId');
                }

                if( $request->get('share_Action') == 'follow') {
                    $oauthIdentity = \App\OauthIdentity::where('user_id',$user->id)->where('provider', $trackShares->share_type)->first();

                    if($oauthIdentity){

                        $stmtrackShares->provider_user_id = $oauthIdentity ? $oauthIdentity->provider_user_id : null;
                        $stmtrackShares->artist_id = $user->id;
                        $stmtrackShares->user_type = $user_type;
                        $stmtrackShares->share_Action = $request->get('share_Action');
                        $stmtrackShares->share_type = $request->get('share_type');
                        $stmtrackShares->save();
                    }
                } else if( $request->get('share_Action') == 'subscribe'){
                    $stmArtist = \App\ArtistsProfile::where('user_id', $user->id)->first();
                    if($stmArtist &&  $stmArtist->youtube_channel){
                        $stmtrackShares->artist_id = $user->id;
                        $stmtrackShares->user_type = $user_type;
                        $stmtrackShares->share_Action = $request->get('share_Action');
                        $stmtrackShares->share_type = $request->get('share_type');
                        $stmtrackShares->save();
                    }
                }
            }


        }

        return response()->json(['status'=>'success','data'=>$trackShares],200);


    }


    public function getGenres()
    {
        $response = [] ;
        $genres = \App\Genres::limit(10)->get();
        if($genres){
            return response()->json(['status'=>'success','data'=>$genres],200);
        }
        return $response;

    }


    // public function getSpotlightTracks(){
    //     $spotlights = \App\TrackDemo::join('top_items', 'track_demos.id', '=', 'top_items.object_id')
    //                                 ->where('top_items.object_type','spotlight_discover')
    //                                 ->select('track_demos.*')
    //                                 ->orderBy('top_items.sequence', 'ASC')
    //                                 ->get();
    //     return response()->json(['status'=>'success','data'=>$spotlights],200);
    // }



    // public function getSpotlightVideos(){
    //     $spotlights = \App\StmVideoRelease::join('top_items', 'stm_video_releases.id', '=', 'top_items.object_id')
    //                                     ->where('top_items.object_type', 'spotlight_video')
    //                                     ->select('stm_video_releases.*')
    //                                     ->orderBy('top_items.sequence', 'ASC')
    //                                     ->first();
    //     return response()->json(['status'=>'success','data'=>$spotlights],200);
    // }



    public function getSingleTrack(Request $request, $type, $slug)
    {
        // if($type == 'video'){

        //     $track = \App\StmVideoRelease::join('artists_profile', 'stm_video_releases.artist_id', '=', 'artists_profile.user_id')
        //                                 ->select('stm_video_releases.*','stm_video_releases.artwork_file as cover_image','stm_video_releases.artist_id as user_id', 'artists_profile.first_name','artists_profile.last_name','artists_profile.avatar','artists_profile.souncloud_url','artists_profile.facebook_url','artists_profile.twitter_url as twitter_url_link','artists_profile.youtube_url','artists_profile.instagram_url')
        //                                 ->where('stm_video_releases.slug', $slug)
        //                                 ->first();
        //     $track['type'] = 'video';
        //     return response()->json(['data' => $track]);

        // }
        // else
            if($type == 'track'){
            $track = \App\TrackDemo::join('artists_profile', 'track_demos.user_id', '=', 'artists_profile.user_id')
                                    ->select('track_demos.*','track_demos.user_id','track_demos.external_download_link','artists_profile.first_name','artists_profile.last_name','artists_profile.avatar','artists_profile.souncloud_url','artists_profile.facebook_url','artists_profile.twitter_url as twitter_url_link','artists_profile.youtube_url','artists_profile.instagram_url','artists_profile.youtube_channel','track_demos.type as track_type','track_demos.type','artists_profile.fb_page')
                                    ->where('track_demos.slug', $slug)
                                    ->first();
            /*$track['t_type'] = 'track';*/
            $track->genres = $track->allGenres()->lists('name');
            $track->moods = $track->moods()->lists('name');

            // $track['type'] = 'track';
            if($track->track_type == 'remix'){

                $track->mp3_file = $track->external_download_link;
                // if($track->external_download_link){
                //     $track->mp3_file = $track->external_download_link;
                // }
                // $scTrack = \App\SoundcloudArtist::where('sc_id',$track->sc_id)->where('artist_id',$track->user_id)->where('streamable',1)->first();
                // if(empty($scTrack)){
                //     $track->mp3_file = $track->external_download_link;
                // }

                // else{
                //     if(!strpos($track->mp3_file, 'client_id'))
                //         $track->mp3_file = $track->mp3_file.'?client_id='.env('SOUNDCLOUD_KEY');
                // }
                $track->download_url = $track->external_download_link;
                // if(!strpos($track->download_url, 'client_id'))
                //     $track->download_url = $track->download_url.'?client_id='.env('SOUNDCLOUD_KEY');

            }

            if ($track->type == 'discover' && $track->external_download_link != null && $track->external_download_link != '') {
                $track->mp3_file = $track->external_download_link;
            }


            $twitterOauthDetail = \App\OauthIdentity::where('user_id', $track->user_id)->where('provider','twitter')->first();
            $track['twitter_url'] =  $twitterOauthDetail && $twitterOauthDetail->nick_name ? 'https://twitter.com/'.$twitterOauthDetail->nick_name : '';
            $track['twitter_nickname']   = $twitterOauthDetail ? $twitterOauthDetail->nick_name : '';

            $instaOauthDetail = \App\OauthIdentity::where('user_id', $track->user_id)->where('provider','instagram')->first();
            $track['instagram_nickname']   = $instaOauthDetail ? $instaOauthDetail->nick_name : '';

            $oauthProviderUsers = \App\OauthIdentity::where('user_id', $track->user_id)->lists('provider_user_id', 'provider');
            $track['provider_user_id'] = isset( $oauthProviderUsers['soundcloud'] ) ? $oauthProviderUsers['soundcloud'] : '';
            $track['instagram_user_id']  = isset( $oauthProviderUsers['instagram'] ) ? $oauthProviderUsers['instagram'] : '';
            $track['facebook_user_id']   = isset( $oauthProviderUsers['facebook'] ) ? $oauthProviderUsers['facebook'] : '';
            $track['spotify_user_id']   = isset( $oauthProviderUsers['spotify'] ) ? $oauthProviderUsers['spotify'] : '';


            $track['oauthProviders'] = \App\OauthIdentity::where('user_id', $track->user_id)->lists('provider');

            $track['oauthProviderUsers'] = $oauthProviderUsers;

            $stmArtistUser = \App\User::where('slug', env('STM_ARTIST_ACCOUNT'))->first();
            if($stmArtistUser) {
                $stmArtist = \App\ArtistsProfile::where('user_id',$stmArtistUser->id)->first();

                if( $stmArtist &&  $stmArtist->fb_page )
                    $track['stm_fb_page'] = $stmArtist->fb_page;

                $track['stm_artist_id'] = $stmArtistUser->id;
            }

            // Check facebook liked terms
            if($this->user) {
                $fbLikedUsers = [$track->user_id];
                if($stmArtistUser) {
                    $fbLikedUsers[] = $stmArtistUser->id;
                }

                $fbLiked = \App\TrackShares::where('user_id', $this->user->id)
                                            ->whereIn('artist_id', $fbLikedUsers)
                                            ->where('share_action', 'like')
                                            ->where('share_type', 'facebook')
                                            ->lists('artist_id')
                                            ->toArray();

                $track['artist_fb_liked'] = in_array($track->user_id, $fbLiked) ? true : false;

                $track['stm_fb_liked'] = ($stmArtistUser && in_array($stmArtistUser->id, $fbLiked) ) ? true : false;
            } else {
                $track['artist_fb_liked'] = false;
                $track['stm_fb_liked'] = false;
            }

            // Check instagram follow terms

            if($this->user){
                $instagramFollowed = \App\TrackShares::where('user_id', $this->user->id)
                                            ->where('artist_id', $track->user_id)
                                            ->where('share_action', 'follow')
                                            ->where('share_type', 'instagram')
                                            ->count();
                $track['instagram_followed'] =  $instagramFollowed ? true : false;

                $youtubeSubscribed = \App\TrackShares::where('user_id', $this->user->id)
                                                ->where('artist_id', $track->user_id)
                                                ->where('share_action', 'subscribe')
                                                ->where('share_type', 'youtube')
                                                ->count();
                $track['youtube_subscribed'] =  $youtubeSubscribed ? true : false;
            }

            // Visit count
            $ip = $request->ip();
            if($track->campaign_id){
                $campaignVisit = \App\libraries\GlobalHelper::trackVisits($ip, $track->campaign_id, $track->id);
            }else{
                if($track->repost_track_id){
                    $demoTrack = \App\TrackDemo::find($track->repost_track_id);
                    $campaignVisit = \App\libraries\GlobalHelper::trackVisits($ip, $demoTrack->campaign_id, $track->id);
                }
            }

            return response()->json(['data' => $track]);
        }

        else if($type == 'campaign') {
            $track = \App\Campaign::join('artists_profile', 'campaigns.user_id', '=', 'artists_profile.user_id')
                                    ->join('streamlines', 'streamlines.stm_track_id', '=', 'campaigns.id', 'left outer')
                                    ->select('campaigns.*', 'campaigns.user_id', 'campaigns.soundcloud_tracks as sc_id', 'campaigns.external_download_link' ,'artists_profile.first_name','artists_profile.last_name','artists_profile.avatar','artists_profile.souncloud_url','artists_profile.facebook_url','artists_profile.twitter_url as twitter_url_link','artists_profile.youtube_url','artists_profile.instagram_url','artists_profile.youtube_channel','campaigns.type as track_type','campaigns.type','artists_profile.fb_page', 'streamlines.slug as streamlines_slug')
                                    ->where('campaigns.slug', $slug)
                                    ->first();

            $track->genres = $track->allGenres()->lists('name');
            $track->moods = $track->moods()->lists('name');

            if($track->track_type == 'remix'){

                $track->mp3_file = $track->external_download_link;
                $track->download_url = $track->external_download_link;
                // if($track->external_download_link){
                //     $track->mp3_file = $track->external_download_link;
                // }
                // $scTrack = \App\SoundcloudArtist::where('sc_id',$track->sc_id)->where('artist_id',$track->user_id)->where('streamable',1)->first();
                // if(empty($scTrack)){
                //     $track->mp3_file = $track->external_download_link;
                // }
                // else{
                //     if(!strpos($track->mp3_file, 'client_id'))
                //         $track->mp3_file = $track->mp3_file.'?client_id='.env('SOUNDCLOUD_KEY');
                // }
                // if(!strpos($track->download_url, 'client_id'))
                //         $track->download_url = $track->download_url.'?client_id='.env('SOUNDCLOUD_KEY');
            }

            if ($track->type == 'original' && $track->external_download_link != null && $track->external_download_link != '') {
                $track->mp3_file = $track->external_download_link;
            }

            $twitterOauthDetail = \App\OauthIdentity::where('user_id', $track->user_id)->where('provider','twitter')->first();
            $track['twitter_url'] =  $twitterOauthDetail && $twitterOauthDetail->nick_name ? 'https://twitter.com/'.$twitterOauthDetail->nick_name : '';
            $track['twitter_nickname'] = $twitterOauthDetail ? $twitterOauthDetail->nick_name : '';

            $instaOauthDetail = \App\OauthIdentity::where('user_id', $track->user_id)->where('provider','instagram')->first();
            $track['instagram_nickname']   = $instaOauthDetail ? $instaOauthDetail->nick_name : '';


            $oauthProviderUsers = \App\OauthIdentity::where('user_id', $track->user_id)->lists('provider_user_id', 'provider');
            $track['provider_user_id'] = isset( $oauthProviderUsers['soundcloud'] ) ? $oauthProviderUsers['soundcloud'] : '';
            $track['instagram_user_id']  = isset( $oauthProviderUsers['instagram'] ) ? $oauthProviderUsers['instagram'] : '';
            $track['facebook_user_id']   = isset( $oauthProviderUsers['facebook'] ) ? $oauthProviderUsers['facebook'] : '';
            $track['spotify_user_id']   = isset( $oauthProviderUsers['spotify'] ) ? $oauthProviderUsers['spotify'] : '';


            $track['oauthProviders'] = \App\OauthIdentity::where('user_id', $track->user_id)->lists('provider');

            $track['oauthProviderUsers'] = $oauthProviderUsers;

            $stmArtistUser = \App\User::where('slug', env('STM_ARTIST_ACCOUNT'))->first();
            if($stmArtistUser) {
                $stmArtist = \App\ArtistsProfile::where('user_id',$stmArtistUser->id)->first();

                if( $stmArtist &&  $stmArtist->fb_page )
                    $track['stm_fb_page'] = $stmArtist->fb_page;

                $track['stm_artist_id'] = $stmArtistUser->id;
            }

            if($this->user) {
                $fbLikedUsers = [$track->user_id];
                if($stmArtistUser) {
                    $fbLikedUsers[] = $stmArtistUser->id;
                }

                $fbLiked = \App\TrackShares::where('user_id', $this->user->id)
                                            ->whereIn('artist_id', $fbLikedUsers)
                                            ->where('share_action', 'like')
                                            ->where('share_type', 'facebook')
                                            ->lists('artist_id')
                                            ->toArray();

                $track['artist_fb_liked'] = in_array($track->user_id, $fbLiked) ? true : false;

                $track['stm_fb_liked'] = ($stmArtistUser && in_array($stmArtistUser->id, $fbLiked) ) ? true : false;
            } else {
                $track['artist_fb_liked'] = false;
                $track['stm_fb_liked'] = false;
            }

            // Check instagram follow terms
            if($this->user){
                $instagramFollowed = \App\TrackShares::where('user_id', $this->user->id)
                                                ->where('artist_id', $track->user_id)
                                                ->where('share_action', 'follow')
                                                ->where('share_type', 'instagram')
                                                ->count();
                $track['instagram_followed'] =  $instagramFollowed ? true : false;

                // Check youtube subscribe terms
                $youtubeSubscribed = \App\TrackShares::where('user_id', $this->user->id)
                                                ->where('artist_id', $track->user_id)
                                                ->where('share_action', 'subscribe')
                                                ->where('share_type', 'youtube')
                                                ->count();
                $track['youtube_subscribed'] =  $youtubeSubscribed ? true : false;
            }

            // $scOauthDetailInstagram = \App\OauthIdentity::where('user_id', $track->user_id)->where('provider','instagram')->first();

            // $track['instagram_user_id'] =  $scOauthDetailInstagram && $scOauthDetailInstagram->provider_user_id ? $scOauthDetailInstagram->provider_user_id : '';

            // $scOauthDetail = \App\OauthIdentity::where('user_id', $track->user_id)->where('provider','soundcloud')->first();
            // $track['provider_user_id'] =  $scOauthDetail && $scOauthDetail->provider_user_id ? $scOauthDetail->provider_user_id : '';

            $ip = $request->ip();
            $campaignVisit = \App\libraries\GlobalHelper::trackVisits($ip, $track->id, null);


            return response()->json(['data' => $track]);
        }
    }

    public function getVideoTracks($slug)
    {
        $user = \App\User::where('slug',$slug)->first();
        $videoTracks = \App\StmVideoRelease::where('stm_video_releases.artist_id', $user->id)
                                           ->paginate(12);
        return response()->json($videoTracks);
    }



    public function getRecommendedTracks($type,$slug){
        if($type == 'track'){
            $data['artist'] = \App\TrackDemo::join('artists_profile', 'track_demos.user_id', '=', 'artists_profile.user_id')
                                ->where('track_demos.slug',$slug)
                                ->select('track_demos.user_id','track_demos.id','track_demos.track_name','track_demos.type','track_demos.slug','artists_profile.first_name','artists_profile.last_name','artists_profile.avatar','artists_profile.souncloud_url','artists_profile.facebook_url','artists_profile.twitter_url','artists_profile.youtube_url','artists_profile.instagram_url','track_demos.background_file')
                                ->first();

            $data['artistsRandomTracks'] = \App\TrackDemo::where('user_id',$data['artist']->user_id)
                                                          ->where('status','approved')
                                                          ->whereNull('isSignupTrack')
                                                          ->where('type','!=','music_video')
                                                          ->inRandomOrder()
                                                          ->take(3)->get();

            $data['stmArtistTracks'] = [];
            $data['stmArtistsGenresTracks'] = [];

            $stm_artist = env('STM_ARTIST_ACCOUNT');
            $stmArtist = \App\User::where('slug',$stm_artist)
                              ->select('id','name','slug','first_name','last_name')
                              ->first();
            if($stmArtist){
                $data['stmArtistTracks'] = \App\TrackDemo::where('user_id',$stmArtist->id)
                                                       ->where('type','discover')
                                                       ->where('status','approved')
                                                       ->whereNull('isSignupTrack')
                                                       ->inRandomOrder()
                                                       ->take(3)
                                                       ->get();
                $trackGenres = \App\TrackDemoGenres::where('demo_track_id',$data['artist']->id)->lists('genre_id');
                $trackGenresTracks = \App\TrackDemoGenres::whereIn('genre_id',$trackGenres)->distinct()->lists('demo_track_id');

                $spotlightIds = \App\TopItems::where('object_type','spotlight_discover')->lists('object_id');
                $data['stmArtistsGenresTracks'] = \App\TrackDemo::
                                                             // join('track_demo_genres','track_demos.id','=','track_demo_genres.demo_track_id')
                                                             whereIn('id', $trackGenresTracks)
                                                            ->where('user_id',$stmArtist->id)
                                                            ->whereNull('isSignupTrack')
                                                            ->where('type','discover')
                                                            ->where('status','approved')
                                                            ->whereIn('id',$spotlightIds)
                                                            ->inRandomOrder()
                                                            ->take(3)
                                                            ->get();
            }
            return response()->json(['status'=>'success','data'=>$data],200);
        }

        if($type == 'campaign'){

            $data['artist'] = \App\Campaign::join('artists_profile', 'campaigns.user_id', '=', 'artists_profile.user_id')
                                ->where('campaigns.slug',$slug)
                                ->select('campaigns.user_id','campaigns.track_name','campaigns.id','campaigns.type','campaigns.slug','artists_profile.first_name','artists_profile.last_name','artists_profile.avatar','artists_profile.souncloud_url','artists_profile.facebook_url','artists_profile.twitter_url','artists_profile.youtube_url','artists_profile.instagram_url','campaigns.background_file')
                                ->first();

            $data['artistsRandomTracks'] = [];
            $artistplan = \App\Subscription::where('user_id',$data['artist']->user_id)
                                            ->select('stripe_plan')
                                            ->first();
           // if($artistplan && $artistplan->stripe_plan == 'gating2'){
                $data['artistsRandomTracks'] = \App\Campaign::where('user_id',$data['artist']->user_id)
                                                        ->where('status',1)
                                                        ->whereIn('type',['original','remix'])
                                                        ->inRandomOrder()
                                                        ->take(3)
                                                        ->get();
           // }

            $data['stmArtistTracks'] = [];
            $data['stmArtistsGenresTracks'] = [];

            $stm_artist = env('STM_ARTIST_ACCOUNT');
            $stmArtist = \App\User::where('slug',$stm_artist)
                                  ->select('id','name','slug','first_name','last_name')
                                  ->first();
            //Display current artist tracks
            if($stmArtist){

                $data['stmArtistTracks'] = \App\Campaign::where('user_id',$stmArtist->id)
                                                       ->whereIn('type',['original','remix'])
                                                       ->where('status',1)
                                                       ->inRandomOrder()
                                                       ->take(3)
                                                       ->get();

            }
            //track from discover
            $topPlanUserIds = \App\Subscription::where('stripe_plan','gating2')->take(3)->inRandomOrder()->lists('user_id');
            if($topPlanUserIds){
                $data['stmArtistsGenresTracks'] = \App\TrackDemo::
                                                         // join('track_demo_genres','track_demos.id','=','track_demo_genres.demo_track_id')
                                                         // whereIn('id', $trackGenresTracks)
                                                        whereIn('user_id',$topPlanUserIds)
                                                        ->whereNull('isSignupTrack')
                                                        ->where('type','discover')
                                                        ->where('status','approved')
                                                        ->inRandomOrder()
                                                        ->take(3)
                                                        ->get();

            }
            return response()->json(['status'=>'success','data'=>$data],200);
        }
    }


    // public function getEverythingData(Request $request,$slug){
    //     $user = \App\User::where('slug',$slug)->first();
    //     $limit = $request->get('limit',9) ;

    //     // $tracks = \App\TrackDemo::where('user_id', $user->id)
    //     //                             ->select('track_demos.*')
    //     //                             ->where('status', "approved")
    //     //                             ->whereIn('type', ['discover','remix'])
    //     //                             ->orderBy('track_demos.created_at', 'desc')
    //     //                             ->paginate($limit);

    //     $tracks = \App\Campaign::where('user_id', $user->id)
    //                                 ->select('campaigns.*')
    //                                 ->where('status', 1)
    //                                 ->whereIn('type', ['original','remix'])
    //                                 ->orderBy('campaigns.created_at', 'desc')
    //                                 ->paginate($limit);

    //     $topVideos = \App\StmVideoRelease::join('top_items', 'stm_video_releases.id', '=', 'top_items.object_id')//Need to change as par client suggestion
    //                                 ->where('top_items.object_type', 'admin_video')
    //                                 ->select('stm_video_releases.*')
    //                                 ->orderBy('stm_video_releases.created_at', 'desc')
    //                                 ->get();
    //     $data = array(
    //         'tracks' => $tracks->items(),
    //         'current_page' =>  $tracks->currentPage(),
    //         'last_page' =>  $tracks->lastPage()
    //     );
    //     return response()->json(['data'=>$data , 'videos' => $topVideos]);

    //     // return response()->json(['tracks' => $tracks, 'videos' => $topVideos]);
    // }

    // public function getMoreEverythingData(Request $request,$slug){

    //     $user = \App\User::where('slug',$slug)->first();

    //     $limit = $request->get('limit',9) ;

    //     // $tracks = \App\TrackDemo::where('user_id', $user->id)
    //     //                             ->select('track_demos.*')
    //     //                             ->where('status', "approved")
    //     //                             ->whereIn('type', ['discover','remix'])
    //     //                             ->orderBy('track_demos.created_at', 'desc')
    //     //                             ->paginate($limit);
    //     $tracks = \App\Campaign::where('user_id', $user->id)
    //                                 ->select('campaigns.*')
    //                                 ->where('status', 1)
    //                                 ->whereIn('type', ['original','remix'])
    //                                 ->orderBy('campaigns.created_at', 'desc')
    //                                 ->paginate($limit);

    //     $topVideos = \App\StmVideoRelease::join('top_items', 'stm_video_releases.id', '=', 'top_items.object_id')//Need to change as par client suggestion
    //                                 ->where('top_items.object_type', 'admin_video')
    //                                 ->select('stm_video_releases.*')
    //                                 ->orderBy('stm_video_releases.created_at', 'desc')
    //                                 ->get();
    //     $data = array(
    //         'tracks' => $tracks->items(),
    //         'current_page' =>  $tracks->currentPage(),
    //         'last_page' =>  $tracks->lastPage()
    //     );
    //     return response()->json(['data'=>$data , 'videos' => $topVideos]);
    //     // return response()->json(['tracks' => $tracks, 'videos' => $topVideos]);
    // }
}