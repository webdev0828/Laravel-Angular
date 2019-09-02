<?php namespace App\libraries;

use Illuminate\Support\Str;
use File;

class GlobalHelper {
	
	public static function  getTweets(){
		$settings = array(
		   'oauth_access_token' => env('HOME_TWITTER_ACCESS_TOKEN'),
		    'oauth_access_token_secret' => env('HOME_TWITTER_ACCESS_TOKEN_SECRET'),
		    'consumer_key' => env('HOME_TWITTER_KEY'),
		    'consumer_secret' => env('HOME_TWITTER_SECRET')
		);


		$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
		$getfield = '?screen_name='.env('HOME_TWITTER_SCREENNAME').'&count=6&trim_user=true&exclude_replies=true&include_rts=false';
		$requestMethod = 'GET';

		try {
			$twitter = new \App\libraries\TwitterAPIExchange($settings);

			$response = $twitter->setGetfield($getfield)
			    				->buildOauth($url, $requestMethod)
			   					->performRequest();

			$response =  json_decode($response,true); 
			
			$tweetObject = [];
			if(!empty($response)){
				foreach ($response as $key => $value) {
					$tweetObject[] = [
						'created_at' => $value['created_at'],
						'text' => $value['text'],
					];
				}
			}
			return $tweetObject;
		} catch(Exception $e) {
			return null;
		}
	}

	public static function scandir_by_mtime($folder) {
	  $dircontent = scandir($folder);
	  $arr = array();
	  foreach($dircontent as $filename) {
	    if ($filename != '.' && $filename != '..') {
	      if (filectime($folder.$filename) === false) return false;
	      $dat = date("YmdHis", filectime($folder.$filename));
	      $arr[$dat] = $filename;
	    }
	  }
	  if (!ksort($arr)) return false;
	  return $arr;
	}


	public static function getCoverBannerTemplates(){
		$directory = 'banners';
		# Show only these file types from the image folder
		// $imageTypes = '{*.jpg,*.JPG,*.jpeg,*.JPEG,*.png,*.PNG,*.gif,*.GIF}';
		// $images = glob($imageFolder . '/'.$imageTypes);
 		$folder= $directory;
 
		$images = array();
		foreach (scandir($folder) as $key => $node) {
		    $nodePath = $folder . DIRECTORY_SEPARATOR . $node;
		    if (is_dir($nodePath)) continue;
		    $images[filemtime($nodePath).$key] = $nodePath;
		}
		krsort($images);
	  	array_reverse($images,true);

		// $files = $images;//File::allFiles($directory);
		$files = $images;
		$arr = [];
		foreach ($files as $key => $file)
		{
		    $arr[] =str_replace('\\', '/', (string)$file);;
		}

		$firstSecArr = array_slice($arr, 0, 8);
		$lastSecArr = array_slice($arr, 8);
		$arr = array_merge($lastSecArr, $firstSecArr);
	
		return $arr;

	}


	public static function trackVisits($ip, $campaignId, $trackId = null){
		$campaignVisit = new \App\CampaignVisit;
        $campaignVisit->ip_address = $ip;
        $campaignVisit->campaign_id = $campaignId;
        $campaignVisit->track_id = $trackId;
        $campaignVisit->save();
	}

	public static function streamlineVisits($ip, $streamlineId) {
		$streamlineVisit = new \App\StreamlineVisit;
		$streamlineVisit->ip_address = $ip;
		$streamlineVisit->streamline_id = $streamlineId;

		$streamlineVisit->save();
	}

	
	public static function uploadFile($file, $destinationPath, $trackName, $type){
		if(!empty($file))
		{
			$extension = $file->getClientOriginalExtension();
		   	$fileName = strtolower(date("dmYhis").'.'.$extension); 

		   	$destinationPath = str_replace('\\', '/', $destinationPath);
		   	$file->move($destinationPath, $fileName);
		    return $fileName;
		}
	}


	public static function uploadCompetitionFile($file, $destinationPath){
		if(!empty($file))
		{
			$extension = $file->getClientOriginalExtension();
		   	$fileName = strtolower(rand(11111,99999).'.'.$extension);
		   	$file->move($destinationPath, $fileName);
		    return $fileName;
		}
	}
	public static function deleteFile($path){
		if(File::exists($path)) {
			File::delete($path);
			return true;
		}
		return false;
	}
	public static function uploadImage($file,$destinationPath){
		if(!empty($file))
		{
			$extension = $file->getClientOriginalExtension();
		   	$fileName = strtolower(rand(11111,99999).'.'.$extension);
		   	$file->move($destinationPath, $fileName);
		    return $fileName;
		}
	}

	public static function getSoundCloudTrack(){
		$tracks = [	['trackName'=> 'Scott Isbell - Tonight Feat Adessi','fileLink' => 'test'],
					['trackName'=> 'Beautiful Day - Burberry Perry(Ft. Lil Yachty)','fileLink' => 'test1'],
					['trackName'=> 'Donald Trump - Trumpified - @RealDonaldTrump','fileLink' => 'test2'],
					['trackName'=> 'K.L. - Petal to the Maxx','fileLink' => 'test3']
				];
		return $tracks;
	}

	public static function getEloquentUniqueSlug($modelClass, $columnName, $value)
	{
	    $slugVal = Str::slug($value);
	    if($value == "user"){
	    	$i= 1;
	    }
	    else{
	    	$i = 0;
	    }
	    
	    $slug = $slugVal;
	    do {
	    	if($i){
	    		$slug = $slugVal.'-'.$i;
	    	}
	    	$modelObj = $modelClass::whereRaw("$columnName = '".$slug."'")->first();
	    	if(!$modelObj) {
	    		break;
	    	}
	    	$i++;
	    } while(1);

	    return $slug;
	}

	public static function addNotification($data){
		$notification = new \App\Notification;
        $notification->user_id = $data['user_id'];
        $notification->comments = $data['comments'];
        $notification->type = $data['type'];
        $notification->isGlobal = isset($data['isGlobal']) ? 1 : 0;
        $notification->ref_id = isset($data['ref_id']) ? $data['ref_id'] : null;
        $notification->save();
        return $notification;
	}

}
