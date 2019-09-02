<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompetitionArtist extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'competition_artists';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['competition_id', 'artist_id', 'mp3_file', 'gendescriptionres', 'check'];

	protected $appends = ['artist_name','artist_avatar','artist_slug', 'track_genres', 'favourite_count','type','play_count', 'track_type'];


    public function getMp3FileAttribute(){
        return $this->attributes['mp3_file'];//.'?client_id='.env('SOUNDCLOUD_KEY');
    }

    public function getPlayCountAttribute(){
        $playHistory = $this->playHistory()->first();
        return $playHistory ? $playHistory->count : 0 ;
    }

    public function playHistory(){
        return $this->hasOne('App\PlayHistory', 'track_id', 'id')->where('type','remix');
    }


    public function getArtistNameAttribute(){
        $artist = $this->artist()->first();
        return $artist->name;
    }

    public function getArtistSlugAttribute(){
        $artist = $this->artist()->first(); 
        return $artist->slug;
    }

    public function getArtistAvatarAttribute(){
        $artist = $this->artist()->first(); 
        return $artist ? $artist->avatar : '';
    }

    public function getTypeAttribute(){
        return $type = 'competition_remix';
    }

    public function getTrackTypeAttribute(){
        return $type = 'remix';
    }

    public function artist(){
        return $this->belongsTo('App\User', 'artist_id', 'id');
    }



    public function getFavouriteCountAttribute(){
        $favouriteCount = $this->favourites()->count();
        return $favouriteCount;
    }

    public function favourites(){
        return $this->hasMany('App\Favourite', 'track_id', 'id')
                    ->where('type', 'remix');
    }

    public function trackGenres(){
        // return $this->hasMany('App\CompetitionTrackGenres', 'competition_track_id', 'id');
        return $this->hasOne('App\CompetitionTrackGenres', 'competition_track_id', 'id');
    }

	public function getTrackGenresAttribute(){
        $ids = $this->trackGenres()->lists('genre_id');
        // $genre_names = $this->trackGenresNames($ids)->get();
        $genre_names = $this->trackGenresNames($ids)->get();
        return $genre_names ;
    }

    public function trackGenresNames($genres){
       $result = \DB::table('genres')
                    ->whereIn('genres.id',$genres)
                    ->select('genres.*');
        return $result;
    }

    public function getCoverImageAttribute(){
            $coverImageLink = $this->attributes['cover_image'];
            $coverImageLink = str_replace('large.jpg', 't500x500.jpg', $coverImageLink);
            return $coverImageLink;
    }

    static function getPenddingTracksCount()
    {
        return self::where('status',NULL)->count();
    }


    public static function boot(){
        parent::boot();

        CompetitionArtist::deleting(function($competitionArtist){ 

            $competitionTrackGenres = CompetitionTrackGenres::where('competition_track_id', $competitionArtist['id'])->get();
            foreach ($competitionTrackGenres as $competitionTrackGenre) {
                $competitionTrackGenre->delete();
            }

            $playHistoryDetails = PlayHistoryDetails::where('track_id', $competitionArtist['id'])->where('type', 'remix')->get();
            foreach ($playHistoryDetails as $playHistoryDetail) {
                $playHistoryDetail->delete();
            }
 
        });
    }


}
