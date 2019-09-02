<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Datatable;

class RepostTrackController extends Controller
{
    public function index()
    {
        $mainTitle = 'Listening Queue';
        $title = 'Repost Tracks';
        $artistList = \App\User::where('user_type', 'artist')->lists('name','id')->toArray();
        return view('admin.tracks.repost',compact('title','artistList','mainTitle'));
    }

    function getTracks(Request $request){
        $tracks = \App\TrackDemo::whereNotNull('repost_track_id')
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


        if($request->get('status') && !empty($request->get('status'))) {
            $tracks = $tracks->where('track_demos.status', $request->get('status'));
        }

        if($request->get('artist') && !empty($request->get('artist'))) {
            $tracks = $tracks->where('track_demos.user_id', $request->get('artist'));
        }
        
        if($request->get('start_date') && !empty($request->get('start_date'))) {
            $tracks = $tracks->whereRaw('DATE_FORMAT(track_demos.created_at, "%Y-%m-%d") >= \''. date('Y-m-d', strtotime($request->get('start_date'))) . '\'');
        }

        if($request->get('end_date') && !empty($request->get('end_date'))) {
            $tracks = $tracks->whereRaw('DATE_FORMAT(track_demos.created_at, "%Y-%m-%d") <= \''. date('Y-m-d', strtotime($request->get('end_date'))) . '\'');
        }

        return Datatable::collection($tracks->get())
        ->addColumn('track_name', function($model) {
            return '<a class="decoration-none trackPlay" data-type="'.$model->type.'" data-track="'.$model->mp3_file.'" data-title="'.$model->track_name.'" href="javascript:void(0)"><span class="fa fa-play-circle-o text-success"></span></a>&nbsp;'.ucfirst($model->track_name);
        })
        ->addColumn('artist_name', function($model) {return ucfirst($model->artist_name);})
        ->addColumn('type', function($model) {return ucfirst($model->type);})
        ->addColumn('status', function($model) {
            if($model->status == 'pending') {
                return '<span class="status"> Pending </span>';
            }else if($model->status == 'approved'){
                return '<span class="status"> Approved </span>';
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
            $status = $model->status == 'pending' ?  '<span class="trackApprove-repost label label-success" id="" title="Approve" data-cmd="approved" data-id="'.$model->id.'">Approve</span>&nbsp;<span class="trackReject-repost label label-danger" id="" title="Reject" data-cmd="rejected" data-id="'.$model->id.'">Reject</span>&nbsp;' : '';

            $viewBtn = '<a class="decoration-none" href="'.\URL::to('admin/repost/'.$model->id.'/details').'"><span class="label label-warning">View</span></a>';
            
            return $status. $viewBtn;
        })
        ->searchColumns('track_name','artists_profile.name')
        ->make();
    }

    public function changeState(Request $request){
        if($request->get('id')){
            $status = $request->get('status');

            $trackDemo = \App\TrackDemo::find($request->get('id'));
            $trackDemo->status = $request->get('status');
            $trackDemo->check = true;
            $trackDemo->save();

            // if($trackDemo){
            //     if($request->get('status') == "approved"){
            //         $notifications = [];
            //         $notifications[] = [
            //                  'user_id' => $trackDemo->user_id,
            //                  'sender_id' => $trackDemo->user_id,
            //                  'object' => 'track',
            //                  'object_type' => 'add',
            //                  'object_id' => $trackDemo->id,
            //                  'created_at' => new \DateTime,
            //                  'updated_at' => new \DateTime,
            //                  'message' => ' reposted track called ',
            //              ];

            //         \DB::table('activities')->insert($notifications);

            //     }
            // }
            
            return response()->json([$status]);
        }
    }

    public function getTrackDetails($id){
        $trackData = \App\TrackDemo::with('artist')->find($id);
        $mainTitle = 'repost';
        $title = ucfirst($trackData->track_name);        
        $socialData = [];
        $socialData['souncloud'] = json_decode($trackData->souncloud_terms);
        $socialData['facebook'] = json_decode($trackData->facebook_terms);
        $socialData['twitter'] = json_decode($trackData->twitter_terms);
        $socialData['youtube'] = json_decode($trackData->youtube_terms);
        $socialData['instagram'] = json_decode($trackData->instagram_terms);
        $artistUrls = json_decode($trackData->artist_links);        

        return view('admin.tracks.detail-view', compact(['title', 'trackData','socialData','artistUrls','mainTitle']));
    }
}
