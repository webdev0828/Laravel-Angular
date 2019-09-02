<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Datatable;
use App\Http\Controllers\Controller;

use Njasm\Soundcloud\SoundcloudFacade;
// or soundcloud if you don't need a facade for specific tasks
use Njasm\Soundcloud\Soundcloud;

class QualityController extends Controller
{   
 
    public function index()
    {
        $mainTitle = 'Listening Queue';
        $title = 'Quality Control';
        $artistList = \App\User::where('user_type', 'artist')->lists('name','id')->toArray();
        return view('admin.tracks.quality-control',compact('title','artistList','mainTitle'));
    }

    public function getTracks(Request $request){
        $tracks = \App\TrackDemo::where('track_demos.type', 'remix')
                                ->whereNotNull('track_demos.isSignupTrack')
                                // ->where('track_demos.status', 'pending')
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
                                ->select('track_demos.*', 'artists_profile.last_name AS lastName', 'artists_profile.first_name AS firstName', 'artists_profile.souncloud_url AS soundCloudUrl', \DB::raw('count(download_history.track_id) as download_count, count(favourites.track_id) as fav_count'), 'play_history.count')
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
        ->addColumn('sound_cloud', function($model) {
            /*return !empty($model->soundCloudUrl) && !strcmp($model->status, "pending") ? '<a class="decoration-none" target="_blank" href="'.$model->soundCloudUrl.'/tracks"><span class="label label-info">SoundCloud</span></a>' : '';*/
            /*if (substr($model->soundCloudUrl, -1) == "/") {
                return '<a class="decoration-none" target="_blank" href="'.$model->soundCloudUrl.'tracks"><span class="label label-orangered">SoundCloud</span></a>';
            } else {
                return '<a class="decoration-none" target="_blank" href="'.$model->soundCloudUrl.'/tracks"><span class="label label-orangered">SoundCloud</span></a>';
            }*/
            /*return '<a class="decoration-none" href="" onclick="return openSoundCloudPage('.$model->user_id.')"><span class="label label-orangered">SoundCloud</span></a>';*/
            return  '<span class="open-soundcloud label label-orangered" id="" title="OpenSoundCloud" data-cmd="openSoundCloud" data-id="'.$model->user_id.'">SoundCloud</span>';
        })
        ->addColumn('artist_name', function($model) {
            $text = '';
            if(!empty($model->artist_name))
            {
                $current_encoding = mb_detect_encoding( $model->artist_name, 'auto');
                if($current_encoding == "ASCII"){
                    $text = mb_convert_encoding ($model->artist_name, 'US-ASCII', 'UTF-8');
                }
                else{
                    $text = $model->artist_name;
                }
                //$text = iconv($current_encoding, 'ASCII',  $text);

            }
            
            return ucfirst($model->lastName && $model->firstName ? $model->firstName.' '.$model->lastName : $text);
        })
        ->addColumn('status', function($model) {
            if($model->status == 'pending') {
                return '<span class="status"> Pending </span>';
            }else if($model->status == 'approved'){
                return '<span class="status"> Approved </span>';
            }else{
                return  '<span class="status"> Rejected </span>';
            }
        })

        // ->addColumn('download_count', function($model) {
        //     return $model->download_count;
        // })
        // ->addColumn('count', function($model) {
        //     return !empty($model->count) ? $model->count : 0;
        // })
        // ->addColumn('fav_count', function($model) {
        //     return $model->fav_count;
        // })
        ->addColumn('created_at', function($model) {return '<span class="sort-date">'.strtotime($model->created_at).'</span>'.date('d/m/Y', strtotime($model->created_at));})
        ->addColumn('actions', function($model) {
             
            $topitem = $model->status == 'approved' ? "" : "";
            $status = $model->status == 'pending' ?  '<span class="trackApprove-quality label label-success" id="" title="Approve" data-cmd="approved" data-id="'.$model->id.'">Approve</span>&nbsp;<span class="trackReject-quality label label-danger" id="" title="Reject" data-cmd="rejected" data-id="'.$model->id.'">Reject</span>&nbsp;' : '';

            $viewBtn = '<a class="decoration-none" href="'.\URL::to('admin/quality-control/'.$model->id.'/details').'"><span class="label label-warning">View</span></a>';
            
            return $topitem. $status. $viewBtn;
        })
        ->searchColumns('track_name','artists_profile.name')
        ->make();
    }
    
    public function updatePlanBillingInfo($user){
         $user = $user;
            $stripeResponse = $user->asStripeCustomer();
            // print_r($stripeResponse);
            // die;
            if($stripeResponse){
                $stripeResponse = $stripeResponse['subscriptions']['data'][0];
                $planInfo = \App\Plans::where('stripe_plan_key',$stripeResponse['plan']['id'])->first();
                $billingObject = new \App\PlanBilling;
                $billingObject->user_id = $user->id;
                $billingObject->plan_id = $planInfo->id;
                $billingObject->plan_name = $planInfo->name;
                
                $billingObject->price = $planInfo->amount;
                $billingObject->start_date = date('Y-m-d H:i:s',$stripeResponse->current_period_start);
                $billingObject->expire_date = date('Y-m-d H:i:s',$stripeResponse->current_period_end);
                $billingObject->discover_demo_limit = $planInfo->discover_demo_limit;
                $billingObject->remix_demo_limit = $planInfo->remix_demo_limit;
                $billingObject->video_demo_limit = $planInfo->video_demo_limit;
                $billingObject->subscription_id = $stripeResponse->id;
                $billingObject->save();

             }
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

                        if($trackDemo->isSignupTrack == 'signup'){
                            $user = \App\User::where('id',$trackDemo->user_id)->first();
                            $user_profile = \App\UserProfile::where('user_id',$trackDemo->user_id)->first();
                            if($user_profile){
                                $artistprofile = \App\ArtistsProfile::where('user_id',$trackDemo->user_id)->first();
                                $artistprofile->fill($user_profile->toArray());
                                $artistprofile->slug = $user->slug;
                                $artistprofile->save();
                                $user->user_type = 'artist';
                                $user->save();
                                $user_profile->delete();
                                   // $this->updatePlanBillingInfo();
                            }

                            //  For change following notification comments when user become artist 
                            $followerIds = \App\Follower::where('user_id', $trackDemo->user_id)->distinct()->lists('follower_id');
                            // $notiUserIds = \App\Notification::whereIn('user_id', $followerIds)->where('type','followed')->distinct()->lists('user_id');
                            $notificationsUsers = \App\Notification::join('users','notifications.user_id','=','users.id')
                                                                    ->whereIn('users.id',$followerIds)
                                                                    ->where('notifications.type','followed')
                                                                    ->select('users.id as userAccountId','users.user_type','notifications.comments','notifications.user_id','notifications.id')->get();
                            foreach ($notificationsUsers as $value) {
                                $pos = strpos($value->comments, '/user/');
                                if($pos){
                                    $array = explode('/',$value->comments);
                                    $slug = $array[4];
                                    $userData = \App\User::where('slug',$slug)->select('user_type')->first();
                                    if($userData){
                                        if($userData->user_type == 'artist'){
                                            $notification = \App\Notification::find($value->id);
                                            $newComment  = str_replace('/user/', '/', $value->comments);
                                            $newComment  = str_replace('favourites', 'tracks', $newComment);
                                            $notification->comments = $newComment;
                                            $notification->save();
                                        }
                                    }
                                    // if($userData->user_type == 'artist'){
                                    //     $notification = \App\Notification::find($value->id);
                                    //     $newComment  = str_replace('/user/', '/', $value->comments);
                                    //     $newComment  = str_replace('favourites', 'tracks', $newComment);
                                    //     $notification->comments = $newComment;
                                    //     $notification->save();
                                    // }
                                }
                            }

                            //  For change favourites notification comments when user become artist 
                            $campaignIds = \App\Favourite::where('user_id', $trackDemo->user_id)->distinct()->lists('campaign_id');
                            $campaignUserIds = \App\Campaign::whereIn('id', $campaignIds)->distinct()->lists('user_id');
                            // $notiUserIds = \App\Notification::whereIn('user_id', $campaignUserIds)->where('type','favorited')->distinct()->lists('user_id');
                            $notificationsUsers = \App\Notification::join('users','notifications.user_id','=','users.id')
                                                                    ->whereIn('users.id',$campaignUserIds)
                                                                    ->where('notifications.type','favorited')
                                                                    ->select('users.id as userAccountId','users.user_type','notifications.comments','notifications.user_id','notifications.id')->get();

                            foreach ($notificationsUsers as $value) {
                                $pos = strpos($value->comments, '/user/');
                                if($pos){
                                    $array = explode('/',$value->comments);
                                    $slug = $array[4];
                                    $userData = \App\User::where('slug',$slug)->select('user_type')->first();
                                    if($userData){
                                        if($userData->user_type == 'artist'){
                                            $notification = \App\Notification::find($value->id);
                                            $newComment  = str_replace('/user/', '/', $value->comments);
                                            $newComment  = str_replace('favourites', 'tracks', $newComment);
                                            $notification->comments = $newComment;
                                            $notification->save();
                                        }
                                    }
                                        
                                }
                            }

                            if($user->email_notification ==1){
                                $data = array(
                                                'user'              =>  $user->name,
                                                'email'             =>  $user->email,
                                                // 'subject'           =>  'Congratulations! You have been accepted as an artist on Sore Thumb Media. Get started by setting up your first campaign!'
                                                 'subject'          => 'Sore Thumb Media quality control'   
                                            );
                                $response = \App\libraries\MailHelper::sendEmail('emails.templates.artist-acceptance', $data);
                            }

                            //now deal with customer subscription with Free account.
                             $subscription = $user->newSubscription('main', 'free');
                             $response =  $subscription->create(null, [
                                'email' => $user->email
                                ]);
                             $this->updatePlanBillingInfo($user);
                        }

                        $trackDemo->status = $status;
                        $trackDemo->save();
                        if($user->web_notification ==1){
                            $notificationData = [
                                'user_id' => $user->id,
                                'comments'=> 'Congratulations! You have been accepted as an artist on Sore Thumb Media. Get started by setting up your first <a href="/dashboard"> campaign</a>!',
                                'type' => 'signup_approved'
                            ];
                            \App\libraries\GlobalHelper::addNotification($notificationData);
                        }
                        return response()->json(['status'=> $status]);
                }
                else{
                    if($trackDemo->isSignupTrack == 'signup'){
                        $user = \App\User::where('id', $trackDemo->user_id)->first();
                        if($user->email_notification ==1){
                            $data = array(
                                            'user'              =>  $user->name,
                                            'email'             =>  $user->email,
                                            // 'subject'           =>  'Your recent submission to join Sore Thumb Media as an artist'
                                            'subject'           => 'Sore Thumb Media quality control'
                                        );
                                
                            $response = \App\libraries\MailHelper::sendEmail('emails.templates.artist-rejection-signup', $data);
                        }

                        $trackDemo->status = $status;
                        $trackDemo->save();

                        if($user->web_notification ==1){
                            $notificationData = [
                                    'user_id' => $user->id,
                                    'comments'=> 'Unfortunately, your recent request to become an artist on Sore Thumb Media has been declined. Keep up the hard work and re-submit in 2 months!',
                                    'type' => 'signup_rejection'
                                ];
                            \App\libraries\GlobalHelper::addNotification($notificationData);
                        }//if($user->web_notification ==1)
                    }
                }
            }
        }
    }

    public function getTrackDetails($id){
        $isSignIn = 1;
        $trackData = \App\TrackDemo::with('artist')->find($id);
        $mainTitle = $trackData->type;
        $title = ucfirst($trackData->track_name);        
        $socialData = [];
        $socialData['souncloud'] = json_decode($trackData->souncloud_terms);
        $socialData['facebook'] = json_decode($trackData->facebook_terms);
        $socialData['twitter'] = json_decode($trackData->twitter_terms);
        $socialData['youtube'] = json_decode($trackData->youtube_terms);
        $socialData['instagram'] = json_decode($trackData->instagram_terms);
        $artistUrls = json_decode($trackData->artist_links);        

        return view('admin.tracks.detail-view', compact(['title', 'trackData','socialData','artistUrls','mainTitle', 'isSignIn']));
    }
}
