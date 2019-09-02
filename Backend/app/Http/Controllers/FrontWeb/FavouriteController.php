<?php namespace App\Http\Controllers\FrontWeb;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FavouriteController extends BaseController {

	function __construct()
    {
        // construct
        parent::__construct();
    }


	public function index(Request $request){
		$slug = $request->get('slug');
		$user = \App\User::where('slug', $slug)->first();
		$userId = $user->id;

		$limit = $request->get('limit',9) ;


		$favouriteTracks = \App\Favourite::leftJoin('campaigns', 'favourites.campaign_id', '=', 'campaigns.id')
											->where('favourites.user_id', $userId)
											->where('favourites.type', 'campaign')
											/*->select('favourites.id as favourite_id', 'campaigns.id', 'campaigns.user_id', 'campaigns.cover_image', \DB::raw('CONCAT(campaigns.mp3_file,"?client_id='.env('SOUNDCLOUD_KEY').'") as mp3_file'), 'campaigns.external_download_link', 'campaigns.track_name', 'campaigns.slug', 'campaigns.type', \DB::raw('"campaign" as track_type'))*/
											->select('favourites.id as favourite_id', 'campaigns.id', 'campaigns.user_id', 'campaigns.cover_image', 'campaigns.mp3_file', 'campaigns.external_download_link', 'campaigns.track_name', 'campaigns.slug', 'campaigns.type', \DB::raw('"campaign" as track_type'))
											->orderBy('favourites.created_at', 'desc')
											->paginate($limit);

		foreach($favouriteTracks->items() as $item) {
			if ($item->type == 'original' && $item->external_download_link != null && $item->external_download_link != '') {
				$item->mp3_file = $item->external_download_link;
			} else if ($item->type == 'remix') {
				$item->mp3_file = $item->mp3_file . '?client_id=' .env('SOUNDCLOUD_KEY');
			}
		}


		$favouriteTracks = array(
            'data' => $favouriteTracks->items(),
            'current_page' =>  $favouriteTracks->currentPage(),
            'last_page' =>  $favouriteTracks->lastPage()
        );
		return response()->json(['favouriteTracks' => $favouriteTracks]);
	}

	public function store(Request $request){
		if($request->get('id')){
			$trackId = $request->get('id');
			$userId = $this->user->id;
			$type = $request->get('type');

			if($type == "campaign"){
				$favouriteTrack = \App\Favourite::where('campaign_id', $trackId)->where('user_id', $userId)->where('type',$type)->first();
			}else{
				$favouriteTrack = \App\Favourite::where('track_id', $trackId)->where('user_id', $userId)->where('type',$type)->first();
			}

			if(!empty($favouriteTrack)){
				$activity = \App\Activity::where('user_id',$userId)
                                         ->where('sender_id',$userId)
                                         ->where('object','favourite')
                                         ->where('object_type',$type)
                                         ->where('object_id',$trackId)
                                         ->delete();
				$favouriteTrack->delete();
				return response()->json(['success'=>'Track successfully removed from favourites', 'action'=> "remove"]);
				// return response()->json(['error'=>'Track is already added in favourite.'], 422);
			}else{
				$favourite = new \App\Favourite;
				$favourite->user_id = $userId;
				if($type == 'campaign'){
					$favourite->campaign_id = $trackId;
				}
				else{
					$favourite->track_id = $trackId;
				}
				$favourite->type = $type;
				$favourite->save();

				if($type == 'campaign'){
					$track = \App\Campaign::where('id', $favourite->campaign_id)->first();
				}
				else if($type == 'remix'){
					$track = \App\CompetitionArtist::where('id', $favourite->track_id)->first();
				}
				else{
					$track = \App\TrackDemo::where('id', $favourite->track_id)->first();
				}
				$artist_id = \App\User::where('id',$type == 'remix' ?  $track->artist_id : $track->user_id)->first();
	            if($artist_id && $artist_id->id != $userId){
	            	$favouriteLink = $this->user->user_type == 'artist' ? url('/'.$this->user->slug).'/tracks' : url('/user/'.$this->user->slug).'/favourites';
	            	$trackLink = $type == 'campaign' ? url('/campaign/'.$track->slug) : url('/track/'.$track->slug);
	                $notificationData = [
	                                'user_id' => $artist_id->id,
	                                'comments'=> $this->user->name ? '<a href="'.$favouriteLink.'">'.$this->user->name.' </a> favorited <a href="'.$trackLink.'">'.$track->track_name : '<a href="'.$favouriteLink.'">'.$this->user->first_name. '</a> favorited <a href="'.$trackLink.'">'.$track->track_name.' track',
	                                'type' => 'favorited'
	                            ];
	              	if($artist_id->web_notification ==1){
	              		\App\libraries\GlobalHelper::addNotification($notificationData);
	              	}//web_notification

	            }

				return response()->json(['success'=>'Track successfully added in favourites', 'action'=> 'add']);
			}
		}
	}


	public function addFavouriteVideo(Request $request){
		if($request->get('id')){
			$videoId = $request->get('id');
			$userId = $this->user->id;

			$favouriteVideo = \App\FavouriteVideo::where('video_id',$videoId)->where('user_id',$userId)->first();

			if(!empty($favouriteVideo)){
				$favouriteVideo->delete();
				return response()->json(['success'=>'Video successfully removed from favourites', 'action'=> 'remove']);
			}else{
				$favourite = new \App\FavouriteVideo;
				$favourite->video_id = $videoId;
				$favourite->user_id = $userId;
				$favourite->save();

				$track = \App\StmVideoRelease::where('id',$favourite->video_id)->first();
				$artist_id = \App\User::where('id',$track->artist_id)->first();
	            if($artist_id && $artist_id->id != $userId){
	                $data = array(
	                    'user'              =>  $this->user,
	                    'artist'            =>  $artist_id->name ? $artist_id->name : $artist_id->first_name,
	                    'email'             =>  $artist_id->email,
	                    'track'             =>  $track,
	                    'type'              =>  'video',
	                    'subject'           =>  $this->user->name ? $this->user->name.' favourites '.$track->track_name: $this->user->first_name.' favourites '.$track->track_name
	                );
	                if($this->user->email_notification ==1){
	                	$response = \App\libraries\MailHelper::sendEmail("emails.templates.track-favorited", $data);
	                }

	            }
				return response()->json(['success'=>'Video successfully added in favourites', 'action'=> 'add']);
			}
		}
	}


	public function deleteFavourite(Request $request){
		$type = $request->get('type');
		$id = $request->get('id');

		$userId = $this->user->id;
		$trackList = \App\Favourite::find($id);
		if($trackList->type == 'campaign'){
			$object_id = $trackList->campaign_id;
		}
		else{
			$object_id = $trackList->track_id;
		}
		$activity = \App\Activity::where('user_id',$userId)
                                         ->where('sender_id',$userId)
                                         ->where('object','favourite')
                                         ->where('object_type',$type)
                                         ->where('object_id',$object_id)
                                         ->delete();
		$trackList = \App\Favourite::find($id)->delete();
		return response()->json(['success'=>'Track deleted successfully']);
		// if($type == "track"){
		// 	$trackList = \App\Favourite::find($id)->delete();
		// 	return response()->json(['success'=>'Track deleted successfully.']);
		// }if($type == "campaign"){
		// 	$trackList = \App\Favourite::find($id)->delete();
		// 	return response()->json(['success'=>'Track deleted successfully.']);
		// }if($type == "remix"){
		// 	$trackList = \App\Favourite::find($id)->delete();
		// 	return response()->json(['success'=>'Competition track deleted successfully.']);
		// }else{
		// 	$trackList = \App\FavouriteVideo::find($id)->delete();
		// 	return response()->json(['success'=>'Video deleted successfully.']);
		// }
	}

	public function getMoreFavouritesTracks(Request $request){
		$slug = $request->get('slug');
		$user = \App\User::where('slug', $slug)->first();
		$userId = $user->id;
		$limit = $request->get('limit',9);

		$favouriteTracks = \App\Favourite::leftJoin('campaigns', 'favourites.campaign_id', '=', 'campaigns.id')
											->where('favourites.user_id', $userId)
											->where('favourites.type', 'campaign')
											->select('favourites.id as favourite_id', 'campaigns.id', 'campaigns.user_id', 'campaigns.cover_image', \DB::raw('CONCAT(campaigns.mp3_file,"?client_id='.env('SOUNDCLOUD_KEY').'") as mp3_file'), 'campaigns.track_name', 'campaigns.slug', 'campaigns.type', \DB::raw('"campaign" as track_type'))
											->orderBy('favourites.created_at', 'desc')
											->paginate($limit);

		foreach($favouriteTracks->items() as $item) {
			if ($item->type == 'original' && $item->external_download_link != null && $item->external_download_link != '') {
				$item->mp3_file = $item->external_download_link;
			} else if ($item->type == 'remix') {
				$item->mp3_file = $item->mp3_file . '?client_id=' .env('SOUNDCLOUD_KEY');
			}
		}
		
		$favouriteTracks = array(
            'data' => $favouriteTracks->items(),
            'current_page' =>  $favouriteTracks->currentPage(),
            'last_page' =>  $favouriteTracks->lastPage()
        );



		return response()->json(['favouriteTracks' => $favouriteTracks]);
	}

}