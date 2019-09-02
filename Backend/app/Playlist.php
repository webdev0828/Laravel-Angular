<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'playlists';
	protected $guarded = [];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'user_id'];

	public static function boot(){
        parent::boot();

        Playlist::deleting(function($userPlaylist){

            $userPlaylistTracks = PlaylistTrack::where('playlist_id', $userPlaylist['id'])->get();
            foreach ($userPlaylistTracks as $userPlaylistTrack) {
                $userPlaylistTrack->delete();
            }

        });
    }
}
