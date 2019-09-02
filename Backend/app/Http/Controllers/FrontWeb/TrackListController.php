<?php namespace App\Http\Controllers\FrontWeb;

use App\Http\Requests\Playlist\PlaylistRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TrackListController extends BaseController {

	public function __construct()
    {
    	parent::__construct();
    }

	public function index(){
		$userId = $this->user->id;
		$allPlaylist = \App\Playlist::where('user_id', $userId)->get();
		$favouritesTrack = \App\Favourite::where('user_id', $userId)->lists('track_id');
		return response()->json(['allPlaylist' => $allPlaylist, 'favouritesTrack' => $favouritesTrack]);
	}

	public function store(PlaylistRequest $request){

		$trackId = $request->get('trackId');
		$type = $request->get('type');
				
		if($request->get('playListId')){
			$playlistId = $request->get('playListId');
			
			if($type == 'campaign'){
				$test = \App\PlaylistTrack::where('playlist_id', $playlistId)
									  ->where('campaign_id', $trackId)
									  ->where('type', $type)
									  ->first();	
			}
			else{
				$test = \App\PlaylistTrack::where('playlist_id', $playlistId)
									  ->where('track_id', $trackId)
									  ->where('type', $type)
									  ->first();
			}
			if(!$test){
				$playlistTrack = new \App\PlaylistTrack;
				$playlistTrack->playlist_id = $playlistId;
				if($type == 'campaign')
					$playlistTrack->campaign_id = $trackId;
				else
					$playlistTrack->track_id = $trackId;
				$playlistTrack->type = $type;
				$playlistTrack->save();
				if($type == "video"){
					return response()->json(['success'=>'Video added to playlist.']);
				}else if($type == "campaign"){
					return response()->json(['success'=>'Track added to playlist.']);
				}else{
					return response()->json(['success'=>'Track added to playlist.']);
				}

			}else{
				if($type == "track" || $type == "remix"){
					return response()->json(['status'=>'error', 'message'=>'Track already added in this playlist.'], 422);
				}else if($type == "campaign"){
					return response()->json(['status'=>'error', 'message'=>'Track already added in this playlist.'], 422);
				}else{
					return response()->json(['status'=>'error', 'message'=>'Video already added in this playlist.'], 422);
				}
			
			}
			
		}
		else{
			$newplaylist = new \App\Playlist;
			$newplaylist->name = $request->get('playlist_name');
			$newplaylist->user_id = $this->user->id; //give static user id need to change it.......
			$newplaylist->save();

			$playlistTrack = new \App\PlaylistTrack;
			$playlistTrack->playlist_id = $newplaylist->id;
			if($type == 'campaign')
				$playlistTrack->campaign_id = $trackId;
			else
				$playlistTrack->track_id = $trackId;
			$playlistTrack->type = $type;
			$playlistTrack->save();
			if($type == "track"){
				return response()->json(['success'=>'Track added to playlist.', 'newplaylist'=> $newplaylist]);
			}else if($type == "campaign"){
				return response()->json(['success'=>'Track added to playlist.', 'newplaylist'=> $newplaylist]);
			}else{
				return response()->json(['success'=>'Video added to playlist.', 'newplaylist'=> $newplaylist]);
			}
			
		}
	}

	public function removePlaylistTrack(Request $request){
		$userId = $this->user->id;
		$trackId = $request->get('id');
		$type = $request->get('type');

		$playlistIds = \App\Playlist::where('user_id', $userId)->lists('id');
		if($type == "campaign"){
			$playlistTrack = \App\PlaylistTrack::where('campaign_id', $trackId)
												->where('type', $type)
												->whereIn('playlist_id', $playlistIds)
												->delete();
		}else{
			$playlistTrack = \App\PlaylistTrack::where('track_id', $trackId)
												->where('type', $type)
												->whereIn('playlist_id', $playlistIds)
												->delete();
		}
		return response()->json(['success'=>'Track successfully remove from playlist']);
	}


}