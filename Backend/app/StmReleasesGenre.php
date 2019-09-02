<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StmReleasesGenre extends Model
{
    protected $table = 'stm_releases_genres';

    protected $fillable = ['stm_releases_id','genre_id','type'];

    public function releasesGenreName(){
        return $this->belongsTo('\App\Genres', 'genre_id', 'id');
    }
}
