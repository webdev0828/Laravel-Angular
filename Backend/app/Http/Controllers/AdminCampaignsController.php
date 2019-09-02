<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests\Admin\CompetitionRequest;

use App\Http\Controllers\Controller;
use Datatable;

class AdminCampaignsController extends Controller
{
    public function index(){
        $mainTitle = 'Campaigns';
        $title = 'Original';
        $artistList = \App\User::where('user_type', 'artist')->lists('name','id')->toArray();
        return view('admin.campaigns.discover',compact('title','artistList','mainTitle'));
    }

    function getDiscoverTracks(Request $request){
        $tracks = \App\Campaign::where('campaigns.type', 'original')
                                ->join('artists_profile','campaigns.user_id', '=', 'artists_profile.user_id')
                                ->leftJoin(\DB::raw("(select
                                                            `download_history`.`campaign_id`
                                                            from `download_history`
                                                            group by `download_history`.`campaign_id`) `download_history`
                                                    "), 'campaigns.id', '=', 'download_history.campaign_id')
                                ->leftJoin('play_history','campaigns.id', '=', 'play_history.campaign_id')
                                ->leftJoin('track_demos', 'campaigns.id', '=', 'track_demos.campaign_id')
                                ->select('campaigns.*', 'artists_profile.name AS artist_name', \DB::raw('count(download_history.campaign_id) as download_count'), 'play_history.count', 'track_demos.id AS discover')
                                ->groupBy('campaigns.id');
                                        
        if($request->get('artist') && !empty($request->get('artist'))) {
            $tracks = $tracks->where('campaigns.user_id', $request->get('artist'));
        }
        
        if($request->get('start_date') && !empty($request->get('start_date'))) {
            $tracks = $tracks->whereRaw('DATE_FORMAT(campaigns.created_at, "%Y-%m-%d") >= \''. date('Y-m-d', strtotime($request->get('start_date'))) . '\'');
        }

        if($request->get('end_date') && !empty($request->get('end_date'))) {
            $tracks = $tracks->whereRaw('DATE_FORMAT(campaigns.created_at, "%Y-%m-%d") <= \''. date('Y-m-d', strtotime($request->get('end_date'))) . '\'');
        }

        return Datatable::collection($tracks->get())
        ->addColumn('track_name', function($model) {
            if ($model->external_download_link != null && $model->external_download_link != '') {
                return '<a class="decoration-none trackPlay" data-type="'.$model->type.'" data-track="'.$model->external_download_link.'" data-title="'.$model->track_name.'" href="javascript:void(0)"><span class="fa fa-play-circle-o text-success"></span></a>&nbsp;'.ucfirst($model->track_name);
            } else {
                return '<a class="decoration-none trackPlay" data-type="'.$model->type.'" data-track="'.$model->mp3_file.'" data-title="'.$model->track_name.'" href="javascript:void(0)"><span class="fa fa-play-circle-o text-success"></span></a>&nbsp;'.ucfirst($model->track_name);
            }
        })
        ->addColumn('artist_name', function($model) {return $model->artist_name;})
        ->addColumn('discover', function($model) {
            if (isset($model->discover) && $model->discover > 0) {
                return '<a href="javascript:void(0);" id="' . \URL::to('admin/discover-tracks/remove/'.$model->discover) . '" title="Remove From Discover" class="discover-status"><i class="fa fa-check"></i></a>';
            } else {
                return '<a href="javascript:void(0);" id="' . \URL::to('admin/discover-tracks/add/'.$model->id) . '" title="Add To Discover" class="discover-status"><i class="fa fa-times"></i></a>';
            }

            /*if (isset($model->discover) && $model->discover > 0) {
                return '<a href="javascript:void(0);" id="'.$model->discover.'" title="Remove From Discover" class="discover-status"><i class="fa fa-check"></i></a>';
            } else {
                return '<a href="javascript:void(0);" id="'.$model->id.'" title="Add To Discover" class="discover-status"><i class="fa fa-times"></i></a>';
            }*/

        })
        ->addColumn('download_count', function($model) {
            return $model->download_count;
        })
        ->addColumn('count', function($model) {
            return !empty($model->count) ? $model->count : 0;
        })
        ->addColumn('created_at', function($model) {
            return '<span class="sort-date">'.strtotime($model->created_at).'</span>'.date('d/m/Y', strtotime($model->created_at));
        })
        ->addColumn('actions', function($model) {
            $viewBtn = '<a class="decoration-none" href="'. \URL::to('admin/track/'.$model->id.'/details') .'"><span class="label label-warning">View</span></a> &nbsp;';
            $delBtn = $deleteBtn = '<a href="javascript:void(0);" id="' . \URL::to('admin/discover-tracks/'.$model->id) . '" title="Delete Campaign" data-method="DELETE"><i class="fa fa-trash"></i></a>';

            return $viewBtn. $delBtn;
        })
        ->searchColumns('track_name','artists_profile.name')
        ->make();
    }

    public function addDiscoverTrack($id) {
        $campaign = \App\Campaign::where('id', $id)
                                ->with('genres','subGenres','moods')
                                ->first();
        $campaign = $campaign->toArray();

        $demoTrack = new \App\TrackDemo;
        $demoTrack->fill($campaign);

        $demoTrack->type = 'discover';
        if($campaign['soundcloud_tracks']){
            $demoTrack->sc_id = $campaign['soundcloud_tracks'];
        }
        $demoTrack->campaign_id = $campaign['id'];
        $demoTrack->status = 'pending';
        $demoTrack->save();

        $genres = $campaign['genres'];
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


        $subGenres = $campaign['sub_genres'];
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

        $moods = $campaign['moods'];
        $moods = array_column($moods, 'id');
        if($moods){
            $demoTrack->moods()->sync($moods);
            $demoTrack->moods;

        }else{
            $demoTrack->moods()->sync([]);
        }

        return response()->json(['status'=>'added', 'data'=>$demoTrack->id], 200);
    }

    public function removeDiscoverTrack($id) {
        $track = \App\TrackDemo::find($id);
        \App\TrackDemoGenres::where("demo_track_id", $id)->delete();
        \App\TrackDemoMoods::where("demo_track_id", $id)->delete();
        $track->delete();

        return response()->json(['status'=>'deleted', 'data'=>$track['campaign_id']], 200);
    }

    public function getRemix(){
        $mainTitle = 'Campaigns';
        $title = 'Remix';
        $artistList = \App\User::where('user_type', 'artist')->lists('name','id')->toArray();
        return view('admin.campaigns.remix',compact('title','artistList','mainTitle'));
    }

    function getRemixTracks(Request $request){
        $tracks = \App\Campaign::where('campaigns.type', 'remix')
                                ->join('artists_profile','campaigns.user_id', '=', 'artists_profile.user_id')
                                ->leftJoin(\DB::raw("(select
                                                            `download_history`.`campaign_id`
                                                            from `download_history`
                                                            group by `download_history`.`campaign_id`) `download_history`
                                                    "), 'campaigns.id', '=', 'download_history.campaign_id')
                                ->leftJoin('play_history','campaigns.id', '=', 'play_history.campaign_id')
                                ->select('campaigns.*', 'artists_profile.name AS artist_name', \DB::raw('count(download_history.campaign_id) as download_count'), 'play_history.count')
                                ->groupBy('campaigns.id');

        if($request->get('artist') && !empty($request->get('artist'))) {
            $tracks = $tracks->where('campaigns.user_id', $request->get('artist'));
        }
        
        if($request->get('start_date') && !empty($request->get('start_date'))) {
            $tracks = $tracks->whereRaw('DATE_FORMAT(campaigns.created_at, "%Y-%m-%d") >= \''. date('Y-m-d', strtotime($request->get('start_date'))) . '\'');
        }

        if($request->get('end_date') && !empty($request->get('end_date'))) {
            $tracks = $tracks->whereRaw('DATE_FORMAT(campaigns.created_at, "%Y-%m-%d") <= \''. date('Y-m-d', strtotime($request->get('end_date'))) . '\'');
        }

        return Datatable::collection($tracks->get())
        ->addColumn('track_name', function($model) {
            return '<a class="decoration-none trackPlay" data-type="'.$model->type.'" data-track="'.$model->mp3_file.'" data-title="'.$model->track_name.'" href="javascript:void(0)"><span class="fa fa-play-circle-o text-success"></span></a>&nbsp;'.ucfirst($model->track_name);
        })
        ->addColumn('artist_name', function($model) {return $model->artist_name;})
        ->addColumn('download_count', function($model) {
            return $model->download_count;
        })
        ->addColumn('count', function($model) {
            return !empty($model->count) ? $model->count : 0;
        })
        ->addColumn('created_at', function($model) {
        	return '<span class="sort-date">'.strtotime($model->created_at).'</span>'.date('d/m/Y', strtotime($model->created_at));
        })
        ->addColumn('actions', function($model) {

            $viewBtn = '<a class="decoration-none" href="'. \URL::to('admin/track/'.$model->id.'/details') .'"><span class="label label-warning">View</span></a> &nbsp;';
            $delBtn = $deleteBtn = '<a href="javascript:void(0);" id="' . \URL::to('admin/discover-tracks/'.$model->id) . '" title="Delete Campaign" data-method="DELETE"><i class="fa fa-trash"></i></a>';

            return $viewBtn. $delBtn;
            
        })
        ->searchColumns('track_name','artists_profile.name')
        ->make();
    }

    public function getTrackDetails($id){

        $trackData = \App\Campaign::with('artist')->find($id);
        $mainTitle = "remix";
        if($trackData->type == 'original'){
            $mainTitle = 'discover';
        }
        $title = ucfirst($trackData->track_name);
        $socialData = [];
        $socialData['soundcloud'] = json_decode($trackData->souncloud_terms);
        $socialData['facebook'] = json_decode($trackData->facebook_terms);
        $socialData['twitter'] = json_decode($trackData->twitter_terms);
        $socialData['youtube'] = json_decode($trackData->youtube_terms);
        $socialData['instagram'] = json_decode($trackData->instagram_terms);
        $artistUrls = json_decode($trackData->artist_links);        
        return view('admin.campaigns.detail-view', compact(['title', 'trackData','socialData','artistUrls','mainTitle']));
    }


    public function destroy($id)
    {
        $campaign = \App\Campaign::find($id);
        if($campaign){
            $campaign->delete();
            \Session::flash('message','Campaign is deleted successfully');
        }

        
    }
}