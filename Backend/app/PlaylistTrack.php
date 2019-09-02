<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlaylistTrack extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'playlist_tracks';
	protected $guarded = [];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['playlist_id', 'track_id', 'type'];

	// protected $appends = ['artist_name','artist_slug','favourite_count','track_genres','track_sub_genres','campaign_moods','play_count', 'download_count', 'visit_count', 'soundcloud_user_id', 'track_type'];

	protected $appends = ['artist_slug','artist_name','track_genres','track_sub_genres','track_moods','favourite_count','play_count', 'mp3_file','track_genres_slug','track_sub_genres_slug','track_moods_slug'];	


	public function getArtistNameAttribute(){
		
         $artist = $this->artist($this->attributes['user_id'])->first();
         return $artist ? $artist->name : null;
    }

    public function getArtistSlugAttribute(){
		
        $artist = $this->artist($this->attributes['user_id'])->first();
        return $artist ? $artist->slug : null;
    }

    public function artist($track_id){
       $result = \App\User::where('id',$track_id);
	   return $result;
    }

    public function getFavouriteCountAttribute(){
        $favouriteCount = $this->favourites()->count();
        return $favouriteCount ? $favouriteCount : 0;
    }

    public function favourites(){
    	
    		return $this->hasMany('App\Favourite', 'campaign_id', 'campaign_id')
                    ->where('type', 'campaign');
    	
    }

    public function getPlayCountAttribute(){
        return $playHistory = $this->playHistory()->sum('count');
    }

    public function playHistory(){
    	
    		return $this->hasMany('App\PlayHistory', 'campaign_id', 'campaign_id');
    	
    }

  
    public function getMp3FileAttribute(){
        if($this->attributes['type'] == 'remix' || $this->attributes['type'] == 'competition_remix'){
            // if($this->external_download_link){
            //     return $this->attributes['external_download_link'];
            // }
            if(!strpos($this->attributes['mp3_file'], 'client_id'))
                return $this->attributes['mp3_file'].'?client_id='.env('SOUNDCLOUD_KEY');
        }
        
        return $this->attributes['mp3_file'];
    }

    public function getTrackGenresAttribute(){

    	
			$ids = $this->campaignDemoGenres($this->attributes['campaign_id'] )->lists('genre_id');
			$genre_name = [];
			if($ids)
				$genre_name = $this->trackGenresNames($ids)->first();
			return $genre_name ? $genre_name->name  : null ;
		
    }
    public function getTrackGenresSlugAttribute(){

        
            $ids = $this->campaignDemoGenres($this->attributes['campaign_id'] )->lists('genre_id');
            $genre_name = [];
            if($ids)
                $genre_name = $this->trackGenresNames($ids)->first();
            return $genre_name ? $genre_name->slug  : null ;
        
    }

    // public function competitionDemoGenres($track_id){
    //     $result = \App\CompetitionTrackGenres::where('competition_track_id',$track_id);
    //     return $result;
    // }

    // public function trackDemoGenres($track_id){
    // 	$result = \App\TrackDemoGenres::where('demo_track_id',$track_id)
    // 								 ->where('type','parent');
    //     return $result;
    // }

    public function campaignDemoGenres($campaign_id){
        $result = \App\CampaignGenres::where('campaign_id',$campaign_id)
                                     ->where('type','parent');
        return $result;
    }


    public function getTrackSubGenresAttribute(){

			$ids = $this->campaignSubDemoGenres($this->attributes['campaign_id'] )->lists('genre_id');
			$genre_name = [];
			if($ids)
				$genre_name = $this->trackGenresNames($ids)->first();
			return $genre_name ? $genre_name->name  : null ;
		
    }
    public function getTrackSubGenresSlugAttribute(){

            $ids = $this->campaignSubDemoGenres($this->attributes['campaign_id'] )->lists('genre_id');
            $genre_name = [];
            if($ids)
                $genre_name = $this->trackGenresNames($ids)->first();
            return $genre_name ? $genre_name->slug  : null ;
        
    }

    // public function trackSubDemoGenres($track_id){
    // 	$result = \App\TrackDemoGenres::where('demo_track_id',$track_id)
    // 								 ->where('type','sub');
    //     return $result;
    // }

    public function campaignSubDemoGenres($campaign_id){
    	$result = \App\CampaignGenres::where('campaign_id',$campaign_id)
    								 ->where('type','sub');
        return $result;
    }



    public function trackGenresNames($genres){
    	$result = \App\Genres::whereIn('id',$genres);
        return $result;
    }


    public function getTrackMoodsAttribute(){

    	
			$ids = $this->campaignDemoMoods($this->attributes['campaign_id'] )->lists('mood_id');
			$mood = [];
			if($ids)
				$mood = $this->trackMoodNames($ids)->first();
			return $mood ? $mood->name  : null ;
		
    }
    public function getTrackMoodsSlugAttribute(){

   
            $ids = $this->campaignDemoMoods($this->attributes['campaign_id'] )->lists('mood_id');
            $mood = [];
            if($ids)
                $mood = $this->trackMoodNames($ids)->first();
            return $mood ? $mood->slug  : null ;
        
    }

    // public function trackDemoMoods($track_id){
    //     $result = \App\TrackDemoMoods::where('demo_track_id',$track_id);
    //     return $result;
    // }

    public function campaignDemoMoods($track_id){
    	$result = \App\CampaignMoods::where('campaign_id',$track_id);
        return $result;
    }

    public function trackMoodNames($genres){
    	$result = \App\Mood::whereIn('id',$genres);
        return $result;
    }
}
