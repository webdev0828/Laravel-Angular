<?php

/*
|--------------------------------------------------------------------------
| Application Events
|--------------------------------------------------------------------------
|
|
*/


Event::listen('auth.login', function($user) {
   	$user->last_login = new DateTime;
    $user->save();
});


###########################################
### User Created
###########################################
\App\User::created(function($user) 
{

	// $now = new \DateTime();
	// if($user->user_type == 'artist' || $user->isArtist == 1)
	// {
		
	// 	$plan = \App\Plans::where('id',1)->first();

	// 	$billing = new \App\PlanBilling;
	// 	$plans_billing[] = [
	// 		'user_id' => $user->id,
	// 		'plan_id' => $plan->id,
	// 		'plan_name' => $plan->name,
	// 		'price' => $plan->amount,
	// 		'discover_demo_limit' => $plan->discover_demo_limit,
	// 		'remix_demo_limit' => $plan->remix_demo_limit,
	// 		'video_demo_limit' => $plan->video_demo_limit,
	// 		'start_date' => $now,
	// 		'expire_Date'	=> date('Y-m-d H:i:s', strtotime("+30 days")),
	// 		'created_at' => $now,
	// 		'updated_at' => $now,
	// 				];
	// 		DB::table('plans_billing')->insert($plans_billing);
	// }
});

\App\DownloadHistory::created(function($download) 
{

	$notifications = [];
	if($download->type == 'track')
		$object_id = $download->track_id;
	if($download->type == 'campaign')
		$object_id = $download->campaign_id;
	if($download->type == 'remix')
		$object_id = $download->track_id;


	$notifications[] = [
			'user_id' => $download->user_id,
			'sender_id' => $download->user_id,
			'object' => 'download',
			'object_type' => $download->type,
			'object_id' => $object_id,
			'created_at' => new DateTime,
			'updated_at' => new DateTime,
			'message' => 'Downloaded ',
		];
	if(!empty($download->user_id)){
		return DB::table('activities')->insert($notifications);
	}
});

\App\Follower::created(function($follower) 
{
	
	$notifications = [];
	$notifications[] = [
			'user_id' => $follower->user_id,
			'sender_id' => $follower->follower_id,
			'object' => 'follow',
			'object_type' => 'following',
			'object_id' => $follower->follower_id,
			'created_at' => new DateTime,
			'updated_at' => new DateTime,
			'message' => 'Followed ',
		];
		
		DB::table('activities')->insert($notifications);
	
	$notifications = [];

	// $notifications[] = [
	// 		'user_id' => $follower->follower_id,
	// 		'sender_id' => $follower->user_id,
	// 		'object' => 'follow',
	// 		'object_type' => 'follower',
	// 		'object_id' => $follower->user_id,
	// 		'created_at' => new DateTime,
	// 		'updated_at' => new DateTime,
	// 		'message' => 'started following ',
	// 	];
	// 	DB::table('activities')->insert($notifications);
});

\App\Favourite::created(function($favourite) 
{

	$notifications = [];
	// $campaign = [];
	// if($favourite->type == 'track'){
	// 	$campaign = \App\TrackDemo::find($favourite->track_id);
	// }

	if($favourite->type == 'track')
		$object_id = $favourite->track_id;
	if($favourite->type == 'campaign')
		$object_id = $favourite->campaign_id;
	if($favourite->type == 'remix')
		$object_id = $favourite->track_id;

	// if($favourite->type == 'remix'){
	// 	return false;
	// 	// $campaign['campaign_id'] = $favourite->track_id;
	// }
	
	$notifications[] = [
			'user_id' => $favourite->user_id,
			'sender_id' => $favourite->user_id,
			'object' => 'favourite',
			'object_type' => $favourite->type,
			// 'object_id' => $favourite->campaign_id ? $favourite->campaign_id : $favourite->track_id,
			'object_id' => $object_id,
			'created_at' => new DateTime,
			'updated_at' => new DateTime,
			'message' => 'Favourited ',
		];
		
		DB::table('activities')->insert($notifications);
});

\App\FavouriteVideo::created(function($favourite) 
{
	
	$notifications = [];
	$notifications[] = [
			'user_id' => $favourite->user_id,
			'sender_id' => $favourite->user_id,
			'object' => 'favourite',
			'object_type' => 'music_video',
			// 'object_id' => $favourite->campaign_id ? $favourite->campaign_id : $favourite->track_id,
			'object_id' => $favourite->video_id,
			'created_at' => new DateTime,
			'updated_at' => new DateTime,
			'message' => 'Favourited ',
		];
		
		DB::table('activities')->insert($notifications);
});


