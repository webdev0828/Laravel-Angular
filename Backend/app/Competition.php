<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'competitions';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'track_id', 'start_date', 'end_date', 'announcement_date', 'stem_file', 'description', 'price_1', 'price_2', 'price_3', 'status', 'published_date'];
	// protected $appends = ['competition_genres','competition_sub_genres'];
	public function CompetitionArtist(){
        return $this->hasMany('App\CompetitionArtist', 'competition_id', 'id');
    }

    public function genres(){
        return $this->belongsToMany('App\Genres', 'competition_genres', 'competition_id', 'genre_id')
        			->withPivot('type')->wherePivot('type','parent');
    }

    public function subGenres(){
        return $this->belongsToMany('App\Genres', 'competition_genres', 'competition_id','genre_id')
        			->withPivot('type')->wherePivot('type','sub');
    }


    public static function boot(){
        parent::boot();

        Competition::deleting(function($competition){

            $competitionArtists=CompetitionArtist::where('competition_id', $competition['id'])->get();
            foreach ($competitionArtists as $competitionArtist) {
                $competitionArtist->delete();
            }

            $competitionGenres=CompetitionGenre::where('competition_id', $competition['id'])->get();
            foreach ($competitionGenres as $competitionGenre) {
                $competitionGenre->delete();
            }

            $competitionWinners=CompetitionWinner::where('competition_id', $competition['id'])->get();
            foreach ($competitionWinners as $competitionWinner) {
                $competitionWinner->delete();
            }
        });
    }
}