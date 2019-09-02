<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScTrackGenres extends Model
{
    protected $table = 'sc_track_genres';

    protected $fillable = ['sc_track_id', 'genre_id'];
}