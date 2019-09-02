<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests\Admin\CompetitionRequest;

use App\Http\Controllers\Controller;
use Datatable;

class CompetitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $title = 'Competition';
        return view('admin.competition.index',compact('title'));
    }

    function getCompetitions(){
        // date_default_timezone_set("Asia/Calcutta");
        // $date = date('Y-m-d H:i:s');
        $competition = \App\Competition::join('campaigns', 'competitions.track_id','=', 'campaigns.id')
                                        ->select('competitions.id', 'competitions.slug', 'competitions.track_id', 'competitions.start_date', 'competitions.end_date', 'competitions.announcement_date', 'competitions.created_at', 'competitions.status', 'campaigns.track_name','campaigns.mp3_file','campaigns.external_download_link','campaigns.type');
        return Datatable::query($competition)
        ->addColumn('track_name', function($model) {
            if ($model->type == 'original' && $model->external_download_link != null && $model->external_download_link != '') {
                return '<a class="decoration-none trackPlay" data-type="'.$model->type.'" data-track="'.$model->external_download_link.'" data-title="'.ucfirst($model->track_name).'" href="javascript:void(0)"><span class="fa fa-play-circle-o text-success"></span></a>&nbsp;'.ucfirst($model->track_name);
            } else {
                $clientURL = "";
                $MP3URL = $model->mp3_file;
                if (!preg_match('/secret_token/',$MP3URL)){
                    $clientURL = "?client_id=".env('SOUNDCLOUD_KEY');
                }

                return '<a class="decoration-none trackPlay" data-type="'.$model->type.'" data-track="'.$model->mp3_file.$clientURL.'" data-title="'.ucfirst($model->track_name).'" href="javascript:void(0)"><span class="fa fa-play-circle-o text-success"></span></a>&nbsp;'.ucfirst($model->track_name);
            }
        })

      //  ->addColumn('track_name', function($model) {return ucfirst($model->track_name);})
        ->addColumn('start_date', function($model){
            return date("d/m/Y", strtotime($model->start_date));
        })
        ->addColumn('end_date', function($model){
            return date("d/m/Y", strtotime($model->end_date));
        })
        ->addColumn('announcement_date', function($model){
            return $model->announcement_date;
        })
        ->addColumn('status', function($model){
            // return date("Y-m-d") <= $model->end_date && date("Y-m-d") >= $model->start_date ? "Running" : "Closed";
            $returnData = '';
            if ($model->status == 0) {
                $returnData = 'Closed';
            }elseif (date("Y-m-d") <= $model->end_date && date("Y-m-d") >= $model->start_date) {
                $returnData = 'Running';
            }
            elseif (date("Y-m-d") > $model->end_date) {
                $returnData = 'Closed';
            }
            else{
                $returnData = 'Coming';
            }
            return $returnData;
        })
        ->addColumn('actions', function($model) {
          
            $actionBtn = '';


            if ($model->status == 1) {
                $actionBtn = '<a href="' . \URL::to('admin/competition/'.$model->slug.'/edit') . '" id="" title="Edit" class="genresEdit"><i class="fa fa-edit"></i></a> &nbsp;';
            }
            
            $actionBtn = $actionBtn.'<a href="javascript:void(0);" id="' . route('admin.competition.destroy',[$model->slug]) . '" title="Delete" data-method="DELETE"><i class="fa fa-trash"></i></a> &nbsp';
            // if ($model->status == 1) {
                $actionBtn = $actionBtn.'<a href="' . \URL::to('admin/competition-finalist/'.$model->id.'/participates') . '"  title="participated users"><i class="fa fa-users"></i></a>';
            // }
            return $actionBtn;
        })
        // ->addColumn('winners', function($model){
        //     // $viewWinnerBtn = date("Y-m-d") > $model->announcement_date ? '<a href="javascript:;" data-id="'.$model->id.'" class="label label-success view-winner">View winners</a>' : '';
            
        //     if($model->status == 0){
        //         $chooseWinnerBtn = '<a href="javascript:void(0);" data-id="'.$model->id.'" class="label label-success view-winner">View winners</a>';
        //     }
        //     else if(date("Y-m-d") == date('Y-m-d', strtotime($model->announcement_date))) {
        //         $chooseWinnerBtn = '<a href="javascript:void(0);" data-id="'.$model->id.'" class="label label-info choose-winner">Choose winners</a>';
        //     }
        //     else{
        //         $chooseWinnerBtn = '<a href="javascript:void(0);" class="label label-danger">N/A</a>';
        //     }

        //     // $chooseWinnerBtn = date("Y-m-d H:i:s")  $model->announcement_date ? '<a href="javascript:;" data-id="'.$model->id.'" class="label label-info choose-winner">Choose winners</a> &nbsp;' :  
            
        //     return $chooseWinnerBtn;
        // })
        ->searchColumns('name','track_name')
        ->orderColumns('name','track_name','start_date','end_date','announcement_date')
        ->make();
    }

    public function create(){
        $title = 'Add competition';
        $genres = \App\Genres::where('parent_id',null)->lists('name', 'id')->toArray();
        $sub_genres = [];
        return view('admin.competition.create',compact(['title','genres','sub_genres']));
    }


    public function getSubGenres(Request $request){
        $genre = $request->get('genreId');
        $sub_genres = \App\Genres::where('parent_id', $genre)->lists('name', 'id')->toArray();
        return response($sub_genres);
    }

    public function store(CompetitionRequest $request){ 
        // echo '<pre>'; print_r($request->all()); die; 

        $startDate = date('Y-m-d', strtotime($request->get('start_date')));
        $endDate = date('Y-m-d', strtotime($request->get('end_date')));

        $competitionData = \App\Competition::where('start_date','<=',$startDate)
                                            ->where('end_date', '>=', $startDate)
                                            ->orWhere(function($q) use($endDate){
                                                $q->where('start_date','<=',$endDate)
                                                  ->where('end_date', '>=', $endDate);
                                            })
                                            ->first();
        if(!$competitionData) {
            $competition = new \App\Competition;
            $competition->track_id = $request->get('track_id');
            $competition->start_date = $startDate;
            $competition->end_date = $endDate;
            $competition->price_1 = $request->get('price_1');
            $competition->price_2 = $request->get('price_2');
            $competition->price_3 = $request->get('price_3');
            $competition->announcement_date = $request->get('announcement_date');
            $competition->description = $request->get('description');
            $stem_dropbpox = $request->get('stem_file');
            $stem_file = str_replace("dl=0", "dl=1", $stem_dropbpox);
            $competition->stem_file = $stem_file;

            $competition->status = 1;

            // if($request->hasFile('stem_file')){
            //     $file = $request->file('stem_file');
            //     $destinationPath = 'uploads/stem';
            //     $fileName = \App\libraries\GlobalHelper::uploadCompetitionFile($file, $destinationPath);
            //     $competition->stem_file = $destinationPath.'/'.$fileName;
            // }

            $competition->slug = \App\libraries\GlobalHelper::getEloquentUniqueSlug('\App\Competition','slug', "competition");
            $competition->save();
            $campaign = \App\Campaign::where('id',$competition->track_id)->first();
            $notificationData = [
                                'user_id' => null,
                                'comments'=> 'New competition <a href="/remix-competitions">'.$campaign->track_name.' </a>is announced!!!',
                                'type' => 'competition',
                                'isGlobal' => 1,
                                'ref_id' => $competition->id,
                            ]; 
            $response = \App\libraries\GlobalHelper::addNotification($notificationData);
            $user = \DB::table('users')->where('user_type','artist')->update(['notifications' => \DB::raw(" CASE WHEN notifications IS NULL THEN ".$response->id." ELSE   CONCAT(COALESCE(notifications),',',$response->id) END")]);


            \Session::flash('message','Competition has been created successfully');
            return \Redirect::route('admin.competition.index');
        } else {
            \Session::flash('error','Can not add competition in this date');
            return redirect()->back()->withInput()->withErrors(['dateError'=>'Can not add competition in this dates']);
        }
    }

    public function edit($slug){
        $title = 'Edit';
               
        $competition = \App\Competition::where('slug', $slug)->first();
        if($competition){

            $competition->start_date = date('d-m-Y', strtotime($competition->start_date));
            $competition->end_date = date('d-m-Y', strtotime($competition->end_date));
            $competition->announcement_date = $competition->announcement_date;

            $tracks = \App\Campaign::where('id', $competition->track_id)
                                    ->lists('track_name', 'id')
                                    ->toArray();

        } 
        return view('admin.competition.create', compact(['title', 'competition', 'tracks']));
    }

    public function update(CompetitionRequest $request, $id){
        // echo '<pre>'; print_r($request->all()); die; 
        $competition = \App\Competition::findOrFail($id);
        $competition->start_date = date('Y-m-d', strtotime($request->get('start_date')));
        $competition->end_date = date('Y-m-d', strtotime($request->get('end_date')));
        $competition->price_1 = $request->get('price_1');
        $competition->price_2 = $request->get('price_2');
        $competition->price_3 = $request->get('price_3');
        $competition->announcement_date = $request->get('announcement_date');
        $stem_dropbpox = $request->get('stem_file');
        $stem_file = str_replace("dl=0", "dl=1", $stem_dropbpox);

        $competition->stem_file = $stem_file;
        $competition->description = $request->get('description');

       
        // if($request->hasFile('stem_file')){
        //     $file = $request->file('stem_file');
        //     $destinationPath = 'uploads/stem';
        //     if($competition->stem_file){
        //       \App\libraries\GlobalHelper::deleteFile($competition->stem_file);
        //     }
        //     $fileName = \App\libraries\GlobalHelper::uploadCompetitionFile($file, $destinationPath);
        //     $competition->stem_file = $destinationPath.'/'.$fileName;
        // }

        $competition->save();
        
        \Session::flash('message','Competition has been updated successfully');
        return \Redirect::route('admin.competition.index');
    }


    public function destroy($slug){

        $competition = \App\Competition::where('slug', $slug)->first();
        $count = $competition->CompetitionArtist()->count();
        // if(!$count){
            if($competition->original_track){
              \App\libraries\GlobalHelper::deleteFile($competition->original_track);
            }
            if($competition->stem_file){
              \App\libraries\GlobalHelper::deleteFile($competition->stem_file);
            }
            if($competition->cover_image){
              \App\libraries\GlobalHelper::deleteFile($competition->cover_image);
            }
            $competition->delete();
            \Session::flash('message','Competition has been deleted successfully');
        // }
        // else{
        //     \Session::flash('message','Can not delete competition');
        // }   
    }

    public function getArtistData($id){
        // $winnerIds = \App\CompetitionWinner::where('competition_id', $id)->lists('winner_id');
        // $artistIds = \App\CompetitionArtist::where('competition_id', $id)->lists('artist_id');
        // if(!empty($artistIds) && count($artistIds) > 0){
        //     $artistData = \App\User::whereIn('id', $artistIds)->lists('name','id');
        //     return response()->json(compact('artistData','winnerIds'));
        // } else {
        //     return response()->json(['Artist is not participated for this competition'], 422);
        // }
        $participates = \App\User::join('competition_artists', 'users.id','=', 'competition_artists.artist_id')
                                ->where('competition_artists.competition_id', $id)
                                ->select('users.name','competition_artists.created_at','competition_artists.mp3_file','competition_artists.track_name','competition_artists.artist_id','competition_artists.competition_id');
       return Datatable::query($participates)
        ->addColumn('name', function($model) {
            return '<a class="decoration-none trackPlay" data-track="'.$model->mp3_file.'?client_id='.env('SOUNDCLOUD_KEY').'" data-title="'.$model->track_name.'" href="javascript:void(0)"><span class="fa fa-play-circle-o text-success"></span></a>&nbsp;'.ucfirst($model->name);
        })
        ->addColumn('track_name', function($model) {
            return ucfirst($model->track_name);
        })
        ->addColumn('created_at', function($model){
            return date('d/m/Y', strtotime($model->created_at));
        })
        ->addColumn('action', function($model) {
            return '<select class="form-control winner-select" data-compid="'.$model->competition_id.'" data-id="'.$model->artist_id.'">
                        <option value="">Select</option>
                        <option value="1">First</option>
                        <option value="2">Second</option>
                        <option value="3">Third</option>
                    </select>';
        })
        ->searchColumns('name','track_name')
        ->make();
    }


    // public function selectWinner(Request $request){
    //     $competitionId = $request->get('cometitionId');
        
    //     if($request->has('winnerlist')){
    //         $winnerList = $request->get('winnerlist');
            
    //         for ($i=0; $i < count($winnerList); $i++) {
    //             $winner = new \App\CompetitionWinner;
    //             $winner->competition_id = $competitionId;
    //             $winner->winner_id = $winnerList[$i];
    //             $winner->position = $i+1;
    //             $winner->save();
    //         }
    //     }
    //     else{
    //         $removeData = \App\CompetitionWinner::where('competition_id', $competitionId)->delete();
    //     }
    //     $competition = \App\Competition::find($competitionId); 
    //     $competition->status = 0;
    //     $competition->save();
    // }

    // function getWinnerData($id){
    //     $winnerData = \App\User::join('competition_winners', 'users.id', '=', 'competition_winners.winner_id')
    //                             ->where('competition_id', $id)
    //                             ->select('competition_winners.winner_id','competition_winners.position', 'users.name');
    //     if(!empty($winnerData) && count($winnerData) > 0){
    //         return Datatable::query($winnerData)
    //                 ->showColumns('name','position')
    //                 ->make();
    //     } else {
    //         return response()->json(['No winner has choosen for this competition'], 422);
    //     }
    // }


    function getParticipates($id){
        
        $competition = \App\Competition::join('campaigns','competitions.track_id','=','campaigns.id')
                                       ->where('competitions.id', $id)
                                       ->select('campaigns.track_name', 'competitions.published_date')
                                       ->first();

        $title = $competition->track_name.'- Participants';
        $isCompetitionPublished = $competition->published_date ? 1 : null;

        $competitionParticipates = \App\CompetitionArtist::leftjoin('competition_winners', 'competition_artists.artist_id', '=','competition_winners.winner_id')
                                                        ->leftjoin('campaigns', 'competition_winners.campaign_id', '=','campaigns.id')
                                                        ->where('competition_artists.competition_id', $id)
                                                        ->where('competition_artists.status', 'finalist')
                                                        ->select('competition_artists.artist_id', 'competition_winners.position', 'competition_winners.campaign_id')
                                                        ->get();

        $stm_artist_slug = env('STM_ARTIST_ACCOUNT');
        $stmArtist = \App\User::where('slug',$stm_artist_slug)
                              ->select('id','name','slug','first_name','last_name')
                              ->first();

        $remixCampaigns = \App\Campaign::where('user_id', $stmArtist->id)
                                        ->where('type', 'remix')
                                        ->lists('track_name','id');



        $winnerInfo = \App\CompetitionWinner::where('competition_id', $id)->get();
        $winnerCampaignIds = \App\CompetitionWinner::where('competition_id', $id)->lists('campaign_id')->toArray();
        $winnerPositionsInfo = \App\CompetitionWinner::where('competition_id', $id)->lists('position')->toArray();

        $allMusicVideos = \App\StmVideoRelease::join('campaigns', 'stm_video_releases.track_id', '=', 'campaigns.id')
                                                ->lists('campaigns.track_name', 'stm_video_releases.id')
                                                ->toArray();

        $selectedMusicVideo = \App\CompetitionWinner::where('competition_id', $id)
                                                    ->where('position', 1)
                                                    ->select('video_id')
                                                    ->first(); 

        return view('admin.competition.view-participates', compact(['competitionParticipates', 'remixCampaigns', 'title', 'id', 'isCompetitionPublished', 'winnerInfo', 'winnerCampaignIds', 'winnerPositionsInfo', 'allMusicVideos', 'selectedMusicVideo']));
    }

    public function saveWinner(Request $request){
        // echo '<pre>'; print_r($request->all()); die; 
        $artistinfo = $request->get('artistinfo');
        $remix_tracks = $request->get('remix_tracks');
        $positions = $request->get('positions');
        $publishedDate = $request->get('published_date');
        $musicVideoId = $request->get('music_video');
        $competitionId = $request->get('_id');
        $removeOlddata =  \App\CompetitionWinner::where('competition_id', $competitionId)->delete();
        
        if(isset($artistinfo)){
            foreach ($artistinfo as $key => $value) {
                if(!empty($artistinfo[$key]) && !empty($remix_tracks[$key]) && !empty($positions[$key])){
                    $artistId = $value;
                    $position = $positions[$key];
                    $remixId = $remix_tracks[$key];

                    if($position == 1 && isset($musicVideoId)){
                        $video_id = $request->get('music_video');
                    }
                    else{
                        $video_id = null;
                    }

                    $winnerObj = new \App\CompetitionWinner;
                    
                    $winnerObj->competition_id = $competitionId;
                    $winnerObj->winner_id = $artistId;  
                    $winnerObj->position = $position;  
                    $winnerObj->campaign_id = $remixId; 
                    $winnerObj->video_id = $video_id;

                    $winnerObj->save();
                }
            }
            if(isset($publishedDate) && $publishedDate == 1){
                $competition = \App\Competition::find($competitionId); 
                $competition->published_date = date('Y-m-d');
                $competition->save();
            }
           


            // $competition_id = $request->get('_id');
            // $campaign = \App\Campaign::join('competitions','campaigns.id','=','competitions.track_id')
            //                          ->where('competitions.id',$competition_id)
            //                          ->select('campaigns.track_name')
            //                          ->first();
            
            // $notificationData = [
            //                     'user_id' => null,
            //                     'comments'=> 'New competition <a href="/remix-competitions">'.$campaign->track_name.' </a> is ready to listen !!!',
            //                     'type' => 'competition_end',
            //                     'isGlobal' => 1,
            //                     // 'ref_id' => $competition->id,
            //                 ];
            // $response = \App\libraries\GlobalHelper::addNotification($notificationData);
            // $user = \DB::table('users')->where('user_type','artist')->update(['notifications' => \DB::raw(" CASE WHEN notifications IS NULL THEN ".$response->id." ELSE   CONCAT(COALESCE(notifications),',',$response->id) END")]);    
        }
        return redirect()->back();
    }


    public function removeFile(Request $request){
        $competition = \App\Competition::find($request->get('id'));
        if($competition){
            unlink($competition->cover_image);
            $competition->cover_image = null;
            $competition->save();
            return response()->json(["Image removed successfully."]);
        }
    }

    function getStmArtistTracks(Request $request){
        $term = $request->get('term');
        $slug = env('STM_ARTIST_ACCOUNT');
        if($slug){
            $user = \App\User::where('slug', $slug)->first();
            if($user){
                $tracks = \App\Campaign::where('user_id', $user->id)
                                        ->where('track_name', 'like', '%'.$term.'%')
                                        ->where('type', 'remix')
                                        ->select('track_name', 'id')
                                        ->get();
                return response()->json(compact('tracks'));
            }
        }
    }

    function getStmArtistRemixTracks(Request $request){
        
        if($request->get('ids')){
            $ids = $request->get('ids');
        }else{
            $ids = [];
        }

        $term = $request->get('term');
        $slug = env('STM_ARTIST_ACCOUNT');
        if($slug){
            $user = \App\User::where('slug', $slug)->first();
            if($user){
                $tracks = \App\Campaign::where('user_id', $user->id)
                                        ->whereNotIn('id', $ids)
                                        ->where('track_name', 'like', '%'.$term.'%')
                                        // ->where('type', 'remix')
                                        ->select('track_name', 'id')
                                        ->get();
                return response()->json(compact('tracks'));
            }
            
        }
    }


    function getMusicVideos(Request $request){
        
        $term = $request->get('term');
        if($term){
            $videos = \App\StmVideoRelease::join('campaigns', 'stm_video_releases.track_id', '=', 'campaigns.id')
                                            ->where('campaigns.track_name', 'like', '%'.$term.'%')
                                            ->select('campaigns.track_name', 'stm_video_releases.id')
                                            ->get();

            return response()->json(compact('videos'));
         }   
    }
    
    
}