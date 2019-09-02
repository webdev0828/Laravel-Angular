<?php namespace App\Http\Controllers\FrontWeb;

use App\Http\Requests;
use Illuminate\Http\Request;
use Socialite;

class APiController extends BaseController {

	function __construct()
    {
        // construct
        parent::__construct();
    }

	public function sendEmailNotification() {
		$emailSend = \App\AdminSetting::find(1)->email_send;
		if ($emailSend) {
			$data = array(
				'email' 	=> 'chew@sorethumbmedia.co.uk',
				'subject'	=> 'Demo Cue'
			);
			$response = \App\libraries\MailHelper::sendEmail("emails.templates.submit-new-queue", $data);
		}
	}

    /*
		Get Compititions
	*/
	public function getCompititions(Request $request){
		$limit = $request->get('limit',9) ;
		$user = $this->user;
		$response = [];
		$participated = 0;
		$soundCloudTracks = [];
		$runnerUp = [];
		$now = date('Y-m-d');

		$data['currentCompetition'] = \App\Competition::join('campaigns','competitions.track_id','=','campaigns.id')
														->join('users','campaigns.user_id','=','users.id')
														->where('competitions.start_date','<=',$now)
										   			    ->where('competitions.end_date','>=',$now)
                                           				->where('competitions.status',1)
										   				->orderBy('competitions.created_at', 'DESC')
										   				->select('campaigns.id as id','campaigns.track_name as name','campaigns.cover_image','competitions.*','users.name as artist_name','users.slug as artist_slug')
                                           				->first();
		if($data['currentCompetition']){
			$data['currentCompetition']['campaign'] = \App\Campaign::where('id',$data['currentCompetition']->track_id)->first();
		}

		$data['pastCompetition'] = \App\Competition::join('campaigns','competitions.track_id','=','campaigns.id')
													// ->where('competitions.announcement_date', '<', date('Y-m-d H:i:s'))
													->whereNotNull('competitions.published_date')
													->whereRaw('DATE_FORMAT(competitions.published_date, "%Y-%m-%d") > DATE_FORMAT(competitions.end_date, "%Y-%m-%d")')
													->whereRaw('DATE_FORMAT(competitions.published_date, "%Y-%m-%d") <= NOW()')
 													->orderBy('competitions.created_at', 'DESC')
													->select('campaigns.track_name as name','campaigns.cover_image','competitions.*')
													->get();

        foreach ($data['pastCompetition'] as $value) {
        	$campaign = \App\Campaign::where('id',$value->track_id)->select('user_id')->first();
        	$artist = \App\User::where('id',$campaign->user_id)->select('slug','name')->first();
        	$value['artist_slug'] = $artist->slug;
        	$value['artist_name'] = $artist->name;
        }

        $competitonId = \App\CompetitionWinner::join('competitions','competition_winners.competition_id','=','competitions.id')
         								  	  	->whereNotNull('competitions.published_date')
         								  	  	->whereRaw('DATE_FORMAT(competitions.published_date, "%Y-%m-%d") > DATE_FORMAT(competitions.end_date, "%Y-%m-%d")')
         								  	   	->whereRaw('DATE_FORMAT(competitions.published_date, "%Y-%m-%d") <= NOW()')
         								  	   	->orderBy('competition_winners.updated_at', 'DESC')
        								  	   	->select('competition_winners.competition_id')
        								 	   	->first();


        $data['latestWinner'] = [];
       	if($competitonId){
			$latestCampaignId = \App\CompetitionWinner::where('competition_id',$competitonId->competition_id)
												   		->where('position','=',1)
												   		->select('campaign_id','winner_id','video_id')
												   		->first();
			if($latestCampaignId){

				// $data['latestWinner'] =  \App\Campaign::where('id', $latestCampaignId->campaign_id)->first();
				$data['latestWinner'] = \App\StmVideoRelease::
                                            with(array('campaign'=>function($q){
                                                    $q->select('id', 'track_name', 'slug');
                                                }
                                            ))
                                            ->where('stm_video_releases.id',$latestCampaignId->video_id)
                                            ->select('stm_video_releases.*')
                                            ->first();


				$artist = \App\User::where('id',$latestCampaignId->winner_id)->first();
				$data['latestWinner']['participated_artist_slug'] = $artist->slug;
				$data['latestWinner']['participated_artist_name'] = $artist->name ? $artist->name : $artist->first_name;
			}

			$campaignIds = \App\CompetitionWinner::where('competition_id',$competitonId->competition_id)
												   ->where('position','!=',1)
												   ->lists('campaign_id');

			$runnerUp = \App\Campaign::join('competition_winners','campaigns.id','=','competition_winners.campaign_id')
									 ->join('users','competition_winners.winner_id','=','users.id')
									 ->whereIn('campaigns.id', $campaignIds)
									 ->select('users.name as participated_artist_name','users.slug as participated_artist_slug','campaigns.*')
									 ->paginate($limit);

			$runnerUp = array(
	            'data' => $runnerUp->items(),
	            'current_page' =>  $runnerUp->currentPage(),
	            'last_page' =>  $runnerUp->lastPage()
	        );

       	}

		if($data['currentCompetition']){
			$data['currentCompetition']->genres = $data['currentCompetition']->genres ? explode(",",$data['currentCompetition']->genres) : [];

			if($user){
				$soundCloudTracks =\App\SoundcloudArtist::where('artist_id', $user->id)->get();
				$competitionRemix = \App\CompetitionArtist::where('competition_id', $data['currentCompetition']->id)
														->where('artist_id', $user->id)
														->first();
				if($competitionRemix){
					$participated = 1;
				}else{
					$participated = 0;
				}
			}
		}



		if($data){
			return response()->json(['data' => $data, 'soundCloudTracks' => $soundCloudTracks, 'participated' => $participated, 'runnerUp' => $runnerUp]);
		}
		return $response;
	}

	public function getMoreRunnerUp(Request $request){
		$limit = $request->get('limit',4);
		$now = date('Y-m-d');

		$data['currentCompetition'] = \App\Competition::where('competitions.start_date','<=',$now)
										   			    ->where('competitions.end_date','>=',$now)
                                           				->where('status',1)
										   				->orderBy('competitions.created_at', 'DESC')
                                           				->select('competitions.*')->first();

		$runnerUp = \App\CompetitionArtist::where('competition_id', $data['currentCompetition']->id)
												->select('competition_artists.*')
												->paginate($limit);
		$runnerUp = array(
            'data' => $runnerUp->items(),
            'current_page' =>  $runnerUp->currentPage(),
            'last_page' =>  $runnerUp->lastPage()
        );

        if($data){
			return response()->json(['runnerUp' => $runnerUp]);
		}

	}

	public function getPastCompetition(Request $request){
		// echo '<pre>'; print_r($request->get('slug')); die;
		$slug = $request->get('slug');
		$user = $this->user;

		$competition = \App\Competition::where('slug', $slug)->select('id')->first();

		// $tracks = \App\CompetitionArtist::where('competition_id', $competition->id)->get();

		// Get participated tracks
		$campaignIds = \App\CompetitionWinner::where('competition_id',$competition->id)
												   ->where('position','!=',1)
												   ->lists('campaign_id');
	    $tracks = [];
	    if($campaignIds){
	    	$tracks = \App\Campaign::join('competition_winners','campaigns.id','=','competition_winners.campaign_id')
									 ->join('users','competition_winners.winner_id','=','users.id')
									 ->whereIn('campaigns.id', $campaignIds)
									 ->select('users.name as participated_artist_name','users.slug as participated_artist_slug','campaigns.*')
									 ->get();
	    }

		// Get winner

		$latestCampaignId = \App\CompetitionWinner::where('competition_id',$competition->id)
												   ->where('position','=',1)
												   ->select('campaign_id','winner_id','video_id')
												   ->first();
	    $winner = [];
		if($latestCampaignId){
			$winner  = \App\StmVideoRelease::with(array('campaign'=>function($q){
                                                    $q->select('id', 'track_name', 'slug');
                                                }
                                            ))
                                            ->where('stm_video_releases.id',$latestCampaignId->video_id)
                                            ->select('stm_video_releases.*')
                                            ->first();
			$artist = \App\User::where('id',$latestCampaignId->winner_id)->first();
			$winner['participated_artist_slug'] = $artist->slug;
			$winner['participated_artist_name'] = $artist->name ? $artist->name : $artist->first_name;
		}

		$pastCompetition = \App\Competition::join('campaigns','competitions.track_id','=','campaigns.id')
													// ->where('competitions.announcement_date', '<', date('Y-m-d H:i:s'))
													->whereNotNull('competitions.published_date')
													->whereRaw('DATE_FORMAT(competitions.published_date, "%Y-%m-%d") > DATE_FORMAT(competitions.end_date, "%Y-%m-%d")')
													->whereRaw('DATE_FORMAT(competitions.published_date, "%Y-%m-%d") <= NOW()')
 													->orderBy('competitions.created_at', 'DESC')
													->select('campaigns.track_name as name','campaigns.cover_image','competitions.*')
													->get();
		foreach ($pastCompetition as $value) {

        	$campaign = \App\Campaign::where('id',$value->track_id)->select('user_id')->first();
        	$artist = \App\User::where('id',$campaign->user_id)->select('slug','name')->first();
        	$value['artist_slug'] = $artist->slug;
        	$value['artist_name'] = $artist->name;

        }
       	return response()->json(['tracks' => $tracks, 'winner' => $winner, 'pastCompetition' => $pastCompetition]);
	}

	public function addRemixTrack(Request $request){
		$user = $this->user;
		$scTrack = \App\SoundcloudArtist::where('sc_id', $request->get('sc_id'))
										->where('artist_id', $user->id)
										->with('genres')
										->first();

		if($scTrack){
			$notification = null;
			$competitionTrack = new \App\CompetitionArtist;
			$competitionTrack->competition_id = $request->get('id');
			$competitionTrack->artist_id = $user->id;
			$competitionTrack->mp3_file = $scTrack->url;
			$competitionTrack->description = $scTrack->description;
			$competitionTrack->cover_image = $scTrack->cover_image;
			$competitionTrack->track_name = $scTrack->track_name;
			$competitionTrack->sc_id = $scTrack->sc_id;
			$competitionTrack->save();

			//$this->sendEmailNotification();

			if($user){
                $data = array(
                'user'            =>  $user->name ? $user->name : $user->first_name,
                'email'             =>  $user->email,
                'track'             =>  $scTrack->track_name,
                // 'subject'           =>  'Thank you for your remix competition submission.'
                'subject'			=> 'Remix competition submission'
                );
                if($user->email_notification ==1){
                	$response = \App\libraries\MailHelper::sendEmail("emails.templates.submit-remix", $data);
                }


                $notificationData = [
                                'user_id' => $user->id,
                                'comments'=> 'Thank you for your recent remix competition submission. Please hold tight until the winners have been announced!',
                                'type' => 'remix_submition'
                            ];
                if($user->web_notification ==1){
					$notification = \App\libraries\GlobalHelper::addNotification($notificationData);
				}

            }
			if(count($scTrack->genres)){
				foreach ($scTrack->genres as $key => $value) {
					$competitionTrackGenres = new \App\CompetitionTrackGenres;
					$competitionTrackGenres->competition_track_id = $competitionTrack->id;
					$competitionTrackGenres->genre_id = $value['genre_id'];
					$competitionTrackGenres->save();
				}
			}


			return response()->json(['data' => $competitionTrack,'notification'=>$notification],200);
		}

	}

	public function submitDemos(Request $request){
		if($request->get('type') == 'video'){
			$scTrack = \App\SoundcloudArtist::where('sc_id', $request->get('slug'))
											->where('artist_id', $this->user->id)
											->with('genres')
											->first();
			$demoTrack = new \App\TrackDemo;
			$demoTrack->sc_id = $request->get('slug');
			$demoTrack->slug = str_slug($scTrack->track_name);
			$count = \App\TrackDemo::whereRaw("slug RLIKE '^{$demoTrack->slug}(-[0-9]+)?$'")->count();
			$demoTrack->slug = $count ? "{$demoTrack->slug}-{$count}" : $demoTrack->slug;

			$demoTrack->user_id = $this->user->id;
			$demoTrack->track_name = $scTrack->track_name;
			$demoTrack->type = 'music_video';
			$demoTrack->status = 'pending';
			$demoTrack->mp3_file = $scTrack->url;
			$demoTrack->download_url = $scTrack->download_url;
			$demoTrack->cover_image = $scTrack->cover_image;
			$demoTrack->save();

			$this->sendEmailNotification();

			$data = array(
                'user'            =>  $this->user->name ? $this->user->name : $this->user->first_name,
                'email'           =>  $this->user->email,
                // 'subject'           =>  'Thank you for your submission for STM Release'
                'subject'		  =>'STM Release submission'
            );
            if($this->user->email_notification ==1){
            	$response = \App\libraries\MailHelper::sendEmail("emails.templates.submit-stm-releases", $data);
            }


             $notificationData = [
                                'user_id' => $this->user->id,
                                'comments'=> 'Thank you for your recent submission for STM release. We will listen to it as soon as possible and be in touch.',
                                'type' => 'stm_release_submit'
                            ];
            if($this->user->web_notification ==1){
              $notification = \App\libraries\GlobalHelper::addNotification($notificationData);
          	}

			if(count($scTrack->genres)){
				foreach ($scTrack->genres as $key => $value) {
					$trackDemoGenres = new \App\TrackDemoGenres;
					$trackDemoGenres->demo_track_id = $demoTrack->id;
					$trackDemoGenres->genre_id = $value['genre_id'];
					$trackDemoGenres->type = 'parent';
					$trackDemoGenres->save();
				}
			}


		}else{

			$campaigns = \App\Campaign::where('slug', $request->get('slug'))
									->with('genres','subGenres','moods')
									->first();

			$campaigns = $campaigns->toArray();

			$demoTrack = new \App\TrackDemo;
			// unset($campaigns['slug']);
			$demoTrack->fill($campaigns);

			$demoTrack->type = $campaigns['type'] == 'original' ? 'discover' : 'remix';
			// $demoTrack->slug = str_slug($demoTrack->track_name);

			if($campaigns['soundcloud_tracks']){
				$demoTrack->sc_id = $campaigns['soundcloud_tracks'];
			}
			$demoTrack->campaign_id = $campaigns['id'];
			$demoTrack->status = 'pending';
			$demoTrack->save();

			$this->sendEmailNotification();

			$typeDemo = $demoTrack->type;
			if($typeDemo =='discover'){
				$typeDemo = 'Discover';
				$data = array(
                'user'            =>  $this->user->name ? $this->user->name : $this->user->first_name,
                'email'             =>  $this->user->email,
                'subject'           =>  'Thank you for your submission for '.$typeDemo
	            );
	            if($this->user->email_notification ==1){
	            	$response = \App\libraries\MailHelper::sendEmail("emails.templates.submit-discover", $data);
	            }
	            $notificationData = [
	                                'user_id' => $this->user->id,
	                                'comments'=> 'Thank you for your recent submission to '.$typeDemo.'. We will listen to it as soon as possible and be in touch.',
	                                'type' => 'submit_discover'
	                            ];
	            if($this->user->web_notification ==1){
	            	$notification = \App\libraries\GlobalHelper::addNotification($notificationData);
	            }

			}
			else{
				$typeDemo = 'Remix';
				$data = array(
                'user'            =>  $this->user->name ? $this->user->name : $this->user->first_name,
                'email'             =>  $this->user->email,
                'subject'           =>  'Thank you for your submission for Repost'
	            );
	            if($this->user->email_notification ==1){
	            	$response = \App\libraries\MailHelper::sendEmail("emails.templates.submit-repost", $data);
	            }
	            $notificationData = [
	                                'user_id' => $this->user->id,
	                                'comments'=> 'Thank you for your recent submission for repost. We will listen to it as soon as possible and be in touch.',
	                                'type' => 'submit_repost'
	                            ];
				if($this->user->web_notification ==1){
					$notification = \App\libraries\GlobalHelper::addNotification($notificationData);
				}
			}

			$genres = $campaigns['genres'];
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


            $subGenres = $campaigns['sub_genres'];
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

            $moods = $campaigns['moods'];
            $moods = array_column($moods, 'id');
            if($moods){
                $demoTrack->moods()->sync($moods);
                $demoTrack->moods;

            }else{
                $demoTrack->moods()->sync([]);
            }
		}

		return response()->json(['status'=>'success','data'=>$demoTrack],200);
	}


	public function submitRepost(Request $request){
		// echo '<pre>'; print_r($request->all()); die;
		$demoTrack = \App\TrackDemo::where('slug', $request->get('slug'))
									->with('genres','subGenres','moods')
									->first();

		$demoTrack = $demoTrack->toArray();

		$repostTrack = new \App\TrackDemo;
		unset($demoTrack['slug']);
		unset($demoTrack['created_at']);
		unset($demoTrack['updated_at']);
		$repostTrack->fill($demoTrack);
		$repostTrack->status = 'pending';
		$repostTrack->repost_track_id = $demoTrack['id'];
		$repostTrack->save();

		$genres = $demoTrack['genres'];
		$genres = array_column($genres, 'id');
		if($genres){
                $pivotData = array_fill(0, count($genres), ['type' => 'parent']);
                $syncData  = array_combine($genres, $pivotData);
                $repostTrack->genres()->sync($syncData);
                $repostTrack->genres;

            }else{
                $genres = [];
                $repostTrack->genres()->sync($genres);
            }

            $subGenres = $demoTrack['sub_genres'];
            $subGenres = array_column($subGenres, 'id');
            if($subGenres){
                $pivotData = array_fill(0, count($subGenres), ['type' => 'sub']);
                $syncData  = array_combine($subGenres, $pivotData);
                $repostTrack->subGenres()->sync($syncData);
                $repostTrack->subGenres;

            }else{
                $subGenres = [];
                $repostTrack->subGenres()->sync($subGenres);
            }

            $moods = $demoTrack['moods'];
            $moods = array_column($moods, 'id');
            if($moods){
                $repostTrack->moods()->sync($moods);
                $repostTrack->moods;

            }else{
                $repostTrack->moods()->sync([]);
            }
        return response()->json(['status'=>'success','data'=>$repostTrack],200);
		// echo '<pre>'; print_r($genres); die;
	}


	public function getDemoTracks(){
		$user= $this->user;
		$demoTracks = \App\TrackDemo::select('track_name', 'type', 'status', 'user_id', 'slug')
								    ->where('status','approved')
								    ->where('user_id', $user->id)
								    ->orderBy('track_name')
									->get();
		return response()->json(['status'=>'success','data'=>$demoTracks],200);
	}

	/*
	 *  The function is used to get intial data to be used at front side as shared for all pages
	 */
	public function getUserData(Request $request){

		$user = $this->user;
		$playlists = [];
		$favouritesIds = [];
		$notifications = [];
		$genres = \App\Genres::whereNull('parent_id')
							 ->select('name','id','slug')
							 ->with('subGenres')
							 ->orderBy('name')
							 ->get();
		$allGenres = \App\Genres::select('name','id','slug','parent_id')
							 ->orderBy('name')
							 ->get();
		$plans = \App\Plans::all();

		// $now = date('Y-m-d');
		$now = new \DateTime();

		$moods = \App\Mood::orderBy('name')
						  ->get();
		$followingTo =[];

		// default values
		$defaults = [
			'default_user_banners' =>	config('constants.default_user_banners'),
			'default_campaign_backgrounds' =>	config('constants.default_campaign_backgrounds'),
			'default_profile_image' => config('constants.default_profile_image'),
			'default_track_image' => config('constants.default_track_image'),
			'default_banner_image' => config('constants.default_banner_image'),
			'default_banner_images' => config('constants.default_banner_images'),
			'default_dashboard_images' => config('constants.default_dashboard_images')
		];

		$oauthConfig = [
				'spotify' => env('SPOTIFY_CLIENT_ID', ''),
				'soundcloud' => env('SOUNDCLOUD_KEY', ''),
				'facebook' => env('FACEBOOK_CLIENT_ID', ''),
				'instagram' => env('INSTAGRAM_KEY', ''),
				'youtube' => env('YOUTUBE_KEY', ''),
				'twitter' => env('TWITTER_KEY', '')
			];

		$banners = \App\libraries\GlobalHelper::getCoverBannerTemplates();

		if($user) {

			$user['nextPlan'] = \App\StripeActions::join('plans','stripe_actions.change_plan_to','=','plans.stripe_plan_key')
            								->where('user_id',$user->id)
            								->select('plans.name','plans.id as plan_id','plans.amount','stripe_actions.actionable_date','stripe_actions.change_plan_to','stripe_actions.stripe_customer_id','stripe_actions.subscription_id','stripe_actions.user_id')
            								->first();
			// $IsrejectedStatus = \App\TrackDemo::where('status','rejected')->where('isSignupTrack','signup')->where('user_id',$user->id)->orderBy('updated_at', 'DESC')->select('id','updated_at')->first();
			// $user['Isrejected'] = $IsrejectedStatus ? 1 : 0 ;
			// if($user['Isrejected'] == '1'){
			// 	$user['rejectedDate'] = date("Y/m/d H:i:s", strtotime($IsrejectedStatus->updated_at));
			// 	$user['expireDate'] = date('Y/m/d H:i:s', strtotime($user['rejectedDate'] . ' +1 day'));
			// 	$isExist = \App\TrackDemo::where('created_at' ,'>' ,$user['rejectedDate'])->where('status','pending')->where('user_id',$user->id)->select('id','updated_at')->first();
			// 	if($isExist){
			// 		$user['isExist'] = '1';
			// 	}
			// }

			$checkDemo = \App\TrackDemo::where('isSignupTrack','signup')->where('user_id',$user->id)->orderBy('updated_at', 'DESC')->select('id','updated_at','status')->first();

			if($checkDemo){
				if($checkDemo->status == 'rejected'){
					$user['Isrejected'] = 1;
					$user['rejectedDate'] = date("Y/m/d H:i:s", strtotime($checkDemo->updated_at));
					$user['expireDate'] = date('Y/m/d H:i:s', strtotime($user['rejectedDate'] . ' +60 day'));
				}
				if($checkDemo->status == 'pending'){
					// $isExist = \App\TrackDemo::where('created_at' ,'>' ,$user['rejectedDate'])->where('status','pending')->where('user_id',$user->id)->select('id','updated_at')->first();
					$user['isExist'] = '1';
				}
			}

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

			// $notifications_count = $userNotiIds->last_checked_date ? \App\Notification::where('created_at','>',$userNotiIds->last_checked_date)->count() : $notifications->count();
			$playlists = \App\Playlist::where('user_id', $user->id)->get();
			$favouriteTrackIds = \App\Favourite::where('user_id', $user->id)->where('type', 'track')->lists('track_id');
			$favouriteCampaignIds = \App\Favourite::where('user_id', $user->id)->where('type', 'campaign')->lists('campaign_id');
			$favouriteVideoIds = \App\FavouriteVideo::where('user_id', $user->id)->lists('video_id');
			$favouriteRemixIds = \App\Favourite::where('user_id', $user->id)->where('type', 'remix')->lists('track_id');
			$followingTo = \App\Follower::where('user_id',$user->id)->lists('follower_id');

			$oauthProviders = \App\OauthIdentity::where('user_id', $user->id)->lists('provider');
			$oauthProviderUsers = \App\OauthIdentity::where('user_id', $user->id)->lists('provider_user_id', 'provider');
			$oauthProviderTokens = \App\OauthIdentity::where('user_id', $user->id)->lists('access_token', 'provider');
            $oauthProviderTokens['expireLimitTime'] = 0;
            $refreshToken = \App\OauthIdentity::where('user_id', $user->id)->lists('token_secret', 'provider');


            if (isset($oauthProviderTokens['spotify'] )&& isset($user['id']) && isset($refreshToken['spotify']) &&  $refreshToken['spotify'] != null  ) {
                $refresh_token = $refreshToken['spotify'];
                $headers = array(
                    "Accept: */*",
                    "Content-Type: application/x-www-form-urlencoded",
                    "User-Agent: runscope/0.1",
                    "Authorization: Basic MWY0ZjZjMTI3MDlkNDBkYmI5YzNmYWQ4YWM1OWY4YTE6MmFiOWVlZWZiNTJmNGNjOTg0ZDIyMDBlODA1NTM2MDU="
                );

                $data = 'grant_type=refresh_token&refresh_token='.$refresh_token;

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://accounts.spotify.com/api/token");

                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = json_decode(curl_exec($ch), true);
                curl_close($ch);

                $spotifyToken = \App\OauthIdentity::where('user_id', $user->id)->where('provider', 'spotify')->select()->first();

                $expireLimitTime = date('Y-m-d H:i:s', strtotime('-1 hour'));
                $oauthProviderTokens['expireLimitTime'] = $expireLimitTime;

                if ($spotifyToken['updated_at'] < $expireLimitTime) {

                    $accessToken = $response['access_token'];

                    $spotifyToken->access_token = $accessToken;
                    $spotifyToken->save();
                    $oauthProviderTokens['spotify'] = $accessToken;
                }
            }

			$playlistTrackIds = \App\PlaylistTrack::join('playlists', 'playlist_tracks.playlist_id', '=', 'playlists.id')
													->where('playlists.user_id', $user->id)
													->where('playlist_tracks.type', 'track')
													->lists('playlist_tracks.track_id');

			$playlistCampaignIds = \App\PlaylistTrack::join('playlists', 'playlist_tracks.playlist_id', '=', 'playlists.id')
													->where('playlists.user_id', $user->id)
													->where('playlist_tracks.type', 'campaign')
													->lists('playlist_tracks.campaign_id');

			$playlistVideoIds = \App\PlaylistTrack::join('playlists', 'playlist_tracks.playlist_id', '=', 'playlists.id')
													->where('playlists.user_id', $user->id)
													->where('playlist_tracks.type', 'video')
													->lists('playlist_tracks.track_id');

			$playlistRemixIds = \App\PlaylistTrack::join('playlists', 'playlist_tracks.playlist_id', '=', 'playlists.id')
													->where('playlists.user_id', $user->id)
													->where('playlist_tracks.type', 'remix')
													->lists('playlist_tracks.track_id');

			// $demoTrackIds = \App\TrackDemo::where('user_id',$user->id)
			// 								->where('status', 'approved')
			// 								->lists('id');

			$activePlans = \App\PlanBilling::join('plans', 'plans_billing.plan_id', '=', 'plans.id')
                                ->where('user_id',$user->id)
                                // ->whereRaw('DATE_FORMAT(start_date, "%Y-%m-%d") <= NOW()')
                                // ->whereRaw('DATE_FORMAT(expire_date, "%Y-%m-%d") >= NOW()')
                                // ->whereRaw("DATE_FORMAT(start_date, '%Y-%m-%d') <= '".date('Y-m-d')."'")
                                // ->whereRaw("DATE_FORMAT(expire_date, '%Y-%m-%d') <= '".date('Y-m-d')."'")
                                ->where('start_date', '<=' , $now)
                                ->where('expire_date', '>=', $now)
                                // ->where('plan_id' , '!=',1)
                                ->orderBy('plans_billing.id','DESC')
                                ->select('plans_billing.plan_id', 'plans_billing.expire_date','plans_billing.start_date','plans_billing.discover_demo_limit','plans_billing.remix_demo_limit','plans_billing.video_demo_limit','plans_billing.price')
                                ->first();
                                // ->toArray();
 			// print_r($activePlans);
			$subscriptions = $user->subscriptions->first();
			if($activePlans && $subscriptions){
				$subscriptions = array_merge($subscriptions->toArray(),$activePlans->toArray());
			}



			//$currentPlan['all_plans'] = $activePlans;
			$currentPlan['plan'] = isset($subscriptions) ? $subscriptions : null; //(isset($activePlans[1]) ? $activePlans[1] : null);
            $user['subscriptionFeatures'] = \App\PlanFeature::join('plans','plan_features.plan_id','=','plans.id')
                                                        ->join('subscription_features','plan_features.feature_id','=','subscription_features.id')
                                                        ->where('plans.id',$currentPlan['plan']['plan_id'])
                                                        ->lists('subscription_features.slug');


			$scfollowings = \App\TrackShares::join('oauth_identities', 'oauth_identities.user_id' , '=', 'track_shares.artist_id')
											->where('track_shares.user_id', $user->id)
											->where('share_action', 'follow')
											->where('share_type', 'soundcloud')
											->distinct('oauth_identities.provider_user_id')
											->lists('oauth_identities.provider_user_id');



			if(!empty($currentPlan['plan'])){

				$start_date = $activePlans['start_date'];
				$end_date = $activePlans['expire_date'];

				if ($start_date) {
					$currentPlan['demoCount'] = \App\TrackDemo::where('user_id',$user->id)
														  ->where('type', 'discover')
														  ->where('track_demos.created_at','>=',$start_date)
										   	   			  ->where('track_demos.created_at','<=',$end_date)
										   	   			  ->count();

					$currentPlan['remixCount'] = \App\TrackDemo::where('user_id',$user->id)
															  ->where('type', 'remix')
															  ->where('track_demos.created_at','>=',$start_date)
											   	   			  ->where('track_demos.created_at','<=',$end_date)
											   	   			  ->count();

					$currentPlan['videoCount'] = \App\TrackDemo::where('user_id',$user->id)
															  ->where('type', 'music_video')
															  ->where('track_demos.created_at','>=',$start_date)
											   	   			  ->where('track_demos.created_at','<=',$end_date)
											   	   			  ->count();

					$currentPlan['streamlineCount'] = \App\Streamline::where('user_id', $user->id)
																->where('streamlines.created_at','>=',$start_date)
																->where('streamlines.created_at','<=',$end_date)
																->count();
				}
			}
		}
		$stripeKey = env('STRIPE_KEY');
		return response()->json(compact('user', 'playlists', 'currentPlan', 'favouriteTrackIds', 'favouriteRemixIds', 'favouriteVideoIds','favouriteCampaignIds', 'genres','allGenres','moods','followingTo','plans','stripeKey', 'defaults','oauthProviders','oauthConfig', 'demoTrackIds','oauthProviderUsers','oauthProviderTokens','banners','followings','scfollowings', 'playlistTrackIds', 'playlistCampaignIds', 'playlistVideoIds', 'playlistRemixIds','notifications','notifications_count','subscriptions'));
	}

	public function submitTrackDemos(Request $request){
			$soundcloudTrack = $request->get('soundcloud_track');
			$user = $this->user;

			// If submitted by normal user
			if($user->user_type == 'stm_user' && $user->isArtist == '0'){

				$artist_profile = \App\ArtistsProfile::firstOrCreate(['user_id' => $user->id]);
                $artist_profile->user_id = $user->id;
                $artist_profile->save();
                $user->isUser = 1;
                $user->isArtist = 1;
                $user->save();

				$this->sendEmailNotification();

                $data = array(
                        'user'              =>  $user->name ? $user->name : $user->first_name,
                        'email'             =>  $user->email,
                         'subject'          => 'Sore Thumb Media quality control'
                    );
                if($user->email_notification ==1){
            		$response = \App\libraries\MailHelper::sendEmail('emails.templates.user-submit-demo', $data);
            	}

            	$notificationData = [
                                'user_id' => $user->id,
                                'comments'=> 'Thank you for your submission! We will listen to it as soon as possible and be in touch.',
                                'type' => 'user_demo_submition'
                            ];
               if($user->web_notification ==1){
               		$notification = \App\libraries\GlobalHelper::addNotification($notificationData);
               }
			}

			$trackDemo = new \App\TrackDemo;
            $trackDemo->user_id = $user->id;
            $trackDemo->track_name = $soundcloudTrack['title'];
            $trackDemo->type = 'remix';
            $trackDemo->track_name = $soundcloudTrack['title'];
            $trackDemo->status = 'pending';

            // Generate unique slug
            $trackDemo->slug = str_slug($trackDemo->track_name);
            $count = \App\TrackDemo::whereRaw("slug RLIKE '^{$trackDemo->slug}(-[0-9]+)?$'")->count();
            $trackDemo->slug = $count ? "{$trackDemo->slug}-{$count}" : $trackDemo->slug;
            // $trackDemo->slug = $trackDemo->slug;

            $trackDemo->mp3_file = $soundcloudTrack['stream_url'];
            $trackDemo->cover_image = $soundcloudTrack['artwork_url'] ? $soundcloudTrack['artwork_url'] : '';
            $trackDemo->download_url = $soundcloudTrack['download_url'];
            $trackDemo->sc_id = $soundcloudTrack['id'];
            $trackDemo->isSignupTrack = 'signup';
            $trackDemo->save();

            $soundcloudArtist = \App\SoundcloudArtist::firstOrCreate(['sc_id' => $soundcloudTrack['id'], 'artist_id' => $user->id]);
            $soundcloudArtist->artist_id = $user->id;
            $soundcloudArtist->sc_id = $soundcloudTrack['id'];
            $soundcloudArtist->track_name = $soundcloudTrack['title'];
            $soundcloudArtist->description = $soundcloudTrack['description'];
            $soundcloudArtist->url = $soundcloudTrack['stream_url'];
            $soundcloudArtist->cover_image = $soundcloudTrack['artwork_url'];
            $soundcloudArtist->download_url = $soundcloudTrack['download_url'];
            $soundcloudArtist->sharing = $soundcloudTrack['sharing'];
            $soundcloudArtist->track_type = $soundcloudTrack['track_type'];
            $soundcloudArtist->streamable = $soundcloudTrack['streamable'] ? $soundcloudTrack['streamable'] : '0' ;
            $soundcloudArtist->downloadable = $soundcloudTrack['downloadable'] ? $soundcloudTrack['downloadable'] : '0';
            $soundcloudArtist->save();


		return response()->json(['status'=>'success','data'=>$trackDemo],200);
	}

}