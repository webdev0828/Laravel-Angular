<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests\Admin\CompetitionRequest;

use App\Http\Controllers\Controller;
use Datatable;

class MusicVideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'STM Release';
        $artistList = \App\User::where('user_type', 'artist')->lists('name','id')->toArray();
        return view('admin.tracks.music-video',compact('title','artistList'));
    }

    public function getMusicVideo(Request $request){
        $tracks = \App\TrackDemo::where('type', 'music_video')
                                ->where('repost_track_id', null)
                                ->select('track_demos.*');
        if($request->get('status') && !empty($request->get('status'))) {
            $tracks = $tracks->where('status', $request->get('status'));
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

        return Datatable::query($tracks)
        ->addColumn('track_name', function($model) {
            $clientURL = "";
              $MP3URL = $model->mp3_file;
            if (!preg_match('/secret_token/',$MP3URL)){
                $clientURL = "?client_id=".env('SOUNDCLOUD_KEY');
            }
        return '<a class="decoration-none trackPlay" data-type="'.$model->type.'" data-track="'.$model->mp3_file.$clientURL.'" data-title="'.$model->track_name.'" href="javascript:void(0)"><span class="fa fa-play-circle-o text-success"></span></a>&nbsp;'. ucfirst($model->track_name);
        })
        ->addColumn('artist_name', function($model) {return ucfirst($model->artist_name);})
        ->addColumn('status', function($model) {
            if($model->status == 'pending'){
                return 'Pending';
            }else if($model->status == 'approved'){
                return 'Approved';
            }else{
                return  'Rejected';
            }
        })
        ->addColumn('created_at', function($model) {return '<span class="sort-date">'.strtotime($model->created_at).'</span>'.date('d/m/Y', strtotime($model->created_at));})
        ->addColumn('actions', function($model) {
            // $type = \App\TopItems::where('object_id', $model->id)->select('object_type')->get();
            // $spotlightVideo = 0;
            // $eveythingVideo = 0;

            // foreach ($type as $value) {
            //     if($value->object_type == "spotlightvideo"){
            //         $spotlightVideo = 1;
            //     } 
            //     if($value->object_type == "everythingvideo"){
            //         $eveythingVideo = 1;
            //     }            
            // } 
            

            $pendingStatus = $model->status == 'pending' ? '<span class="videoApprove label label-info" id="" title="Approve" data-cmd="approved" data-id="'.$model->id.'">Approve</span>&nbsp;<span class="videoReject label label-danger" id="" title="Reject" data-cmd="rejected" data-id="'.$model->id.'">Reject</span>' : '';
            $download = $this->getPrivateSTM($model->id);

            
            // $viewBtn = '<a class="decoration-none" href="'.\URL::to('admin/music-video/'.$model->id.'/details').'"><span class="label label-warning">View</span></a>';
            
            // $status = ($model->status == 'approved' || $model->status == 'rejected') ? "-" : ;
            
            // $topvideos = $model->status == 'approved' ? '<span class="'. ($spotlightVideo ? 'top-video label label-info' : 'top-video label label-default background-color').'" id="'.($spotlightVideo ? 'InTop' : 'OutTop').'" title="spotlight video" data-cmd="spotlightvideo" data-id="'.$model->id.'">Spotlight</span> &nbsp; <span class="'. ($eveythingVideo ? 'top-video label label-info' : 'top-video label label-default background-color').'" id="'.($eveythingVideo ? 'InTop' : 'OutTop').'" title="eveything video" data-cmd="everythingvideo" data-id="'.$model->id.'">everything</span>' : '';
            // return $status.$topvideos;
            // return $pendingStatus.$viewBtn;
            return $pendingStatus.$download;
        })
        
        ->searchColumns('track_name')
        ->orderColumns('artist_name')
        ->make();
    }


    public function changeState(Request $request){
        if($request->get('id')){
            $status = $request->get('status');

            $trackDemo = \App\TrackDemo::find($request->get('id'));
            $trackDemo->status = $request->get('status');
            $trackDemo->check = true;
            $trackDemo->save();
            $user = \App\User::where('id',$trackDemo->user_id)->first();

            if($request->get('status') == 'pending'){
                $dripFeed =  \App\DripFeed::where('track_id', $trackDemo->id)->first();
                if($dripFeed){
                    $dripFeed->delete();
                }

            }

            if($request->get('status') == 'rejected'){
               
                if($user->email_notification ==1){
                    $data = array(
                    'user'            =>  $user->name ? $user->name : $user->first_name,
                    'email'           =>  $user->email,  
                    // 'subject'           =>  'Your recent submission for STM Release'
                    'subject'         => 'Your recent submission for exclusive release'
                    );
                    $response = \App\libraries\MailHelper::sendEmail("emails.templates.reject-stm-releases", $data);
                }

                if($user->web_notification ==1){
                    $notificationData = [
                                    'user_id' => $user->id,
                                    'comments'=> 'Unfortunately, your recent submission has not been accepted as an exclusive STM release. Keep up the hard work!',
                                    'type' => 'stm_release_reject'
                                ];
                    \App\libraries\GlobalHelper::addNotification($notificationData);
                }//web_notification end
            }
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
            //                  'message' => 'added a new music video called ',
            //              ];

            //         \DB::table('activities')->insert($notifications);
            //     }
            // }

            return response()->json([$status]);
        }
    }


    // function approvedTracks(Request $request){
    //     $tracks = \App\TrackDemo::where('type', 'video')
    //                             ->where('status', 'approved')
    //                             ->select('track_demos.*');
        
    //     return Datatable::query($tracks)
    //     ->showColumns('track_name','artist_name')
    //     ->addColumn('actions', function($model) { 
    //         $type = \App\TopItems::where('object_id', $model->id)->select('object_type')->get();
    //         $topvideo = 0;
    //         foreach ($type as $value) {
    //             if($value->object_type == "topvideo"){
    //                 $topvideo = 1;
    //             }             
    //         } 
    //          return '<span class="'. ($topvideo ? 'top-video label label-info' : 'top-video label label-default background-color').'" id="'.($topvideo ? 'InTop' : 'OutTop').'" title="topvideo" data-cmd="topvideo" data-id="'.$model->id.'">Top video</span>';
    //     })
    //     ->searchColumns('track_name')
    //     ->orderColumns('created_at')
    //     ->make();
    // }


    // public function addTop(Request $request){
    //     if($request->get('top') == 'InTop'){
    //         \App\TopItems::where('object_id', $request->get('trackId'))
    //                         ->where('object_type', $request->get('type'))
    //                         ->delete();
    //         return response()->json(['track removed'], 422);
    //     }
    //     else{
    //         $type =$request->get('type');
    //         if($type == "everythingvideo"){
    //             $count = \App\TopItems::where('object_type', $request->get('type'))
    //                                     ->count();
    //             if ($count < 2 ) {
    //                 $topList = new \App\TopItems;
    //                 $topList->object_type = $type;
    //                 $topList->object_id = $request->get('trackId');
    //                 $topList->sequence = 1; //Need to change for use sequence
    //                 $topList->save();
    //                 return response()->json(['Successfully added in toplist']);
    //             }
    //             else{
    //                 return response()->json(['You can not select more track for toplist'], 422);
    //             }
    //         }
    //         else{
    //             $count = \App\TopItems::where('object_type', $request->get('type'))
    //                                     ->count();
    //             if ($count < 1 ) {
    //                 $topList = new \App\TopItems;
    //                 $topList->object_type = $type;
    //                 $topList->object_id = $request->get('trackId');
    //                 $topList->sequence = 1; //Need to change for use sequence
    //                 $topList->save();
    //                 return response()->json(['Successfully added in toplist']);
    //             }
    //             else{
    //                 return response()->json(['You can not select more track for toplist'], 422);
    //             }
    //         }
    //     }
    // }

    public function getPrivateSTM($id){
        $trackData = \App\TrackDemo::where('id',$id)->select('sc_id')->first();
        if($trackData){
            $scId = $trackData->sc_id;
           $trackInfo = \App\SoundcloudArtist::where('sc_id', $scId)->select('secret_token','download_url')->first();
           // print_r($trackInfo);
           if($trackInfo){
            $token = $trackInfo->secret_token;
             $download_url = $trackInfo->download_url;

           return  "&nbsp;<a class='label label-primary' href='".$download_url."?secret_token=".$token."&client_id=".env('SOUNDCLOUD_KEY')."'>Download</a>"; 
           }
           
        }
        return "";

    }
    public function getTrackDetails($id){
        $trackData = \App\TrackDemo::with('artist')->find($id);
        // echo '<pre>'; print_r($trackData); die; 
        $mainTitle = $trackData->type;
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