<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class StmVideoRelease extends Model
{
    // use Sluggable;

    protected $table = 'stm_video_releases';

    protected $fillable = ['artist_id', 'track_id', 'track_name', 'url', 'artwork_file', 'download_link', 'slug', 'background_file', 'description'];

    // protected $appends = ['artist_name','artist_avatar','artist_slug','video_genres','video_sub_genres','video_moods', 'favourite_count','type'];

    protected $appends = ['artist_name', 'type', 'artist_slug'];


    // public function sluggable()
    // {
    //     return [
    //         'slug' => [
    //             'source' => 'track_id'    
    //         ]
    //     ];
    // }

    public function getAvatarAttribute()
    {
        return $this->attributes['avatar'] ? 'uploads/artist/profiles/'.$this->attributes['avatar'] : '';
    }

    public function artist(){
        return $this->belongsTo('\App\User', 'artist_id', 'id');
    }

    public function getArtistSlugAttribute(){
        $artist = $this->artist()->first(); 
        return $artist ? $artist->slug : '';
    }

    public function getArtistAvatarAttribute(){
        $artist = $this->artist()->first(); 
        return $artist ? $artist->avatar : '';
    }

    public function getTypeAttribute(){
        return $type = 'video';
    }

     public function getArtistNameAttribute(){
        $artist = $this->artist()->first(); 
        return $artist ? $artist->name : '';
    }


    public function campaign(){
        return $this->belongsTo('\App\Campaign', 'track_id', 'id');
    }

    // public function getVideoGenresAttribute(){
    //     $ids = $this->videoGenres()->lists('genre_id');
    //     // $genre_names = $this->videoGenresNames($ids)->get();
    //     $genre_names = $this->videoGenresNames($ids)->get();
    //     return $genre_names ? $genre_names  : [] ;
    // }

    // public function videoGenres(){
    //     // return $this->hasMany('App\StmReleasesGenre', 'stm_releases_id', 'id')
    //     //                     ->where('type','genre');
    //     return $this->hasOne('App\StmReleasesGenre', 'stm_releases_id', 'id')
    //                         ->where('type','genre');
    // }

    // public function getVideoSubGenresAttribute(){
    //     $ids = $this->videoSubGenres()->lists('genre_id');
    //     // $genre_names = $this->videoGenresNames($ids)->get();
    //     $genre_names = $this->videoGenresNames($ids)->get();
    //     return $genre_names ? $genre_names  : [] ;
    // }

    // public function videoSubGenres(){
    //     // return $this->hasMany('App\StmReleasesGenre', 'stm_releases_id', 'id')
    //     //                     ->where('type','genre');
    //     return $this->hasOne('App\StmReleasesGenre', 'stm_releases_id', 'id')
    //                         ->where('type','sub_genre');
    // }

    // public function videoGenresNames($genres){
    //    $result = \DB::table('genres')
    //                 ->whereIn('genres.id',$genres)
    //                 ->select('genres.*');
    //     return $result;
    // }


      //Get video moods 
    // public function getVideoMoodsAttribute(){
    //     $ids = $this->videoDemoMoods()->lists('moods_id'); 
    //     $genre_names = $this->videoMoodNames($ids)->select('name','id')->get();
    //     // $genre_names = $this->videoMoodNames($ids)->select('name','id')->get();
    //     return $genre_names;
    // }

    // public function videoDemoMoods(){
    //     // return $this->hasMany('App\StmReleasesMood', 'stm_releases_id', 'id');
    //     return $this->hasOne('App\StmReleasesMood', 'stm_releases_id', 'id');
    // }

    // public function videoMoodNames($genres){
    //    $result = \DB::table('moods')
    //                 ->whereIn('moods.id',$genres)
    //                 ->select('moods.*');
    //     return $result;
    // }


    // public function getFavouriteCountAttribute(){
    //     $favouriteCount = $this->favourites()->count();
    //     return $favouriteCount;
    // }

    public function favourites(){
        return $this->hasMany('App\FavouriteVideo', 'video_id', 'id');
    }



    // public function getVideoGenresAttribute(){
    //     $query = \App\Genres::join('campaign_genres', 'campaign_genres.genre_id', '=', 'genres.id')
    //                         ->where('campaign_genres.campaign_id', $this->attributes['track_id'])
    //                         ->where('type','parent')
    //                         ->select('genres.name')
    //                         ->first();

    //     return $query ? $query->name : '';
    // }

    // public function getVideoSubGenresAttribute(){
    //     $query = \App\Genres::join('campaign_genres', 'campaign_genres.genre_id', '=', 'genres.id')
    //                         ->where('campaign_genres.campaign_id', $this->attributes['track_id'])
    //                         ->where('type','sub')
    //                         ->select('genres.name')
    //                         ->first();

    //     return $query ? $query->name : '';
    // }

    public function genres(){
        return $this->hasMany('\App\StmReleasesGenre', 'stm_releases_id', 'id')->where('type','genre');
    }

    public function subGenres(){
        return $this->hasMany('\App\StmReleasesGenre', 'stm_releases_id', 'id')->where('type','sub_genre');
    }

    public function moods(){
        return $this->hasMany('\App\StmReleasesMood', 'stm_releases_id', 'id');
    }

    public function genreIds(){
        return $this->hasMany('\App\StmReleasesGenre', 'stm_releases_id', 'id')->lists();
    }


    public static function boot(){
        parent::boot();

        StmVideoRelease::deleting(function($campaign){
            // echo '<pre>'; print_r($campaign); die; 

            $stmReleasesGenres = StmReleasesGenre::where('stm_releases_id', $campaign['id'])->get();
            foreach ($stmReleasesGenres as $stmReleasesGenre) {
                $stmReleasesGenre->delete();
            }

            $stmReleasesMoods = StmReleasesMood::where('stm_releases_id', $campaign['id'])->get();
            foreach ($stmReleasesMoods as $stmReleasesMood) {
                $stmReleasesMood->delete();
            }

            
        });
    }

}
