<?php namespace App\Http\Controllers\FrontWeb;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VideoController extends BaseController {

    public function __construct()
    {
        parent::__construct();
    }
    
    public function getVideos(Request $request, $slug= null) {

        // echo '<pre>'; print_r($request->all()); die; 
        $videoRelease = \App\StmVideoRelease::query();
        $limit = 9;// $request->get('limit',9) ;
        $genres = $request->get('geners');
        $moods = $request->get('moods');
        $order = $request->get('order');
        $sub_genre = $request->get('sub_genre');
        $selectedSubGenres = [];
        $spotlights = \App\StmVideoRelease::join('top_items', 'stm_video_releases.id', '=', 'top_items.object_id')
                                        ->where('top_items.object_type', 'spotlight_video')
                                        ->with(array('campaign'=>function($q){
                                                $q->select('id', 'track_name', 'slug');
                                            }
                                        ))
                                        ->select('stm_video_releases.*')
                                        ->orderBy('top_items.created_at', 'DESC');
       
        if($order){
            if($order == "-favourite_count" ){
                $videoRelease = $videoRelease->leftJoin(
                                                    \DB::raw("
                                                        (select
                                                            `favourites`.`campaign_id`
                                                            from `favourites`
                                                            where `favourites`.`type` = 'campaign'
                                                            group by `favourites`.`campaign_id`) `favourites`
                                                    "), 'stm_video_releases.track_id', '=', 'favourites.campaign_id'
                                                );
            }

            if($order == "-play_count" ){
                $videoRelease = $videoRelease->leftJoin(
                                                    \DB::raw("
                                                        (select
                                                            `play_history`.`campaign_id`
                                                            from `play_history`
                                                            where `play_history`.`type` = 'campaign'
                                                            group by `play_history`.`campaign_id`) `play_history`
                                                    "), 'stm_video_releases.track_id', '=', 'play_history.campaign_id'
                                                );
            }

            if($order == "-download_count" ){
                $videoRelease = $videoRelease->leftJoin(
                                                    \DB::raw("
                                                        (select
                                                            `download_history`.`track_id`
                                                            from `download_history`
                                                            group by `download_history`.`track_id`) `download_history`
                                                    "), 'stm_video_releases.track_id', '=', 'download_history.track_id'
                                                );
            }

            if($order == "DESC" || $order == "ASC"){ 
                $videoRelease = $videoRelease->orderBy('created_at', $order);
            }
        }

        if($genres){
            $videoGenreIds = \App\CampaignGenres::where('genre_id',$genres)
                                                ->lists('campaign_id');
            $videoRelease = $videoRelease->whereIn('track_id', $videoGenreIds);

            $spotlights = $spotlights->whereIn('stm_video_releases.track_id',$videoGenreIds);

            $subGenresId = \App\Genres::where('parent_id',$genres)->lists('id');

            $stmReleaseCampaignIds = \App\StmVideoRelease::lists('track_id');
            
            $subGenres = \App\CampaignGenres::join('campaigns','campaign_genres.campaign_id','=','campaigns.id')
                                                ->whereIn('campaign_genres.genre_id',$subGenresId)
                                                ->where('campaign_genres.type','sub')
                                                ->whereIn('campaigns.id',$stmReleaseCampaignIds)
                                                ->lists('genre_id'); 


            // $subGenres = \App\CampaignGenres::whereIn('genre_id',$subGenresId)
            //                                     ->where('type','sub')
            //                                     ->lists('genre_id');
            $selectedSubGenres = \App\Genres::whereIn('id',$subGenres)->get();
        }

        if($moods){
            $videoMoodIds = \App\CampaignMoods::where('mood_id',$moods)
                                                ->lists('campaign_id');
            $videoRelease = $videoRelease->whereIn('track_id', $videoMoodIds);
        } 

        if($sub_genre){
            $videoSubGenreIds = \App\CampaignGenres::where('genre_id', $sub_genre)
                                                    ->where('type', 'sub')
                                                    ->lists('campaign_id'); 
            $videoRelease = $videoRelease->whereIn('track_id', $videoSubGenreIds);
        }

        if($slug){
            $user = \App\User::where('slug',$slug)->first();
            $videoRelease = $videoRelease->where('artist_id', $user->id);
        }

        $spotlights = $spotlights->first();
        if($spotlights && !$slug){
            $videoRelease = $videoRelease->where('id', '!=', $spotlights->id);
        }
        $videoRelease = $videoRelease->with(array('campaign'=>function($q){
                                            $q->select('id','track_name','slug');
                                        }
                                    ))
                                    ->select('stm_video_releases.*')
                                    ->paginate($limit);

        

        $data = array(
            'data' => $videoRelease->items(), 
            'current_page' =>  $videoRelease->currentPage(),
            'last_page' =>  $videoRelease->lastPage(),
            'spotlights' => $spotlights,
            'subGenres' =>  $selectedSubGenres ,
        );

        return response()->json($data);
    }

    public function getMoreVideos(Request $request, $slug= null) {

        $videoRelease = \App\StmVideoRelease::query();
        $limit = 9;//$request->get('limit',9) ;
        $genres = $request->get('geners');
        $moods = $request->get('moods');
        $order = $request->get('order');
        $sub_genre = $request->get('sub_genre');
        $selectedSubGenres = [];
        $spotlights = \App\StmVideoRelease::join('top_items', 'stm_video_releases.id', '=', 'top_items.object_id')
                                        ->where('top_items.object_type', 'spotlight_video')
                                        ->with(array('campaign'=>function($q){
                                                $q->select('id', 'track_name', 'slug');
                                            }
                                        ))
                                        ->select('stm_video_releases.*')
                                        ->orderBy('top_items.created_at', 'DESC');
       
        if($order){
            if($order == "-favourite_count" ){
                $videoRelease = $videoRelease->leftJoin(
                                                    \DB::raw("
                                                        (select
                                                            `favourites`.`campaign_id`
                                                            from `favourites`
                                                            where `favourites`.`type` = 'campaign'
                                                            group by `favourites`.`campaign_id`) `favourites`
                                                    "), 'stm_video_releases.track_id', '=', 'favourites.campaign_id'
                                                );
            }

            if($order == "-play_count" ){
                $videoRelease = $videoRelease->leftJoin(
                                                    \DB::raw("
                                                        (select
                                                            `play_history`.`campaign_id`
                                                            from `play_history`
                                                            where `play_history`.`type` = 'campaign'
                                                            group by `play_history`.`campaign_id`) `play_history`
                                                    "), 'stm_video_releases.track_id', '=', 'play_history.campaign_id'
                                                );
            }

            if($order == "-download_count" ){
                $videoRelease = $videoRelease->leftJoin(
                                                    \DB::raw("
                                                        (select
                                                            `download_history`.`track_id`
                                                            from `download_history`
                                                            group by `download_history`.`track_id`) `download_history`
                                                    "), 'stm_video_releases.track_id', '=', 'download_history.track_id'
                                                );
            }

            if($order == "DESC" || $order == "ASC"){ 
                $videoRelease = $videoRelease->orderBy('created_at', $order);
            }
        }

        if($genres){
            $videoGenreIds = \App\CampaignGenres::where('genre_id',$genres)
                                                ->lists('campaign_id');
            $videoRelease = $videoRelease->whereIn('track_id', $videoGenreIds);

            $spotlights = $spotlights->whereIn('stm_video_releases.track_id',$videoGenreIds);

            $subGenresId = \App\Genres::where('parent_id',$genres)->lists('id');
            $subGenres = \App\CampaignGenres::whereIn('genre_id',$subGenresId)
                                                ->where('type','sub')
                                                ->lists('genre_id');
            $selectedSubGenres = \App\Genres::whereIn('id',$subGenres)->get();
        }

        if($moods){
            $videoMoodIds = \App\CampaignMoods::where('mood_id',$moods)
                                                ->lists('campaign_id');
            $videoRelease = $videoRelease->whereIn('track_id', $videoMoodIds);
        } 

        if($sub_genre){
            $videoSubGenreIds = \App\CampaignGenres::where('genre_id', $sub_genre)
                                                    ->where('type', 'sub')
                                                    ->lists('campaign_id'); 
            $videoRelease = $videoRelease->whereIn('track_id', $videoSubGenreIds);
        }

        if($slug){
            $user = \App\User::where('slug',$slug)->first();
            $videoRelease = $videoRelease->where('artist_id', $user->id);
        }

        $spotlights = $spotlights->first();
        if($spotlights && !$slug){
            $videoRelease = $videoRelease->where('id', '!=', $spotlights->id);
        }
        $videoRelease = $videoRelease->with(array('campaign'=>function($q){
                                            $q->select('id','track_name','slug');
                                        }
                                    ))
                                    ->select('stm_video_releases.*')
                                    ->paginate($limit);

        

        $data = array(
            'data' => $videoRelease->items(), 
            'current_page' =>  $videoRelease->currentPage(),
            'last_page' =>  $videoRelease->lastPage(),
            'spotlights' => $spotlights,
            'subGenres' =>  $selectedSubGenres ,
        );

        return response()->json($data);
    }

}