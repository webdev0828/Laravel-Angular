<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Datatable;
use App\Http\Controllers\Controller;

use Njasm\Soundcloud\SoundcloudFacade;
// or soundcloud if you don't need a facade for specific tasks
use Njasm\Soundcloud\Soundcloud;

class RemixTrackController extends Controller
{   
 
    public function index()
    {
        $mainTitle = 'Listening Queue';
        $title = 'Repost';
        $artistList = \App\User::where('user_type', 'artist')->lists('name','id')->toArray();
        return view('admin.tracks.remix',compact('title','artistList','mainTitle'));
    }

    function getTracks(Request $request){
        $tracks = \App\TrackDemo::where('track_demos.type', 'remix')
                                ->where('repost_track_id', null)

                                ->where(function($query){
                                    $query->whereNull('track_demos.isSignupTrack')
                                    ->orWhere(function($query1){
                                        $query1->whereNull('track_demos.isSignupTrack')
                                        ->where('track_demos.status', '!=', 'pending');
                                    });
                                })
                                
                                
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
        ->addColumn('status', function($model) {
            if($model->status == 'pending') {
                return '<span class="status"> Pending </span>';
            }else if($model->status == 'dripfeed'){
                return '<span class="status"> Drip Feed </span>';
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
             
            $topitem = $model->status == 'approved' ? "" : "";
            $status = $model->status == 'pending' ?  '<span class="trackApprove-remix label label-success" id="" title="Approve" data-cmd="approved" data-id="'.$model->id.'">Approve</span>&nbsp;<span class="trackReject-remix label label-danger" id="" title="Reject" data-cmd="rejected" data-id="'.$model->id.'">Reject</span>&nbsp;<span class="dripFeed-remix label label-primary" id="" title="Move to Drip Feed" data-cmd="dripfeed" data-id="'.$model->id.'">Drip Feed</span>&nbsp;' : '';
            $dripfeed = $model->status == 'dripfeed' ?  '<span class="dripFeed-remix label label-primary" id="" title="Remove from  Drip Feed" data-cmd="pending" data-id="'.$model->id.'">Remove from Drip Feed</span>&nbsp;' : '';


            $viewBtn = '<a class="decoration-none" href="'.\URL::to('admin/remix/'.$model->id.'/details').'"><span class="label label-warning">View</span></a>';
            
            return $topitem. $status.$dripfeed. $viewBtn;
        })
        ->searchColumns('track_name','artists_profile.name')
        ->make();
    }

    public function changeState(Request $request){

        if($request->get('id')){
            $status = $request->get('status');
            $trackDemo = \App\TrackDemo::find($request->get('id')); 
            
            if($trackDemo){
                $trackDemo->check = true;
                $trackDemo->save();

                if($request->get('status') == "approved"){
                    
                    // SoundCloud Repost 
                    $admin = \App\User::where('user_type', 'admin')->first();
                    $userId = $admin->id;
                    $trackId = $trackDemo->sc_id;

                    $isPrivate = \App\SoundcloudArtist::where('artist_id', $trackDemo->user_id)->where('sc_id',$trackId)->first();
                    
                    if($isPrivate && $isPrivate->sharing == 'private'){
                        return response()->json(['errorMsg'=>'You can not repost private track']);
                    }

                    $clientId = env('GATING_SOUNDCLOUD_KEY');
                    $clientSecret = env('GATING_SOUNDCLOUD_SECRET');
                    $redirectUrl = env('GATING_SOUNDCLOUD_REDIRECT_URI');

                    $oauthIdentity = \App\OauthIdentity::where('user_id', $userId)
                                                        ->where('provider', 'soundcloud')
                                                        ->first();

                    if(!$oauthIdentity){
                        return response()->json(['errorMsg'=>'Please connect to SoundCloud']);
                    }else{
                        $accessToken = $oauthIdentity->access_token;

                        $facade = new SoundcloudFacade($clientId, $clientSecret, $redirectUrl);
                        $facade->setAccessToken($accessToken);

                        try{
                            $response = $facade->put('/e1/me/track_reposts/'.$trackId)->asJson()->request();
                        } catch(\Exception $e){
                            return response()->json(['errorMsg' => $e->getMessage()]);
                        };



                        $user = \App\User::where('id', $trackDemo->user_id)->first();
                        if($user->email_notification ==1){
                            $data = array(
                                            'user'              =>  $user->name,
                                            'email'             =>  $user->email,
                                            'track'             =>  $trackDemo,
                                            // 'subject'           =>  'Your recent submission to join Sore Thumb Media as an artist'
                                            'subject'           => 'Your recent submission for Repost'
                                        );
                                
                            $response = \App\libraries\MailHelper::sendEmail('emails.templates.approve-repost', $data);
                        }

                        if($user->web_notification ==1){
                            $notificationData = [
                                    'user_id' => $user->id,
                                    'comments'=> "Congratulations! Your recent submission has been reposted on Sore Thumb Media's Soundcloud. Keep an eye out as it will be dropping in there soon!",
                                    'type' => 'approve_repost'
                                ];
                            \App\libraries\GlobalHelper::addNotification($notificationData);
                        }//web notification

                        $trackDemo->status = $status;
                        $trackDemo->save();

                        return response()->json(['status'=> $status]);
                    }  
                }
                elseif($request->get('status') == "dripfeed"){
                    $trackDemo->status = $status;
                    $trackDemo->save();

                    $dripFeed = new \App\DripFeed;
                    $dripFeed->track_id = $trackDemo->id;
                    $dripFeed->track_type = $trackDemo->type;
                    $dripFeed->user_id = $trackDemo->user_id;
                    $dripFeed->save();

                }
                elseif($request->get('status') == "pending"){
                    $trackDemo->status = $status;
                    $trackDemo->save();

                    $dripFeed =  \App\DripFeed::where('track_id', $trackDemo->id)->first();
                    if($dripFeed){
                        $dripFeed->delete();
                    }

                }
                else{
                    $user = \App\User::where('id', $trackDemo->user_id)->first();
                        if($user->email_notification ==1){
                            $data = array(
                                            'user'              =>  $user->name,
                                            'email'             =>  $user->email,
                                            'track'             =>  $trackDemo,
                                            'subject'           => 'Your recent submission for Repost'
                                        );
                                
                            $response = \App\libraries\MailHelper::sendEmail('emails.templates.reject-repost', $data);
                        }
                        
                        if($user->web_notification ==1){
                            $notificationData = [
                                    'user_id' => $user->id,
                                    'comments'=> "Unfortunately, your recent submission has not been reposted on Sore Thumb Media's Soundcloud. Keep up the hard work!",
                                    'type' => 'reject_repost'
                                ];
                            \App\libraries\GlobalHelper::addNotification($notificationData);
                        }


                    $trackDemo->status = $status;
                    $trackDemo->save();
                }


            }

            
        }
    }

    public function getTrackDetails($id){
        $trackData = \App\TrackDemo::with('artist')->find($id);
        $mainTitle = $trackData->type;
        $title = ucfirst($trackData->track_name);        
        $socialData = [];
        $socialData['soundcloud'] = json_decode($trackData->souncloud_terms);
        $socialData['facebook'] = json_decode($trackData->facebook_terms);
        $socialData['twitter'] = json_decode($trackData->twitter_terms);
        $socialData['youtube'] = json_decode($trackData->youtube_terms);
        $socialData['instagram'] = json_decode($trackData->instagram_terms);
        $artistUrls = json_decode($trackData->artist_links);        

        return view('admin.tracks.detail-view', compact(['title', 'trackData','socialData','artistUrls','mainTitle']));
    }
}
