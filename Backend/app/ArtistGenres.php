<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArtistGenres extends Model
{
    protected $table = 'artist_genres';

    protected $fillable = ['user_id', 'genre_id'];

    // public function genres(){
    //     return $this->belongsToMany('App\Genres', 'artist_genres', 'artist_id','genre_id');
    // }
}