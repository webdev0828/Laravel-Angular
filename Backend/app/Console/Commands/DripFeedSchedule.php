<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Njasm\Soundcloud\SoundcloudFacade;
use Njasm\Soundcloud\Soundcloud;
class DripFeedSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dripfeed:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drip Feed process';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      //  $this->info("here");
        $this->processDripFeed();
    }

    public function processDripFeed(){
        $isStart = false;
        $settings = \App\Setting::where('name', 'dripfeed')->first(); 
        $currentTime = strtotime(date('Y-m-d H:i:s'));

        if($settings){
            if($settings->current_status ==1){

                $lastCheckedTime = $settings->last_checked_date;
                if(empty($lastCheckedTime))
                {
                    $lastCheckedTime = strtotime(date('Y-m-d H:i:s'));
                }
                else{
                    $lastCheckedTime = $lastCheckedTime;
                    $lastCheckedTime =   date("Y-m-d H:i:s", strtotime("+".$lastCheckedTime." minutes", strtotime($lastCheckedTime)));
                    $lastCheckedTime = strtotime($lastCheckedTime);
                }

                if($currentTime >= $lastCheckedTime){
                    $isStart = true;    
                }
            }
        }
    //    $isStart = true;  
        if($isStart == true){
            $dripFeedInfo = \App\DripFeed::limit(1)->get();
               // $this->info("processDripFeed");
            if($dripFeedInfo){
                foreach ($dripFeedInfo as  $feed) {
                    $dripId = $feed->id;
                    if($feed->track_type =="discover"){
                        $trackId = $feed->track_id;
                        $this->discoverTrackProcess($feed);
                        \DB::table('drip_feeds')->where('id',$dripId)->delete();
                        $settings->last_checked_date = date('Y-m-d H:i:s');
                     }
                    elseif($feed->track_type =="remix"){
                         $this->RepostDripFeed($feed);
                        \DB::table('drip_feeds')->where('id',$dripId)->delete();
                   }
                }
            }
            // $settings->last_checked_date = date('Y-m-d H:i:s');
            // $settings->save();
        }
        $settings->last_checked_date = date('Y-m-d H:i:s');
        $settings->save();

    }

    public function discoverTrackProcess($feed){
        $user = \App\User::where('id',$feed->user_id)->first();
        $trackDemo = \App\TrackDemo::find($feed->track_id); 
        $trackDemo->status="approved";
        $trackDemo->save();

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
        }
     }//discoverTrackProcess

    public function RepostDripFeed($feed){
        $admin = \App\User::where('user_type', 'admin')->first();
        $userId = $admin->id;
      //   $this->info("userId".$userId);
        $trackDemo = \App\TrackDemo::find($feed->track_id); 
        $trackId = $trackDemo->sc_id;

        $isPrivate = \App\SoundcloudArtist::where('artist_id', $trackDemo->user_id)->where('sc_id',$trackId)->first();
        // $this->info("sharing".$isPrivate->sharing);
        // if($isPrivate && $isPrivate->sharing == 'private'){
        //      echo "private";
        //     return false;
        // }

        $clientId = env('GATING_SOUNDCLOUD_KEY');
        $clientSecret = env('GATING_SOUNDCLOUD_SECRET');
        $redirectUrl = env('GATING_SOUNDCLOUD_REDIRECT_URI');

        $oauthIdentity = \App\OauthIdentity::where('user_id', $userId)
                                                        ->where('provider', 'soundcloud')
                                                        ->first();
        if(!$oauthIdentity){
            // $this->info("aoitend");
            return false;
        }else{
           // $this->info("sharing".$oauthIdentity->access_token);
            $accessToken = $oauthIdentity->access_token;
            $facade = new SoundcloudFacade($clientId, $clientSecret, $redirectUrl);
            $facade->setAccessToken($accessToken);
            try{
                $response = $facade->put('/e1/me/track_reposts/'.$trackId)->asJson()->request();
               // print_r($response);
            }
            catch(\Exception $e){
                //print_r($e);
                return false;
            }

            $user = \App\User::where('id', $trackDemo->user_id)->first();
            
            if($user->email_notification ==1){
                $data = array(
                    'user' =>  $user->name,
                    'email'=>  $user->email,
                    'track'=>  $trackDemo,
                    'subject' => 'Your recent submission for Repost'
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

           $trackDemo->status = "approved";
            $trackDemo->save();
            return true;
        }//else
    }

}
