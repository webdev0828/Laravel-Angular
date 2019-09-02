<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompetitionTrackGenres extends Model
{
    protected $table = 'competition_track_genres';

    protected $fillable = ['competition_track_id', 'genre_id'];

}