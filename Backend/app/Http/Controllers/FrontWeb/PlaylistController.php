<?php namespace App\Http\Controllers\FrontWeb;

use App\Http\Requests\Playlist\PlaylistRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PlaylistController extends BaseController {

	function __construct()
    {
        // construct
        parent::__construct();
    }

	public function index(Request $request){
		$limit = $request->get('limit',9);

		$listIds = \App\Playlist::where('user_id', $this->user->id)->lists('id');

		$playlistTracks = \App\PlaylistTrack::leftJoin('campaigns', 'playlist_tracks.campaign_id', '=', 'campaigns.id')
											->whereIn('playlist_tracks.playlist_id', $listIds)
											->where('playlist_tracks.type', 'campaign')
											->select('playlist_tracks.playlist_id as playlist_id', 'playlist_tracks.id as playlist_track_id','playlist_tracks.track_id as track_id', 'playlist_tracks.campaign_id as campaign_id', 'playlist_tracks.type as track_type', 'campaigns.id', 'campaigns.cover_image', 'campaigns.track_name', 'campaigns.mp3_file', 'campaigns.type', 'campaigns.user_id', 'campaigns.slug')
											->orderBy('playlist_tracks.created_at', 'desc')
											->paginate($limit);
											
											

		// $playlistCampaigns = \App\Campaign::join('playlist_tracks', 'campaigns.id', '=', 'playlist_tracks.campaign_id')
		// 								->whereIn('playlist_tracks.playlist_id', $listIds)
		// 								->where('playlist_tracks.type', 'campaign')
		// 								->select('campaigns.*', 'playlist_tracks.playlist_id as playlist_id', 'playlist_tracks.id as playlist_track_id')
		// 								//->groupBy('tracks.id')
		// 								->paginate($limit);										

		// $playlistRemixTrack = \App\CompetitionArtist::join('playlist_tracks', 'competition_artists.id', '=', 'playlist_tracks.track_id')
		// 											->whereIn('playlist_tracks.playlist_id', $listIds)
		// 											->where('playlist_tracks.type', 'remix')
		// 											->select('competition_artists.*', 'playlist_tracks.playlist_id as playlist_id', 'playlist_tracks.id as playlist_track_id')
		// 											//->groupBy('tracks.id')
		// 											->paginate(6);

		// // echo '<pre>'; print_r($playlistRemixTrack->toArray()); die; 

		// $playlistVideos = \App\StmVideoRelease::join('playlist_tracks', 'stm_video_releases.id', '=', 'playlist_tracks.track_id')
		// 										->whereIn('playlist_tracks.playlist_id', $listIds)
		// 										->where('playlist_tracks.type', 'video')
		// 										->select('stm_video_releases.*', 'playlist_tracks.playlist_id as playlist_id', 'playlist_tracks.id as playlist_track_id')
		// 										->paginate($limit);
		// $playlistTracks = array(
  //           'data' => $playlistTracks->items(), 
  //           'current_page' =>  $playlistTracks->currentPage(),
  //           'last_page' =>  $playlistTracks->lastPage()
  //       );

        
												
		return response()->json(['playlistTracks' => $playlistTracks]);
	}

	public function getMorePlaylistsTracks(Request $request){

		$limit = $request->get('limit',9);

		$listIds = \App\Playlist::where('user_id', $this->user->id)->lists('id');

		$playlistTracks = \App\PlaylistTrack::leftJoin('campaigns', 'playlist_tracks.campaign_id', '=', 'campaigns.id')
											->whereIn('playlist_tracks.playlist_id', $listIds)
											->where('playlist_tracks.type', 'campaign')
											->select('playlist_tracks.playlist_id as playlist_id', 'playlist_tracks.id as playlist_track_id','playlist_tracks.track_id as track_id', 'playlist_tracks.campaign_id as campaign_id', 'playlist_tracks.type as track_type', 'campaigns.id', 'campaigns.cover_image', 'campaigns.track_name', 'campaigns.mp3_file', 'campaigns.type', 'campaigns.user_id', 'campaigns.slug')
											->orderBy('playlist_tracks.created_at', 'desc')
											->paginate($limit);
											
		return response()->json(['playlistTracks' => $playlistTracks]);
		// $userId = $this->user->id;
		// $limit = 6;
		// $listIds = \App\Playlist::where('user_id', $this->user->id)->lists('id');
		// $playlistTracks = \App\TrackDemo::join('playlist_tracks', 'track_demos.id', '=', 'playlist_tracks.track_id')
		// 								->whereIn('playlist_tracks.playlist_id', $listIds)
		// 								->where('playlist_tracks.type', 'track')
		// 								->select('track_demos.*', 'playlist_tracks.playlist_id as playlist_id', 'playlist_tracks.id as playlist_track_id')
		// 								//->groupBy('tracks.id')
		// 								->paginate($limit);
		// $playlistTracks = array(
  //           'data' => $playlistTracks->items(), 
  //           'current_page' =>  $playlistTracks->currentPage(),
  //           'last_page' =>  $playlistTracks->lastPage()
  //       );

		// $playlistRemixTrack = \App\CompetitionArtist::join('playlist_tracks', 'competition_artists.id', '=', 'playlist_tracks.track_id')
		// 											->whereIn('playlist_tracks.playlist_id', $listIds)
		// 											->where('playlist_tracks.type', 'remix')
		// 											->select('competition_artists.*', 'playlist_tracks.playlist_id as playlist_id', 'playlist_tracks.id as playlist_track_id')
		// 											//->groupBy('tracks.id')
		// 											->paginate($limit);
		// $playlistRemixTrack = array(
  //           'data' => $playlistRemixTrack->items(), 
  //           'current_page' =>  $playlistRemixTrack->currentPage(),
  //           'last_page' =>  $playlistRemixTrack->lastPage()
  //       );

		// return response()->json(['playlistTracks' => $playlistTracks, 'playlistRemixTrack' => $playlistRemixTrack]);
	}

	public function getMorePlaylistsCampaigns(Request $request){
		$userId = $this->user->id;
		$limit = $request->get('limit',9) ;
		$listIds = \App\Playlist::where('user_id', $this->user->id)->lists('id');
		$playlistCampaigns = \App\Campaign::join('playlist_tracks', 'campaigns.id', '=', 'playlist_tracks.campaign_id')
										->whereIn('playlist_tracks.playlist_id', $listIds)
										->where('playlist_tracks.type', 'campaign')
										->select('campaigns.*', 'playlist_tracks.playlist_id as playlist_id', 'playlist_tracks.id as playlist_track_id')
										//->groupBy('tracks.id')
										->paginate($limit);	
		$playlistCampaigns = array(
	            'data' => $playlistCampaigns->items(), 
	            'current_page' =>  $playlistCampaigns->currentPage(),
	            'last_page' =>  $playlistCampaigns->lastPage()
	        );
		return response()->json(['playlistCampaigns' => $playlistCampaigns]);
	}

	// public function getMorePlaylistsRemix(Request $request){
	// 	$userId = $this->user->id;
	// 	$limit = $request->get('limit',9) ;
	// 	$listIds = \App\Playlist::where('user_id', $this->user->id)->lists('id');
	// 	$playlistRemixTrack = \App\CompetitionArtist::join('playlist_tracks', 'competition_artists.id', '=', 'playlist_tracks.track_id')
	// 												->whereIn('playlist_tracks.playlist_id', $listIds)
	// 												->where('playlist_tracks.type', 'remix')
	// 												->select('competition_artists.*', 'playlist_tracks.playlist_id as playlist_id', 'playlist_tracks.id as playlist_track_id')
	// 												//->groupBy('tracks.id')
	// 												->paginate($limit);
	// 	$playlistRemixTrack = array(
 //            'data' => $playlistRemixTrack->items(), 
 //            'current_page' =>  $playlistRemixTrack->currentPage(),
 //            'last_page' =>  $playlistRemixTrack->lastPage()
 //        );
	// 	return response()->json(['playlistRemixTrack' => $playlistRemixTrack]);
	// }

	public function getMorePlaylistsVideos(Request $request){
		$userId = $this->user->id;
		$limit = $request->get('limit',9) ;
		$listIds = \App\Playlist::where('user_id', $this->user->id)->lists('id');
		$playlistVideos = \App\StmVideoRelease::join('playlist_tracks', 'stm_video_releases.id', '=', 'playlist_tracks.track_id')
												->whereIn('playlist_tracks.playlist_id', $listIds)
												->where('playlist_tracks.type', 'video')
												->select('stm_video_releases.*', 'playlist_tracks.playlist_id as playlist_id', 'playlist_tracks.id as playlist_track_id')
												->paginate($limit);

        $playlistVideos = array(
            'data' => $playlistVideos->items(), 
            'current_page' =>  $playlistVideos->currentPage(),
            'last_page' =>  $playlistVideos->lastPage()
        );
		return response()->json(['playlistVideos' => $playlistVideos]);
	}

	public function store(PlaylistRequest $request){
		if($request->get('playlist_name')){
			$newplaylist =  \App\Playlist::findOrNew($request->input('id'));
			$newplaylist->name = $request->get('playlist_name');
			$newplaylist->user_id = $this->user->id;
			$newplaylist->save();
			return response()->json(['success'=>'Playlist created successfully.', 'newplaylist' => $newplaylist]);
		}
		else{
			return response()->json(['error'=>'error.']);
		}
	}

	public function destroy($id){
		if($id){
			$playlist = \App\Playlist::find($id);
			$playlistTrack = \App\PlaylistTrack::where('playlist_id', '=', $id)->delete();
			$playlist->delete();
			return response()->json(['success'=>'Playlist deleted successfully.']);
		}else{
			return response()->json(['error'=>'error.']);
		}
	}

	public function update(PlaylistRequest $request){ 
		if($request->get('id') && $request->get('name')){
			$changeName = \App\Playlist::find($request->get('id'))->update(['name' => $request->get('name')]);
		}
	}

	
	public function deleteTrack($id){
		if($id){
			$trackList = \App\PlaylistTrack::find($id)->delete();
			return response()->json(['success'=>'Track deleted successfully.']);
		}else{
			return response()->json(['error'=>'error.']);
		}
	}

	public function getPlayCount(Request $request){
		$id= $request->get('id');
		$type = $request->get('type');
		if($type == 'track'){
				$track = \App\TrackDemo::find($id);
			}
			elseif ($type == 'video') {
				$track = \App\StmVideoRelease::find($id);
			}
			elseif ($type == 'campaign') {
				$track = \App\Campaign::find($id);
			}

			else{
				$track = \App\CompetitionArtist::find($id);
			}

			return response()->json(['success'=>'Success','play_count'=>$track->play_count],200);


	}
	public function updatePlayHistory(Request $request ,$id,$slug,$type){
		if($id && $type){
			
			if($type == 'track'){
				$type = 'track';
				$track = \App\TrackDemo::find($id);
			}
			elseif ($type == 'video') {
				$track = \App\StmVideoRelease::find($id);
			}
			elseif ($type == 'campaign') {
				$type = 'campaign';
				$track = \App\Campaign::find($id);
			}

			else{
				$type = 'remix';
				$track = \App\CompetitionArtist::find($id);
			}

			$user = \App\User::where('slug',$slug)->first();
			
			// $playHistoryDetails = \App\PlayHistoryDetails::where('track_id',$id)
			// 											 ->where(['ip_address' => $request->ip()])
			// 											 ->where('type',$type)
			// 											 ->first();
			if($type == 'campaign'){
				$playHistoryDetailsCampaign = \App\PlayHistoryDetails::where('campaign_id',$id)
														 ->where(['ip_address' => $request->ip()])
														 ->where('type',$type)
														 ->first();
				if(!$playHistoryDetailsCampaign){
					$playHistoryDetails = new \App\PlayHistoryDetails;
					$playHistoryDetails->campaign_id = $id;
					$playHistoryDetails->type = $type;
					$playHistoryDetails->ip_address = $request->ip();
					$playHistoryDetails->save();
				}
					// $playHistory = \App\PlayHistory::firstOrNew(['campaign_id' => $id,'track_id' => null,'artist_id' => $user->id,'type'=>$type]);
					$playHistory = \App\PlayHistory::where('campaign_id',$id)->where('type','campaign')->where('artist_id',$user->id)->first();
					
					if($playHistory){
						$playHistory->count = $playHistory->count ? $playHistory->count + 1 : 1;	
						$playHistory->save();
					}
					else{
						$playHistory = new \App\PlayHistory;
						$playHistory->campaign_id = $id;
						$playHistory->artist_id = $user->id;
						$playHistory->type = $type;
						$playHistory->count = $playHistory->count ? $playHistory->count + 1 : 1;
						$playHistory->save();	
					}
					// $playHistory->campaign_id = $id;
					// $playHistory->artist_id = $user->id;
					// $playHistory->type = $type;
					// $playHistory->count = $playHistory->count ? $playHistory->count + 1 : 1;
					// $playHistory->save();

					return response()->json(['success'=>'Success','data'=>$track],200);
				// }										 
			}														 
			if($type == 'track' || $type == 'remix'){
				$playHistoryDetails = \App\PlayHistoryDetails::where('track_id',$id)
														 ->where(['ip_address' => $request->ip()])
														 ->where('type',$type)
														 ->first();
				if(!$playHistoryDetails){

					$playHistoryDetails = new \App\PlayHistoryDetails;
					$playHistoryDetails->track_id = $id;
					$playHistoryDetails->campaign_id = $track->campaign_id;
					$playHistoryDetails->type = $type;
					$playHistoryDetails->ip_address = $request->ip();
					$playHistoryDetails->save();
				}

					// $playHistory = \App\PlayHistory::firstOrNew(['track_id' => $id,'campaign_id' => null,'artist_id' => $user->id,'type'=>$type]);

					$playHistory = \App\PlayHistory::where('track_id',$id)->where('type',$type)->where('artist_id',$user->id)->first();
					
					if($playHistory){
						$playHistory->count = $playHistory->count ? $playHistory->count + 1 : 1;	
						$playHistory->save();
					}
					else{
						$playHistory = new \App\PlayHistory;
						$playHistory->track_id = $id;
						$playHistory->campaign_id = $track->campaign_id;
						$playHistory->artist_id = $user->id;
						$playHistory->type = $type;
						$playHistory->count = $playHistory->count ? $playHistory->count + 1 : 1;
						$playHistory->save();	
					}


					// $playHistory->track_id = $id;
					// $playHistory->artist_id = $user->id;
					// $playHistory->type = $type;
					// $playHistory->count = $playHistory->count ? $playHistory->count + 1 : 1;
					// $playHistory->save();

					return response()->json(['success'=>'Success','data'=>$track],200);
				// }														 
			}

			return response()->json(['success'=>'Success'],200);
		}
	}
}