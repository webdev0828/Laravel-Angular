<?php namespace App\Http\Controllers\FrontWeb;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PlanBilling;
use App\Http\Requests\Auth\UpdateArtistRequest;
use App\Http\Requests\Auth\UpdateProfileImageRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\UpdateEmailRequest;

class ArtistController extends BaseController {

    function __construct()
    {
        // construct
        parent::__construct();
    }

    public function index()
    {
        return view('layouts.default'); 
    }


    /*
        Get Logged user Billing and plan history
    */
    public function getPlanBillingInfo(){
        $response = [];
        $user = $this->user;
        if($user){
             $response = [];
             $invoices = $user->subscribed('main') ? $user->invoices() : null;
                
             $i =0;
             if (count($invoices) > 0){
                foreach ($invoices as $invoice){
                   $response[$i]['date'] = $invoice->date()->toFormattedDateString();
                   $response[$i]['id'] = $invoice->id;
                   $response[$i]['price'] =  $invoice->total();
                   $lines= $invoice->lines['data'];
                 //  print_r($lines);
                   $count = 0;
                   foreach ($lines as $key => $line) {
                      $response[$i]['description'] =  $line['description'];
                      $plans= $line['plan'];
                      $response[$i]['plan'] =  $plans['id'];
                      $periods = $line['period'];
                      $response[$i]['start_date'] =  date('Y-m-d',$periods['start']);
                      $response[$i]['expire_date'] =  date('Y-m-d',$periods['end']);

                    // $plans= $lines[0]['plan'];
                    $count++;

                   }
                    // $response[$i]['description'] =  $lines[0]['description'];

                    // $plans= $lines[0]['plan'];
                    // $response[$i]['plan'] =  $plans['id'];
                    // $periods = $lines[0]['period'];
                    // $response[$i]['start_date'] =  date('Y-m-d',$periods['start']);
                    // $response[$i]['expire_date'] =  date('Y-m-d',$periods['end']);


                   $i++;
                }
             }
             $plansData = [];
             $plansObject = \App\Plans::select('name', 'stripe_plan_key')->get();
              foreach ($plansObject as  $value) {
                 $plansData[$value->stripe_plan_key] = $value->name;
              }
            return response()->json(['planBilling' => ['data'=>$response,'master'=>$plansData]]);
        }
        return $response;

    }

    public function GetArtistGenres(){
        $user = $this->user;

        $artist['genres'] = $user->artistGenres()->get();
        $artist['labelGenres'] = $user->artistLabelGenres()->get();
        return response()->json(['data' => $artist]);
    }

    public function getCampaigns(){
        $userId = $this->user->id;
        $campaignIds = \App\TrackDemo::where('user_id',$userId)->where('campaign_id','!=','')->lists('campaign_id');
        $campaigns = \App\Campaign::where('user_id', $userId)
                                  ->where('status','1')
                                  ->whereNotIn('id',$campaignIds)
                                  ->orderBy('track_name', 'ASC')
                                  ->get();
        $scTracks = \App\SoundcloudArtist::where('artist_id', $userId)->orderBy('track_name', 'ASC')->get();
        return response()->json(['data' => $campaigns, 'scTracks' => $scTracks]);
    }


    public function getChart(){
        $userId = $this->user->id;
        $count = \App\TrackShares::where('artist_id', $userId)
                                ->where(function($q){
                                    $q->where(function($query){
                                        $query->where('share_type', 'facebook')
                                        ->where('share_action', 'like');
                                    })
                                    ->orWhere(function($query){
                                        $query->where('share_type', 'instagram')
                                        ->where('share_action', 'follow');
                                    })
                                    ->orWhere(function($query){
                                        $query->where('share_type', 'youtube')
                                        ->where('share_action', 'subscribe');
                                    })
                                    ->orWhere(function($query){
                                        $query->where('share_type', 'twitter')
                                        ->where('share_action', 'follow');
                                    })
                                    ->orWhere(function($query){
                                        $query->where('share_type', 'soundcloud')
                                        ->where('share_action', 'follow');
                                    });
                                })
                                ->select('share_type', \DB::raw('count(*) as totalCount'))
                                ->groupBy('share_type')
                                ->get();
                          
        $campaignsIds = \App\Campaign::where('user_id',$userId)->lists('id');
        $visits = \App\CampaignVisit::whereRaw('created_at > now() - INTERVAL 7 DAY')
                                     ->whereIn('campaign_id',$campaignsIds)
                                     ->select(\DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as date') , \DB::raw('count(campaign_id) as count'))
                                     ->groupBy('date')
                                     ->lists('count','date');
        $visitsCounts = [];                                     
        $now = time();
        for($i=0;$i<7; $i++) {
            $date = date('Y-m-d', strtotime('-'.$i.' day', $now));
            $visitsCounts[$date] = isset($visits[$date]) ? $visits[$date] : 0;
        }

        // Streamline Chart
        $streamlineCounts = \App\StreamlineShares::where('streamline_user_id', $userId)
                                                ->select('share_service', \DB::raw('count(*) as totalCount'))
                                                ->groupBy('share_service')
                                                ->get();

        $streamlinesIds = \App\Streamline::where('user_id', $userId)->lists('id');
        $streamlineVisits = \App\StreamlineVisit::whereRaw('created_at > now() - INTERVAL 7 DAY')
                                                    ->whereIn('streamline_id', $streamlinesIds)
                                                    ->select(\DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as date') , \DB::raw('count(streamline_id) as count'))
                                                    ->groupBy('date')
                                                    ->lists('count','date');
        $streamlineVisitsCounts = [];
        for( $i = 0; $i < 7; $i ++ ) {
            $date = date('Y-m-d', strtotime('-'.$i.' day', $now));
            $streamlineVisitsCounts[$date] = isset($streamlineVisits[$date]) ? $streamlineVisits[$date] : 0;
        }

        return response()->json(['count' => $count,'visits'=>$visitsCounts, 'streamlineCounts' => $streamlineCounts, 'streamlineVisits' => $streamlineVisitsCounts]);
    }

    public function updateArtist(UpdateArtistRequest $request){
        $user = $this->user;
        
        // if($user->user_type=='artist'){
            if($user){
                $artist = \App\ArtistsProfile::where('user_id',$user->id)->first();
                $artist->name = $request->get('name');
                $artist->first_name = $request->get('first_name');
                $artist->last_name = $request->get('last_name');
                $artist->website = $request->get('website');
                $artist->youtube_channel = $request->get('youtube_channel');
                $artist->paypal_email = $request->get('paypal_email');
                $artist->country = $request->get('country');
                // $artist->city = $request->get('city');
                $town = explode(",",$request->get('city'));
                $artist->city = $town[0];

                $artist->souncloud_url = $request->get('souncloud_url') ? $request->get('souncloud_url') : '' ;
                $artist->facebook_url    = $request->get('facebook_url') ?  $request->get('facebook_url') : '' ;
                $artist->youtube_url    = $request->get('youtube_url') ? $request->get('youtube_url') : '' ;
                $artist->twitter_url    = $request->get('twitter_url') ? $request->get('twitter_url') :'';
                $artist->instagram_url    = $request->get('instagram_url') ? $request->get('instagram_url'): '';
                
                // $artist->genres()->sync([1,3,4]);

                $genres = $request->get('genresIds');
                
                if($genres){
                    
                    $genres = explode(",",$request->get('genresIds'));
                    $pivotData = array_fill(0, count($genres), ['type' => 'artist']);
                    $syncData  = array_combine($genres, $pivotData);

                    $user->artistGenres()->sync($syncData);
                    $user->artistGenres;
                }
                else{
                    $user->artistGenres()->sync([]);
                }

                
                if($request->get('password')){
                    $user->password = \Hash::make($request->get('password'));
                    $user->save();
                }
                $user->name = $request->get('name');
                $user->first_name = $request->get('first_name');
                $user->last_name = $request->get('last_name');

                $user->email_notification = $request->get('email_notification', $user->email_notification);
                $user->web_notification = $request->get('web_notification', $user->web_notification);

                $user->save();
                $artist->save();

                if($user->user_type=='artist'){
                    $track = \App\ReleaseTracks::firstOrCreate(['user_id' => $user->id]);
                    // $track = \App\ReleaseTracks::where('user_id',$user->id)->first();

                    if($track){
                        $track->track_name = $request->get('track_name') ? $request->get('track_name') : '' ;
                        $track->user_id=$user->id;
                        // $track->track_name = $request->get('track_name');
                        $track->record_label = $request->get('record_label') ? $request->get('record_label') : '' ;
                        // $track->record_label = $request->get('record_label');
                        $track->purchase_link = $request->get('purchase_link') ? $request->get('purchase_link') : '' ;
                        // $track->purchase_link = $request->get('purchase_link');
                        // if($request->get('labelgenress')){
                        // $track->genres = $request->get('label_genres');
                        $labelGenres = $request->get('labelgenresIds');
                        if(!empty($labelGenres)){
                            $labelGenres = explode(',', $labelGenres);
                            $pivotData = array_fill(0, count($labelGenres), ['type' => 'label']);
                            $syncData  = array_combine($labelGenres, $pivotData);
                            
                            // $artist->labelGenres()->detach($pivotData);
                            $user->artistLabelGenres()->sync($syncData);
                            $user->artistLabelGenres;
                        }
                        else{
                            $user->artistLabelGenres()->sync([]);
                        }
                        // }
                        if($request->hasFile('track'))
                        {
                            $file = $request->file('track');
                            $destinationPath = public_path('uploads/images/tracks');
                            $fileName = \App\libraries\GlobalHelper::uploadImage($file,$destinationPath);
                            $fileName = 'uploads/images/tracks/'.$fileName;
                            $track->album_image = $fileName;
                        }
                       
                        $track->save();
                        
                        $artist->genres = $user->artistGenres()->get();
                        $artist->labelGenres = $user->artistLabelGenres()->get();
                        
                        $artist->track_name = $track->track_name;
                        $artist->record_label = $track->record_label;
                        $artist->purchase_link = $track->purchase_link;
                        // $artist->labelgenress =  $track->genres ? explode(",", $track->genres) : [];
                        $artist->release_track = $track;
                    }
                }
                $artist->user_type = $user->user_type;
                $artist->email_notification = $user->email_notification;
                $artist->web_notification = $user->web_notification;
                // $artist->genres = $artist->genre ? explode(",",$artist->genre) : [];
                // $artist->track_name = $track->track_name;
                // $artist->record_label = $track->record_label;
                // $artist->purchase_link = $track->purchase_link;
                // // $artist->labelgenress =  $track->genres ? explode(",", $track->genres) : [];
                // $artist->release_track = $track;
                

                return response()->json(['status'=>'success', 'message'=> 'Your profile updated successfully ','data' =>$artist],200);
        }
        return response()->json(['status'=>'error','message'=>'Artist not found '], 422); 
    }

    public function changePassword(ChangePasswordRequest $request){
        
        $user = $this->user;
        if(\Auth::attempt($request->only('email', 'password' ), true)) {
            $user->password = \Hash::make($request->get('new_password'));
            $user->save();
            return response()->json(['status'=>'success'],200);
        }
        else{
            return response()->json(['status'=>'incorrect','message' =>'Password not match'],422);
        }   
    }

    public function checkPassword(Request $request){
        $user = $this->user;
        if($user->provider == 'facebook'){
            if($user->email == $request->get('email') && $user->secret_token == $request->get('password')){
                return response()->json(['status'=>'success'],200);    
            }
         }
        if(\Auth::attempt($request->only('email', 'password' ), true)) {
            return response()->json(['status'=>'success'],200);
        }
        else{
            return response()->json(['status'=>'incorrect','message' => $user->provider == 'facebook' ? 'Secret token not match' :  'Password not match'],422);
        }   
    }

    public function sendUpdateEmailLink(UpdateEmailRequest $request){
    
        $user = $this->user;

        if($user->provider == 'facebook'){
            if($user->secret_token == $request->get('password')){
                $link = $user->getEmailConfirmCode();
                $data = array(
                            'user'              =>  $user->name ? $user->name : $user->first_name,
                            'email'             =>  $user->email,
                            'activationLink'    =>  $link,
                            'subject'           =>  'Change email confirmation link'
                        );
                $user = \App\User::find($user->id);
                $user->new_email = $request->get('email');
                $user->email_act_link = $link;
                $user->save();
                $response = \App\libraries\MailHelper::sendEmail('emails.email-change-link', $data); 
                return response()->json(['status'=>'success'],200);
            }
        }

        $request['email'] = $user->email;
        if(\Auth::attempt($request->only('email', 'password' ), true)) {
            $link = $user->getEmailConfirmCode();
            $data = array(
                        'user'              =>  $user->name ? $user->name : $user->first_name,
                        'email'             =>  $user->email,
                        'activationLink'    =>  $link,
                        'subject'           =>  'Change email confirmation link'
                    );
            $user = \App\User::find($user->id);
            $user->new_email = $request->get('email');
            $user->email_act_link = $link;
            $user->save();
            $response = \App\libraries\MailHelper::sendEmail('emails.email-change-link', $data); 
            return response()->json(['status'=>'success'],200);
        }
        else {
            return response()->json(['status'=>'error' ,'message' => $user->provider == 'facebook' ? 'Secret token not match' :  'Password does not match'], 422);
        }
    }
        
    public function getArtistProfile($slug){

        $user = \App\User::where('slug',$slug)->first();
        if($user){
            // $genres = \App\Genres::limit(10)->get();
            $artist = \App\ArtistsProfile::with(['releaseTrack'])->where('user_id',$user->id)->first();
            //followers data
            // with(['genres','releaseTrack','labelGenres'])
            if($artist) {

                $artist->genres = $user->artistGenres()->get();
                $artist->labelGenres = $user->artistLabelGenres()->get();
                $artist->email_notification = $user->email_notification;
                $artist->web_notification = $user->web_notification;

                $followerIds = \App\Follower::where('user_id',$user->id)->orderBy('created_at','DESC')->lists('follower_id');
                $artist['followers'] = \App\User::whereIn('id', $followerIds)->orderBy('id',$followerIds)->get();
                // $ids = $followerIds;
                // $ids[] =  $user->id;
                $artist['activities'] = \App\Activity::where('user_id', $user->id)
                                                    // ->with(['owner','track','profile','follower','following'])
                                                    ->with(['owner','track','profile','follower','following','favouriteCampaign','favouriteRemix','favouriteVideo','downloadCampaign','downloadRemix','downloadVideo','downloadTrack'])
                                                    // ->where('object_id','!=',$user->id)
                                                    ->orderBy('created_at','desc')
                                                    ->paginate(10);
                $artist['activities'] = array(
                    'data' => $artist['activities']->items(), 
                    'current_page' =>  $artist['activities']->currentPage(),
                    'last_page' =>  $artist['activities']->lastPage()
                );                                                    
                $artist->user_type = $user->user_type;
                $artist->track_name = $artist->releaseTrack['track_name'];
                $artist->album_image = $artist->releaseTrack['album_image'];
                $artist->record_label = $artist->releaseTrack['record_label'];
                $artist->purchase_link = $artist->releaseTrack['purchase_link'];

                $demoTrackIds = \App\TrackDemo::where('user_id',$user->id)
                                            ->where('status', 'approved')
                                            ->lists('id');

                $campaignTrackIds = \App\Campaign::where('user_id',$user->id)
                                            ->where('status', 1)
                                            ->lists('id');                                      

                $followingCount = \App\Follower::where('user_id', $user->id)->whereRaw('followers.user_id!=followers.follower_id')->count();
                $fanCount = \App\Follower::join('users','followers.follower_id','=','users.id')->where('follower_id', $user->id)->whereRaw('followers.user_id!=followers.follower_id')->count();
                $trackCount = \App\Campaign::where('user_id', $user->id)->where('status',1)->count();
                // $trackIds = \App\TrackDemo::where('status', 'approved')->where('user_id', $user->id)->lists('id');
                // $likeCount = \App\Favourite::whereIn('track_id', $trackIds)->count();
                $trackLikeCount = \App\Favourite::where('user_id', $user->id)->count();
                $videoLikeCount = \App\FavouriteVideo::where('user_id', $user->id)->count();
                $likeCount = $trackLikeCount + $videoLikeCount;

                $videoReleaseCount = \App\StmVideoRelease::where('artist_id', $user->id)->count();

                $countries =   config('constants.countries');
                if($countries){
                    foreach ($countries as $key => $value) {
                        if($value['name'] == $artist->country)
                            $artist->country_code = $value['alpha2'];
                    }
                }

                $now = new \DateTime();
                $activePlans = \App\PlanBilling::join('plans', 'plans_billing.plan_id', '=', 'plans.id')
                                ->where('user_id',$user->id)
                                ->where('start_date', '<=' , $now)
                                ->where('expire_date', '>=', $now)
                                ->orderBy('start_date')
                                ->select('plans_billing.plan_id', 'plans_billing.id', 'plans_billing.expire_date','plans_billing.start_date','plans.name','plans_billing.discover_demo_limit','plans_billing.remix_demo_limit','plans_billing.video_demo_limit')
                                ->get()
                                ->toArray();
                $currentPlan = isset($activePlans[1]) ? $activePlans[1] : (isset($activePlans[0]) ? $activePlans[0] : null);
                $subscriptionFeatures = \App\PlanFeature::join('plans','plan_features.plan_id','=','plans.id')
                                                        ->join('subscription_features','plan_features.feature_id','=','subscription_features.id')
                                                        ->where('plans.id',$currentPlan['plan_id'])
                                                        ->lists('subscription_features.slug');
                // $artist->labelgenress = $artist->releaseTrack['genres'] ? explode(",",$artist->releaseTrack['genres']) : [];
                // $artist->labelgenress = explode(",",$artist->releaseTrack['genres']);
                // $artist->releaseTracks = $artist->releaseTrack;
                return response()->json(['status'=>'success','data'=>$artist, 'followingCount' => $followingCount, 'fanCount' => $fanCount, 'trackCount' => $trackCount, 'likeCount' => $likeCount, 'demoTrackIds' => $demoTrackIds,'campaignTrackIds' => $campaignTrackIds, 'videoReleaseCount' =>$videoReleaseCount,'subscriptionFeatures'=>$subscriptionFeatures],200);
            } else {
                return response()->json(['status'=>'error','message'=>'Artist not found'], 422);
            }
        } else {
            return response()->json(['status'=>'error','message'=>'Artist not found '], 422);
        }
    }

    public function getMoreActivities(Request $request){
        $user = $this->user;
        $limit = $request->get('limit',10);
        $artist['activities'] = \App\Activity::where('user_id', $user->id)
                                            ->with(['owner','track','profile','follower','following'])
                                            ->orderBy('created_at','desc')
                                            ->paginate($limit);
        $artist['activities'] = array(
                    'data' => $artist['activities']->items(), 
                    'current_page' =>  $artist['activities']->currentPage(),
                    'last_page' =>  $artist['activities']->lastPage()
                );                                             
        return response()->json(['status'=>'success','data'=>$artist],200);                                            
    }
    public function updateArtistBio(Request $request){
        $user = $this->user;
        if($user){
            $artist = \App\ArtistsProfile::where('user_id',$user->id)->first();
            $bioData = $request->get('bio');
            $bioData = str_replace("<div>",'<br/>', $bioData);
            $bioData = str_replace("<br></div>",'', $bioData);
            $bioData = str_replace("</div>",'', $bioData);
            // $bioData = str_replace("</div>",'<br/>', $bioData);
            // $artist->bio = $bioData;
            // $artist->bio = $request->get('bio');
            $artist->bio = $bioData == "<br>" ? '' : $bioData;
            // // $bio = $request->get('bio');
            // // $artist->bio = strip_tags($bio);
            $artist->save();
            return response()->json(['status'=>'success','data'=>$artist],200);
        }
        return response()->json(['status'=>'error','message'=>'Artist not found '], 422);
    }

    public function updateArtistProfileImage(UpdateProfileImageRequest $request)
    {
        /*if($request->hasFile('file'))
        {
            $file = $request->file('file');
            $destinationPath = public_path('uploads/artist/profiles');
            $fileName = \App\libraries\GlobalHelper::uploadImage($file,$destinationPath);
            $fileName = 'uploads/artist/profiles'.'/'.$fileName;
            $user = $this->user;

            $artist = \App\ArtistsProfile::where('user_id',$user->id)->first();
            $artist->avatar = $fileName;
            $user->avatar = $fileName;
            $user->save();
            $artist->save();
            return response()->json(['status'=>'success','data'=>$artist],200); 
        }*/

        if ($request['file']) {
            $data = $request['file'];
            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);

            $fileName = strtolower(rand(11111,99999).'.jpg');
            $fileName = 'uploads/artist/profiles/'.$fileName;
            file_put_contents($fileName, $data);

            $user = $this->user;
            $artist = \App\ArtistsProfile::where('user_id',$user->id)->first();
            $artist->avatar = $fileName;
            $user->avatar = $fileName;
            $user->save();
            $artist->save();
            return response()->json(['status'=>'success','data'=>$artist],200);
        }

        return response()->json(['status'=>'error','message'=>'Something went wrong.Please try again.','code'=>'500'],422);
        // return response()->json(['status'=>'error','message'=>'Artist not found '], 401);   
    }
    public function updateArtistBannerImage(Request $request)
    {
        /*$inputs = $request->all();
        if($request->hasFile('file'))
        {
            $file = $request->file('file');
            $destinationPath = public_path('frontweb/images/artist/banners');
            $fileName = \App\libraries\GlobalHelper::uploadImage($file,$destinationPath);
            $fileName = 'frontweb/images/artist/banners'.'/'.$fileName;

            $user = $this->user;
            $artist = \App\ArtistsProfile::where('user_id',$user->id)->first();
            $artist->cover = $fileName;
            $artist->save();
            return response()->json(['status'=>'success','data'=>$artist],200);   
        }*/

        if ($request['file']) {
            $data = $request['file'];
            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);

            $fileName = strtolower(rand(11111,99999).'.jpg');
            $fileName = 'frontweb/images/artist/banners/'.$fileName;
            file_put_contents($fileName, $data);

            $user = $this->user;
            $artist = \App\ArtistsProfile::where('user_id',$user->id)->first();
            $artist->cover = $fileName;
            $artist->save();
            return response()->json(['status'=>'success','data'=>$artist],200);
        }

        // return response()->json(['status'=>'error','message'=>'Artist not found '], 401);
        return response()->json(['status'=>'error','message'=>'Something went wrong.Please try again.','code'=>'500'],422); 
    }

    public function selectBannerImage(Request $request) {
        $fileUrl = $request->get('file');
        if($fileUrl) {
            $fileInfo = explode('.', $fileUrl);
            $extension = end($fileInfo);
            $filename = strtolower(date("dmYhis").'.'.$extension);
            $destinationPath = public_path('frontweb/images/artist/banners').'/'.$filename;
            $filename = 'frontweb/images/artist/banners'.'/'.$filename;
            $user = $this->user;
            $artist = \App\ArtistsProfile::where('user_id',$user->id)->first();
            $artist->cover = $filename;
            $artist->save();
            @copy($fileUrl,$destinationPath);

            return response()->json(['status'=>'success','data'=>$artist->cover],200);
        }
        return response()->json(['status'=>'error','message'=>'Something went wrong.Please try again.','code'=>'500'],422); 
    }


    /*
        make donation 
    */

    public function makeDonation(Request $request)
    {
        // \Stripe\Stripe::setApiKey("sk_test_1vpqP9KVCSVTOxniy34jsNV0");
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $amount  = $request->get('amount');
            $amount = $amount * 100 ;
            $name    = $request->get('name');
            $comment    = $request->get('comment') ? $request->get('comment') : '' ;
            $token   = $request->get('stripeToken');
            $artist_slug   = $request->get('artist');
            $artist_id =\App\User::where('slug',$artist_slug)->select('id')->first();
            $user = $this->user;
            $metadata = array("Doner Name" => $name , "Email" => $user->email , "ID" =>$user->id);
           
                $charge = \Stripe\Charge::create(array(
                    "amount" => $amount,
                    "currency" => "usd",
                    "source" => $token,
                    "metadata" => $metadata,
                    // "description" => "Payment for :".$legacy->getName()." legacy, by ".$user->getUsername().". ID: ".$userid
                    "description" => $comment
                    ));
            
            $donation = new \App\Donation;
            $user = $this->user;
            $donation->user_id = $user->id;
            $donation->artist_id = $artist_id->id;
            $donation->donation_amount = $amount / 100;
            $donation->comment = $comment;
            $donation->transaction_id = $charge->id;
            $donation->save();
            return response()->json(['status'=>'success','data'=>$donation],200);
    }

    // public function getActivities($slug){
    //     $user = \App\User::where('slug',$slug)->first();
    //     if($user){
    //         $ids = \App\Follower::where('user_id',$user->id)
    //                         ->lists('follower_id');
    //         $ids[] =  $user->id;
    //         $activities = \App\Activity::whereIn('user_id', $ids)
    //                                     ->orWhere('sender_id', $user->id)
    //                                     ->with(['owner','track','profile','follower','following'])
    //                                     ->get();
    //         return response()->json(['status'=>'success','data'=>$activities],200);
    //     }
    //     return response()->json(['status'=>'error','message'=>'User not found '], 401);
    // }

    public function getFollowersTracks(Request $request){
        $limit = $request->get('limit',10);
        $order = $request->get('order');
        $genres = $request->get('geners'); 
        $sub_genre = $request->get('sub_genre'); 
        $moods = $request->get('moods');

        $campaignTracks = \App\Campaign::query();
        $selectedSubGenres = [];
         $user = $this->user;
        if($order){
            if($order == "-favourite_count" ){
                $campaignTracks = $campaignTracks->leftJoin(
                                                    \DB::raw("
                                                        (select
                                                            `favourites`.`campaign_id`
                                                            from `favourites`
                                                            where `favourites`.`type` = 'campaign'
                                                            group by `favourites`.`campaign_id`) `favourites`
                                                    "), 'campaigns.id', '=', 'favourites.campaign_id'
                                                );
            }

            if($order == "-play_count" ){
                $campaignTracks = $campaignTracks->leftJoin(
                                                    \DB::raw("
                                                        (select
                                                            `play_history`.`campaign_id`
                                                            from `play_history`
                                                            where `play_history`.`type` = 'campaign'
                                                            group by `play_history`.`campaign_id`) `play_history`
                                                    "), 'campaigns.id', '=', 'play_history.campaign_id'
                                                );
            }

            if($order == "-download_count" ){
                $campaignTracks = $campaignTracks->leftJoin(
                                                    \DB::raw("
                                                        (select
                                                            `download_history`.`campaign_id`
                                                            from `download_history`
                                                            group by `download_history`.`campaign_id`) `download_history`
                                                    "), 'campaigns.id', '=', 'download_history.campaign_id'
                                                );
            }

            if($order == "DESC" || $order == "ASC"){ 
                $campaignTracks = $campaignTracks->orderBy('created_at', $order);
            }
        }


        if($genres){
            $campaignGenreIds = \App\CampaignGenres::where('genre_id',$genres)
                                                    ->lists('campaign_id');
            $campaignTracks = $campaignTracks->whereIn('id', $campaignGenreIds);

            $subGenresId = \App\Genres::where('parent_id',$genres)->lists('id');

            $userIds = \App\Follower::where('user_id', $user->id)
                                ->lists('follower_id')
                                ->toArray();

            $followersCampaignIDs = [];
            if($userIds){
                $followersCampaignIDs = $campaignTracks->whereIn('user_id', $userIds)
                                            ->whereRaw('id in (select max(id) from campaigns where user_id in ('.implode(",", $userIds).') group by user_id order by created_at )')
                                            ->lists('id');
            }

            $subGenres = \App\CampaignGenres::join('campaigns','campaign_genres.campaign_id','=','campaigns.id')
                                                ->whereIn('campaign_genres.genre_id',$subGenresId)
                                                ->where('campaign_genres.type','sub')
                                                ->whereIn('campaigns.id',$followersCampaignIDs)
                                                ->lists('campaign_genres.genre_id');
            $selectedSubGenres = \App\Genres::whereIn('id',$subGenres)->get();
        }


        if($sub_genre){
            $trackSubGenreIds = \App\CampaignGenres::where('genre_id', $sub_genre)
                                                    ->where('type', 'sub')
                                                    ->lists('campaign_id'); 
            $campaignTracks = $campaignTracks->whereIn('id', $trackSubGenreIds);
        }

        if($moods){
            $campaignMoodIds = \App\CampaignMoods::where('mood_id',$moods)
                                                    ->lists('campaign_id');
            $campaignTracks = $campaignTracks->whereIn('id', $campaignMoodIds);
        }


        // $user = $this->user;

        if($user){
            $ids = \App\Follower::where('user_id', $user->id)
                                ->lists('follower_id')
                                ->toArray();
            if(!$ids){
                $campaignTracks = array(
                    'data' => [], 
                    'current_page' =>  1,
                    'last_page' =>  1,
                    'subGenres' =>  []
                );
                
                return response()->json($campaignTracks);    
            }
            $campaignTracks = $campaignTracks->whereIn('user_id', $ids)
                                            ->whereRaw('id in (select max(id) from campaigns where user_id in ('.implode(",", $ids).') group by user_id order by created_at )')
                                            ->with(['profile'])
                                            ->select('campaigns.*')
                                            ->paginate($limit);

            foreach($campaignTracks->items() as $item) {
                if ($item->type == 'original' && $item->external_download_link != null && $item->external_download_link != '') {
                    $item->mp3_file = $item->external_download_link;
                }
            }

            // echo '<pre>'; print_r($campaignTracks); die; 
            $campaignTracks = array(
                'data' => $campaignTracks->items(), 
                'current_page' =>  $campaignTracks->currentPage(),
                'last_page' =>  $campaignTracks->lastPage(),
                'subGenres' =>  $selectedSubGenres
            );
            
            return response()->json($campaignTracks);
            // return response()->json(['status'=>'success','data'=>$trackDemo],200);
        }
        
        return response()->json(['status'=>'error','message'=>'User not found '], 422);
    }

    public function getMoreFollowersTracks(Request $request){
        $limit = $request->get('limit',10);
        $order = $request->get('order');
        $genres = $request->get('geners'); 
        $sub_genre = $request->get('sub_genre'); 
        $moods = $request->get('moods');

        $campaignTracks = \App\Campaign::query();
        $selectedSubGenres = [];

        if($order){
            if($order == "-favourite_count" ){
                $campaignTracks = $campaignTracks->leftJoin(
                                                    \DB::raw("
                                                        (select
                                                            `favourites`.`campaign_id`
                                                            from `favourites`
                                                            where `favourites`.`type` = 'campaign'
                                                            group by `favourites`.`campaign_id`) `favourites`
                                                    "), 'campaigns.id', '=', 'favourites.campaign_id'
                                                );
            }

            if($order == "-play_count" ){
                $campaignTracks = $campaignTracks->leftJoin(
                                                    \DB::raw("
                                                        (select
                                                            `play_history`.`campaign_id`
                                                            from `play_history`
                                                            where `play_history`.`type` = 'campaign'
                                                            group by `play_history`.`campaign_id`) `play_history`
                                                    "), 'campaigns.id', '=', 'play_history.campaign_id'
                                                );
            }

            if($order == "-download_count" ){
                $campaignTracks = $campaignTracks->leftJoin(
                                                    \DB::raw("
                                                        (select
                                                            `download_history`.`campaign_id`
                                                            from `download_history`
                                                            group by `download_history`.`campaign_id`) `download_history`
                                                    "), 'campaigns.id', '=', 'download_history.campaign_id'
                                                );
            }

            if($order == "DESC" || $order == "ASC"){ 
                $campaignTracks = $campaignTracks->orderBy('created_at', $order);
            }
        }


        if($genres){
            $campaignGenreIds = \App\CampaignGenres::where('genre_id',$genres)
                                                    ->lists('campaign_id');
            $campaignTracks = $campaignTracks->whereIn('id', $campaignGenreIds);

            $subGenresId = \App\Genres::where('parent_id',$genres)->lists('id');
            $subGenres = \App\CampaignGenres::whereIn('genre_id',$subGenresId)
                                                ->where('type','sub')
                                                ->lists('genre_id');
            $selectedSubGenres = \App\Genres::whereIn('id',$subGenres)->get();                                   
        }


        if($sub_genre){
            $trackSubGenreIds = \App\CampaignGenres::where('genre_id', $sub_genre)
                                                    ->where('type', 'sub')
                                                    ->lists('campaign_id'); 
            $campaignTracks = $campaignTracks->whereIn('id', $trackSubGenreIds);
        }

        if($moods){
            $campaignMoodIds = \App\CampaignMoods::where('mood_id',$moods)
                                                    ->lists('campaign_id');
            $campaignTracks = $campaignTracks->whereIn('id', $campaignMoodIds);
        }

        $user = $this->user;

        if($user){
            $ids = \App\Follower::where('user_id',$user->id)
                            ->lists('follower_id')
                            ->toArray();
            if(!$ids){
                $campaignTracks = array(
                    'data' => [], 
                    'current_page' =>  1,
                    'last_page' =>  1,
                    'subGenres' =>  []
                );
                
                return response()->json($campaignTracks);    
            }                            
            $campaignTracks = $campaignTracks->whereIn('user_id', $ids)
                                            ->whereRaw('id in (select max(id) from campaigns where user_id in ('.implode(",", $ids).') group by user_id order by created_at )')
                                            ->with(['profile'])
                                            ->select('campaigns.*')
                                            ->paginate($limit);

            foreach($campaignTracks->items() as $item) {
                if ($item->type == 'original' && $item->external_download_link != null && $item->external_download_link != '') {
                    $item->mp3_file = $item->external_download_link;
                }
            }

            $campaignTracks = array(
                                        'data' => $campaignTracks->items(), 
                                        'current_page' =>  $campaignTracks->currentPage(),
                                        'last_page' =>  $campaignTracks->lastPage(),
                                        'subGenres' =>  $selectedSubGenres
                                    );
            
            return response()->json($campaignTracks);
            // return response()->json(['status'=>'success','data'=>$campaignTracks],200);
        }
        
        return response()->json(['status'=>'error','message'=>'User not found '], 422);
    }


    // public function getActivities(Request $request){
    //     // echo '<pre>'; print_r("innn"); die; 
    //     $slug = $request->get('slug');
    //     $user = \App\User::where('slug', $slug)->first();
        
    //     $activities = \App\Activity::where('user_id', $user->id)
    //                                     ->with(['owner','track','profile','follower','following'])
    //                                     ->get();
    //     // echo '<pre>'; print_r($activities->toArray()); die; 
    //     return response()->json(['status'=>'success','data'=>$activities],200);
    // }
    public function destroy(Request $request)
    {
        
        $user = $this->user;

        if($user->provider == 'facebook'){
            if($user->email == $request->get('email') && $user->secret_token == $request->get('password')){
                $user->status = '0';
                $user->save();
                $data = array(
                    'user'              =>  $user->name ? $user->name : $user->first_name,
                    'email'             =>  $user->email,
                    // 'subject'           =>  'Your Sore Thumb Media account deactivated'
                    'subject'           => 'Account deactivated'
                );

                $response = \App\libraries\MailHelper::sendEmail("emails.templates.deactivate-account", $data);
                \Auth::logout();
                return response()->json(['status'=>'success'],200);
            }
         }


        if(\Auth::attempt($request->only('email', 'password' ))) {
            $user->status = '0';
            $user->save();
            $data = array(
                'user'              =>  $user->name ? $user->name : $user->first_name,
                'email'             =>  $user->email,
                // 'subject'           =>  'Your Sore Thumb Media account deactivated'
                'subject'           => 'Account deactivated'
            );

            $response = \App\libraries\MailHelper::sendEmail("emails.templates.deactivate-account", $data);
            \Auth::logout();
            return response()->json(['status'=>'success'],200);
        }
        else{
            return response()->json(['status'=>'incorrect','message' => $user->provider == 'facebook' ? 'Secret token not match' :  'Password does not match'], 400);
        }

        // if($user){
        //     $user->status = '0';
        //     $user->save();
        // }
        

        // $artist = \App\ArtistsProfile::where('user_id',$user->id)->first();
        // if($artist)
        //   $artist->delete();

        
        // if($user)
        //   $user->delete();
        
        // return response()->json(['status'=>'success'], 200);
    }

    public function saveYoutubeChannel(Request $request){
        $youtubeChannel = $request->get('youtube_channel');
        
        if($youtubeChannel) {
            $user = $this->user;
            if($user) {
                $artist = \App\ArtistsProfile::where('user_id',$user->id)->first();
                $artist->youtube_channel = $youtubeChannel;
                $artist->save();    
                return response()->json(['success', 'success']);
            } else {
                return response()->json(['error', 'access denied'], 401);
            }
        } else {
            return response()->json(['error', 'Please enter youtube channel']);
        }
        
    }

    public function saveFacebookPage(Request $request){
        $fbPage = $request->get('fb_page');
        
        if($fbPage) {
            $user = $this->user;
            if($user) {
                $artist = \App\ArtistsProfile::where('user_id',$user->id)->first();
                $artist->fb_page = $fbPage;
                $artist->save();    
                return response()->json(['success', 'success']);
            } else {
                return response()->json(['error', 'access denied'], 401);
            }
        } else {
            return response()->json(['error', 'Please enter facebook page']);
        }
    }


    public function getNotification(Request $request){
        if($this->user) {
            $user = $this->user;
            $userNotiIds = \App\User::where('id',$user->id)->select('notifications','last_checked_date')->first();
            $globalNotiIds = !empty($userNotiIds->notifications) ? explode(',',$userNotiIds->notifications) : [];

            $notifications_all = \App\Notification::where(function($query) use ($user,$globalNotiIds){
                                                        $query->where('user_id', $user->id)
                                                        ->orWhere(function($query1) use ($globalNotiIds){
                                                            $query1->whereIn('id',$globalNotiIds);
                                                        });
                                                    })
                                                    ->where(function($q){
                                                        $q->where('type', '!=', 'competition');
                                                        $now = date('Y-m-d');
                                                        $todayCompetition = \App\Competition::where('competitions.start_date','<=',$now)
                                                                                        ->where('competitions.end_date','>=',$now)
                                                                                        ->where('status',1)
                                                                                        ->orderBy('competitions.created_at', 'DESC')
                                                                                        ->select('competitions.*')->first();
                                                        if($todayCompetition) {
                                                            $q->orWhere(function($sq) use($todayCompetition){
                                                                $sq->where('type', 'competition')
                                                                    ->where('ref_id', $todayCompetition->id);
                                                            });
                                                        }
                                                    })
                                                    ->where('is_read',0)
                                                    ->select('comments','id','type')
                                                    ->orderBy('created_at','DESC');
            $notifications = $notifications_all->get();
            $notifications_count = $userNotiIds->last_checked_date ? $notifications_all->where('created_at','>',$userNotiIds->last_checked_date)->count() : $notifications->count();
            // $notifications = \App\Notification::where(function($query) use ($user,$globalNotiIds){
            //                                             $query->where('user_id', $user->id)
            //                                             ->orWhere(function($query1) use ($globalNotiIds){
            //                                                 $query1->whereIn('id',$globalNotiIds);
            //                                             });
            //                                         })
            //                                         ->where('is_read',0)->select('comments','id','type')->orderBy('created_at','DESC')->get();
            // $notifications_count = $userNotiIds->last_checked_date ? \App\Notification::where('created_at','>',$userNotiIds->last_checked_date)->count() : $notifications->count();
            return response()->json(['status'=>'success','data'=>$notifications,'notifications_count'=>$notifications_count],200);
        }
    }

    public function removeNotification(Request $request){
        $id = $request->get('id');
        if($id) {
            $notification = \App\Notification::find($id);
            $notification->is_read = $notification->isGlobal == 1 ? 0 : 1;
            $notification->save();
            if($notification->isGlobal == 1){
                $user = \App\User::where('id',$this->user->id)->first();
                $globalNotiIds = explode(',', $user->notifications);
                $globalNotiIds = array_diff($globalNotiIds, array($id));
                $globalNotiIds = empty($globalNotiIds) ? null : implode(',', $globalNotiIds);
                $user->notifications = $globalNotiIds;
                $user->save();
            }
            return response()->json(['status'=>'success'],200);
        }
    }

    public function removeNotificationCount(){
        $user = \App\User::where('id',$this->user->id)->first();
        if($user){
            $user->last_checked_date = $now = new \DateTime();
            $user->save();
            return response()->json(['status'=>'success'],200);
        }
    }


    public function getActivities(Request $request){
        $user = $this->user;
        $activities = \App\Activity::where('user_id', $user->id)
                                                    ->with(['owner','track','profile','follower','following','favouriteCampaign','favouriteRemix','favouriteVideo','downloadCampaign','downloadRemix','downloadVideo','downloadTrack'])
                                                    ->orderBy('created_at','desc')
                                                    ->get();
        return response()->json(['status'=>'success','data'=>$activities],200);
    }

    public function getFollowings(){
        $user = $this->user;
        $followerIds = \App\Follower::where('user_id',$user->id)->lists('follower_id');
        $followers = \App\User::whereIn('id', $followerIds)->get();
        return response()->json(['status'=>'success','data'=>$followers],200);
    }

    public function changePermission(Request $request){
        $status = $request->get('status');
        $type = $request->get('type');
        $user = $this->user;
        if($type == 'email')
            $user->isEmail = $status == 'true' ? 'false' : 'true';
        if($type == 'notification')
            $user->isNotification = $status == 'true' ? 'false' : 'true';
        $user->save();
        return response()->json(['status'=>'success','data'=>$type =='email' ?  $user->isEmail : $user->isNotification],200);
    }

    public function welcomePopup(){
        $user = $this->user;
        if($user) {
            $user->welcome_popup = 0;
            $user->save();
            return response()->json(['status'=>'success'],200);
        } else {
            return response()->json(['status'=>'error'],422);
        }
    }
}