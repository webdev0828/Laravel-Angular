<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'favourites';
	protected $guarded = [];
	// public $timestamps = false;


	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['track_id', 'user_id', 'campaign_id', 'type'];

	protected $appends = ['artist_name', 'artist_slug', 'favourite_count', 'play_count', 'track_genres', 'track_sub_genres', 'track_moods','track_genres_slug','track_sub_genres_slug','track_moods_slug'];




	public function artist($id){
        // return $this->belongsTo('App\User', 'user_id', 'id');
        $result = \App\User::where('id',$id);
        return $result;
    }

    public function getArtistNameAttribute(){
        // $artist = $this->artist()->first();
        // return $artist->name;
        $artist = $this->artist($this->attributes['user_id'])->first();
        return $artist ? $artist->name : null;
    }

    public function getArtistSlugAttribute(){
        // $artist = $this->artist()->first(); 
        // return $artist->slug;
        $artist = $this->artist($this->attributes['user_id'])->first();
        return $artist ? $artist->slug : null;
    }




    public function getFavouriteCountAttribute(){
        $favouriteCount = $this->favourites()->count();
        return $favouriteCount;
    }

    public function favourites(){
    	return $this->where('campaign_id', $this->id)
    				->where('type', 'campaign');
    				
    	
    }




    public function getPlayCountAttribute(){
        $playHistory = $this->playHistory()->first();
        return $playHistory ? $playHistory->count : 0 ;
    }

    public function playHistory(){
        return $this->hasOne('App\PlayHistory', 'campaign_id', 'id');
    }




    public function getTrackGenresAttribute(){
        $ids = $this->campaignGenres($this->id)->lists('genre_id');
		$genre_name = [];
		if($ids)
			$genre_name = $this->trackGenresNames($ids)->first();
		return $genre_name ? $genre_name->name  : null ;
    }

    public function getTrackGenresSlugAttribute(){

        $ids = $this->campaignGenres($this->id)->lists('genre_id');
        $genre_name = [];
        if($ids)
            $genre_name = $this->trackGenresNames($ids)->first();
        return $genre_name ? $genre_name->slug  : null ;
    }

    public function campaignGenres($campaign_id){
    	$result = \DB::table('campaign_genres')
                    ->where('campaign_genres.campaign_id',$campaign_id)->where('type','parent');
        return $result;
    }

    public function trackGenresNames($genres){
    	$result = \App\Genres::whereIn('id',$genres);
        return $result;
    }




    public function getTrackSubGenresAttribute(){

        $ids = $this->campaignSubDemoGenres($this->id)->lists('genre_id');   // "this->id" is track id
		$genre_name = [];
		if($ids)
			$genre_name = $this->trackGenresNames($ids)->first();
		return $genre_name ? $genre_name->name  : null ;
	}

    public function getTrackSubGenresSlugAttribute(){
        $ids = $this->campaignSubDemoGenres($this->id)->lists('genre_id');   // "this->id" is track id
        $genre_name = [];
        if($ids)
            $genre_name = $this->trackGenresNames($ids)->first();
        return $genre_name ? $genre_name->slug  : null ;
    }

    public function campaignSubDemoGenres($campaign_id){
    	$result = \App\CampaignGenres::where('campaign_id',$campaign_id)
    								 ->where('type','sub');
        return $result;
    }




    public function getTrackMoodsAttribute(){

        $ids = $this->campaignDemoMoods($this->id)->lists('mood_id');
		$mood = [];
		if($ids)
			$mood = $this->trackMoodNames($ids)->first();
		return $mood ? $mood->name  : null ;
		
    }

    public function getTrackMoodsSlugAttribute(){

        $ids = $this->campaignDemoMoods($this->id)->lists('mood_id');
        $mood = [];
        if($ids)
            $mood = $this->trackMoodNames($ids)->first();
        return $mood ? $mood->slug  : null ;
    }

    public function campaignDemoMoods($track_id){
    	$result = \App\CampaignMoods::where('campaign_id', $track_id);
        return $result;
    }

    public function trackMoodNames($moods){
    	$result = \App\Mood::whereIn('id', $moods);
        return $result;
    }

}