<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SoundcloudArtist extends Model
{
    protected $table = 'sc_artist';

    protected $fillable = ['artist_id', 'sc_id', 'track_name', 'description', 'cover_image', 'url','secret_token'];


    public function genres(){
        return $this->hasMany('App\ScTrackGenres', 'sc_track_id', 'id');
    }


    // public function genres(){
    //     return $this->belongsToMany('App\Genres', 'sc_track_genres', 'sc_track_id','genre_id')
    //                 ->withPivot('type')->wherePivot('type','parent');
    // }

    // public function subGenres(){
    //     return $this->belongsToMany('App\Genres', 'sc_track_genres', 'sc_track_id','genre_id')
    //                 ->withPivot('type')->wherePivot('type','sub');
    // }

    public static function boot(){
        parent::boot();

        SoundcloudArtist::deleting(function($soundcloudArtist){ 

            $scTrackGenres = ScTrackGenres::where('sc_track_id', $soundcloudArtist['id'])->get();
            foreach ($scTrackGenres as $scTrackGenre) {
                $scTrackGenre->delete();
            }
 
        });
    }

}