<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests\Admin\CompetitionRequest;

use App\Http\Controllers\Controller;
use Datatable;

class DiscoverTrackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mainTitle = 'Listening Queue';
        $title = 'Discover';
        $artistList = \App\User::where('user_type', 'artist')->lists('name','id')->toArray();
        $genresList = \App\Genres::whereNull('parent_id')->lists('name','id')->toArray();
        return view('admin.tracks.discover',compact('title','artistList','mainTitle','genresList'));
    }

    public function showPlayer($ids)
    {
        $title = 'JPlayer';
        $idValues = $ids;
        return view('admin.tracks.jplayer',compact('title','idValues'));
    }

    function getTracks(Request $request){
        $tracks = \App\TrackDemo::where('track_demos.type', 'discover')
                                ->where('repost_track_id', null)
                                ->join('artists_profile','track_demos.user_id', '=', 'artists_profile.user_id')
                                ->leftJoin(\DB::raw("(select
                                                            `favourites`.`track_id`
                                                            from `favourites`
                                                            where `favourites`.`type` = 'track'
                                                            group by `favourites`.`track_id`) `favourites`
                                                    "), 'track_demos.id', '=', 'favourites.track_id'
                                                )
                                ->leftJoin('play_history','track_demos.id', '=', 'play_history.track_id')
                                ->leftJoin(\DB::raw("(select
                                                            `download_history`.`track_id`
                                                            from `download_history`
                                                            group by `download_history`.`track_id`) `download_history`
                                                    "), 'track_demos.id', '=', 'download_history.track_id')
                                ->select('track_demos.*', 'artists_profile.name AS artist_name', \DB::raw('count(download_history.track_id) as download_count, count(favourites.track_id) as fav_count'), 'play_history.count')
                                ->groupBy('track_demos.id');
        // dd($tracks->get()->toArray());

        if($request->get('status') && !empty($request->get('status'))) {
            $tracks = $tracks->where('track_demos.status', $request->get('status'));
        }

        if($request->get('artist') && !empty($request->get('artist'))) {
            $tracks = $tracks->where('track_demos.user_id', $request->get('artist'));
        }

        if($request->get('genre') && !empty($request->get('genre'))) {
            $tracks = $tracks->join('track_demo_genres','track_demos.id', '=', 'track_demo_genres.demo_track_id')->where('track_demo_genres.genre_id', $request->get('genre'));
        }
        
        if($request->get('start_date') && !empty($request->get('start_date'))) {
            $tracks = $tracks->whereRaw('DATE_FORMAT(track_demos.created_at, "%Y-%m-%d") >= \''. date('Y-m-d', strtotime($request->get('start_date'))) . '\'');
        }

        if($request->get('end_date') && !empty($request->get('end_date'))) {
            $tracks = $tracks->whereRaw('DATE_FORMAT(track_demos.created_at, "%Y-%m-%d") <= \''. date('Y-m-d', strtotime($request->get('end_date'))) . '\'');
        }

        return Datatable::collection($tracks->get())
        ->addColumn('track_name', function($model) {
            if ($model->type == 'discover' && $model->external_download_link != null && $model->external_download_link != '') {
                return '<a class="decoration-none trackPlay" data-type="'.$model->type.'" data-track="'.$model->external_download_link.'" data-title="'.$model->track_name.'" href="javascript:void(0)"><span class="fa fa-play-circle-o text-success"></span></a>&nbsp;'.ucfirst($model->track_name);
            } else {
                return '<a class="decoration-none trackPlay" data-type="'.$model->type.'" data-track="'.$model->mp3_file.'" data-title="'.$model->track_name.'" href="javascript:void(0)"><span class="fa fa-play-circle-o text-success"></span></a>&nbsp;'.ucfirst($model->track_name);
            }
        })
        ->addColumn('artist_name', function($model) {return ucfirst($model->artist_name);})
     //   ->addColumn('genre', function($model) {return $model->track_genres[0]->name; })
        ->addColumn('status', function($model) {
            if($model->status == 'pending') {
                return '<span class="status"> Pending </span>';
            }else if($model->status == 'approved'){
                return '<span class="status"> Approved </span>';
            }elseif($model->status == 'dripfeed') {
                return '<span class="status">Drip Feed</span>';
            }else{
                return  '<span class="status"> Rejected </span>';
            }
        })
        ->addColumn('download_count', function($model) {
            return $model->download_count;
        })
        ->addColumn('count', function($model) {
            return !empty($model->count) ? $model->count : 0;
        })
        ->addColumn('fav_count', function($model) {
            return $model->fav_count;
        })
        ->addColumn('created_at', function($model) {return '<span class="sort-date">'.strtotime($model->created_at).'</span>'.date('d/m/Y', strtotime($model->created_at));})
        ->addColumn('actions', function($model) {
            $type = \App\TopItems::where('object_id', $model->id)->where('object_type', 'spotlight_discover')->first();
            // echo '<pre>'; print_r($type->toArray()); die; 
            $spootLight = 0;
            if($type){
                $spootLight = 1;
            } 
            $topitem = $model->status == 'approved' ? '<span class="'. ($spootLight ? 'spootLight label label-info' : 'spootLight label background-color').'" id="'.($spootLight ? 'InTop' : 'OutTop').'" title="spotlight" data-cmd="spotlight_discover" data-id="'.$model->id.'">Spotlight</span>&nbsp;' : "";
            $status = $model->status == 'pending' ?  '<span class="trackApprove label label-success" id="" title="Approve" data-cmd="approved" data-id="'.$model->id.'">Approve</span>&nbsp;<span class="trackReject label label-danger" id="" title="Reject" data-cmd="rejected" data-id="'.$model->id.'">Reject</span>&nbsp;<span class="dripFeed label label-primary" id="" title="Move to Drip Feed" data-cmd="dripfeed" data-id="'.$model->id.'">Drip Feed</span>&nbsp;' : '';
            $dripfeed = $model->status == 'dripfeed' ?  '<span class="dripFeed label label-primary" id="" title="Move to Drip Feed" data-cmd="pending" data-id="'.$model->id.'">Remove from Drip Feed</span>&nbsp;' : '';
        

            $viewBtn = '<a class="decoration-none" href="'.\URL::to('admin/discover/'.$model->id.'/details').'"><span class="label label-warning">View</span></a>';
            
            return $topitem. $status.$dripfeed. $viewBtn;
        })
        ->searchColumns('track_name','artists_profile.name')
        ->make();
    }

    public function addTop(Request $request){ 
        if($request->get('top') == 'InTop'){
            \App\TopItems::where('object_id', $request->get('trackId'))
                            ->where('object_type', $request->get('type'))
                            ->delete();
            return response()->json(['msg'=>'removed from spotlight', 'status'=>'removed']);
        }else{
            $type = $request->get('type');
            $trackId = $request->get('trackId');

            $genre = \App\TrackDemoGenres::where('demo_track_id', $trackId)
                                            ->where('type', 'parent')
                                            ->select('genre_id')
                                            ->first();

            $count = \App\TopItems::join('track_demo_genres','top_items.object_id', '=', 'track_demo_genres.demo_track_id')
                                    ->where('track_demo_genres.genre_id', $genre->genre_id)
                                    ->count();

            if ($count < 4 ) {
                $topList = \App\TopItems::firstOrCreate(['object_id'=> $request->input('trackId'),'object_type'=> $request->input('type')]);
                $topList->object_type = $request->get('type');
                $topList->object_id = $request->get('trackId');
                $topList->sequence = 1; //Need to change for use sequence
                $topList->save();
                return response()->json(['msg'=>'successfully added in spotlight', 'status'=>'added']);
            }
            else{
                return response()->json(['You can not select more track for spotlight']); 
            }
        }
    }
// return '<a href="javascript:void(0);" id="' . route('admin.campaigns.destroy',[$model->id]) . '" title="Delete" data-method="DELETE"><i class="fa fa-trash"></i></a>&nbsp; 
//                     <span class="'. ($everyThing ? 'everyThing label label-info' : 'everyThing label label-default background-color').'" id="'.($everyThing ? 'InTop' : 'OutTop').'" title="everything" data-cmd="everything" data-id="'.$model->id.'" >Top Track</span>&nbsp;
//                     <span class="'. ($spootLight ? 'spootLight label label-info' : 'spootLight label label-default background-color').'" id="'.($spootLight ? 'InTop' : 'OutTop').'" title="spotlight" data-cmd="spotlight" data-id="'.$model->id.'">Spotlight</span>';



    public function changeState(Request $request){
        if($request->get('id')){
            $status = $request->get('status');
            $trackDemo = \App\TrackDemo::find($request->get('id'));
            $trackDemo->status = $request->get('status');
            $trackDemo->check = true;
            $trackDemo->save();

            if($request->get('status') == 'dripfeed'){
                $dripFeed = new \App\DripFeed;
                $dripFeed->track_id = $trackDemo->id;
                $dripFeed->track_type = $trackDemo->type;
                $dripFeed->user_id = $trackDemo->user_id;
                $dripFeed->save();

            }

            if($request->get('status') == 'pending'){
                $dripFeed =  \App\DripFeed::where('track_id', $trackDemo->id)->first();
                if($dripFeed){
                    $dripFeed->delete();
                }

            }



            $user = \App\User::where('id',$trackDemo->user_id)->first();
            if($request->get('status') == 'approved'){
                if($user->email_notification ==1){
                    $data = array(
                    'user'            =>  $user->name ? $user->name : $user->first_name,
                    'email'             =>  $user->email,
                    'track'             =>  $trackDemo,    
                    'subject'           =>  'Your recent submission for Discover'
                    );
                     $response = \App\libraries\MailHelper::sendEmail("emails.templates.approve-discover", $data);
                }
                if($user->web_notification ==1){
                    $notificationData = [
                                    'user_id' => $user->id,
                                    'comments'=> 'Congratulations! Your recent submission has been accepted into the Discover section. Keep an eye out as it will be dropping in there soon!',
                                    'type' => 'discover_approval'
                                ];
                     \App\libraries\GlobalHelper::addNotification($notificationData);
                }//web

            }
            if($request->get('status') == 'rejected'){
                if($user->email_notification ==1){
                    $data = array(
                    'user'            =>  $user->name ? $user->name : $user->first_name,
                    'email'             =>  $user->email,
                    'track'             =>  $trackDemo,    
                    'subject'           =>  'Your recent submission for Discover'
                    );
                     $response = \App\libraries\MailHelper::sendEmail("emails.templates.reject-discover", $data);
                }

                if($user->web_notification ==1){
                    $notificationData = [
                                'user_id' => $user->id,
                                'comments'=> 'Unfortunately, your recent submission has not been accepted into the Discover section. Keep up the hard work!',
                                'type' => 'discover_approval'
                            ];
                    \App\libraries\GlobalHelper::addNotification($notificationData);
                }//web notification end

            }
            return response()->json([$status]);
        }
    }

    public function getTrackDetails($id){
        $trackData = \App\TrackDemo::with('artist')->find($id);
        $mainTitle = $trackData->type;
        $title = @ucfirst($trackData->track_name);
        $socialData = [];
        $socialData['soundcloud'] = json_decode($trackData->souncloud_terms);
        $socialData['facebook'] = json_decode($trackData->facebook_terms);
        $socialData['twitter'] = json_decode($trackData->twitter_terms);
        $socialData['youtube'] = json_decode($trackData->youtube_terms);
        $socialData['instagram'] = json_decode($trackData->instagram_terms);
        $artistUrls = json_decode($trackData->artist_links);

        return view('admin.tracks.detail-view', compact(['title', 'trackData','socialData','artistUrls','mainTitle']));
    }


    function rejectTrack(Request $request){
        $id = $request->get('id');
        if($id){
            $trackDemo = \App\TrackDemo::find($id);
            $trackDemo->status = "rejected";
            $trackDemo->save();
            return response()->json(['msg'=>'Track is rejected']);
        }
    }

}