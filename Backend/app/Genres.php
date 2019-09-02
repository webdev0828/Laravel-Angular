<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Genres extends Model
{
	use Sluggable;
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'genres';
	protected $guarded = [];


	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */

	protected $fillable = ['name', 'description', 'parent_id'];

	public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

	### Sub Genres Relation
	public function subGenres()
	{
		return $this->hasMany('App\Genres', 'parent_id');
	}


	public static function boot(){
        parent::boot();

        Genres::deleting(function($genre){
            $artistGenres = ArtistGenres::where('genre_id', $genre['id'])->get();
            foreach ($artistGenres as $artistGenre) {
                $artistGenre->delete();
            }

            $campaignGenres = CampaignGenres::where('genre_id', $genre['id'])->get();
            foreach ($campaignGenres as $campaignGenre) {
                $campaignGenre->delete();
            }

            $competitionGenres = CompetitionGenre::where('genre_id', $genre['id'])->get();
            foreach ($competitionGenres as $competitionGenre) {
                $competitionGenre->delete();
            }

            $competitionTrackGenres = CompetitionTrackGenres::where('genre_id', $genre['id'])->get();
            foreach ($competitionTrackGenres as $competitionTrackGenre) {
                $competitionTrackGenre->delete();
            }

            $scTrackGenres = ScTrackGenres::where('genre_id', $genre['id'])->get();
            foreach ($scTrackGenres as $scTrackGenre) {
                $scTrackGenre->delete();
            }

            $stmReleasesGenres = StmReleasesGenre::where('genre_id', $genre['id'])->get();
            foreach ($stmReleasesGenres as $stmReleasesGenre) {
                $stmReleasesGenre->delete();
            }

            $trackDemoGenres = TrackDemoGenres::where('genre_id', $genre['id'])->get();
            foreach ($trackDemoGenres as $trackDemoGenre) {
                $trackDemoGenre->delete();
            }
                
        });
    }

}
