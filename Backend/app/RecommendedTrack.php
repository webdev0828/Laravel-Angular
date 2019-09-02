<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecommendedTrack extends Model
{
    protected $table = 'recommended_tracks';

    protected $fillable = ['name','file','artwork_file','background_image'];
}
