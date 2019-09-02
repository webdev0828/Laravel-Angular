<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Track extends Model
{
    protected $table = 'tracks';
    protected $fillable = ['track_name', 'slug', 'tags','file', 'user_id'];
    protected $guarded = [];
    protected $appends = ['artist_name', 'favourite_count','track_genres'];

	// use SoftDeletes;

    public function getArtistNameAttribute(){
        $artist = $this->artist()->first(); 
        return $artist->name;
    }

    public function getTrackGenresAttribute(){
        $ids = $this->trackGenres()->lists('genre_id'); 
        $genre_names = $this->trackGenresNames($ids)->get();
        return $genre_names;
    }

     public function trackGenres(){
        return $this->hasMany('App\TrackGenres', 'track_id', 'id');
    }

    public function trackGenresNames($genres){
       $result = \DB::table('genres')
					->whereIn('genres.id',$genres)
					->select('genres.*');
		return $result;
    }

	public function getFavouriteCountAttribute(){
        $favouriteCount = $this->favourites()->count();
        return $favouriteCount;
    }

    // public function getFavouriteTrackAttribute(){
    //     $artist = $this->favouriteTrack()->first(); 
    //     return $artist->name;
    // }
    

    public function artist(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function favourites(){
        return $this->hasMany('App\Favourite', 'track_id', 'id');
    }

    // public function favouriteTrack() {
    //     return $this->hasMany('App\Favourite', 'track_id', 'id')
    //                 ->join('users', 'users.id', '=', 'favourites.user_id');
    // }

    // public function getPlaylistNameAttribute(){
    //     $playlist = $this->playlist()->select('name')->get(); 
    //     return $playlist;
    // }

    // public function playlist(){
    // 	return $this->belongsToMany('\App\Playlist', 'playlist_tracks', 'playlist_id', 'id');
    // }

    public function playlistTracks()
    {
        return $this->belongsToMany('App\Playlist', 'playlist_tracks', 'track_id', 'playlist_id');
    }

	static function getUnregisteredUsersTracks()
	{
		return self::where('tracks.user_id', '=', 0)
				->select('id','title','soundcloud_url','avatar_url','username','status','favourite');
	}

	static function getMembersTracks()
	{
		return self::where('tracks.user_id', '!=', 0)
				->select('id','title','soundcloud_url','avatar_url','username','status','favourite');
	}

	static function getProUsersTracks()
	{
		$result = \DB::table('tracks')->join('users', 'tracks.user_id', '=', 'users.id')
					->where('users.subscription','=','PROPREMIUM')
					->orWhere('users.subscription','=','PROUNLIMITED')
					->select('tracks.id','tracks.title', 'tracks.soundcloud_url','tracks.avatar_url','tracks.username','tracks.status','tracks.favourite');
		return $result;
	}

	static function getFavouritesTracks()
	{
		// $result = \DB::table('tracks')->join('favourites', 'tracks.id', '=', 'favourites.track_id')
		// 			->join('users', 'favourites.user_id', '=', 'users.id')
		// 			->orWhere('users.user_type','=','admin')
		// 			->select('tracks.id','tracks.title', 'tracks.soundcloud_url','tracks.avatar_url','tracks.username','tracks.status');
		// return $result;
		return self::where('tracks.favourite', '=', 1)
				->select('id','title','soundcloud_url','avatar_url','username','status');
	}

	static function getDeletedTracks()
	{
		return Track::onlyTrashed()->select('id','title','soundcloud_url','avatar_url','username');
	}

	public function users()
	{
		return $this->belongsToMany('\App\User', 'favourites', 'track_id', 'user_id');
	}	
	static function getArtistTracks($id)
	{
		return self::where('tracks.user_id', '=', $id)
				->select('id','title','soundcloud_url','avatar_url','username','status','favourite')->get();
	}
}
