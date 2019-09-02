<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Campaign extends Model
{
	use Sluggable;

    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'campaigns';
	protected $guarded = [];
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['track_name', 'track_id', 'user_id', 'type', 'genres', 'spotify_terms', 'souncloud_terms', 'facebook_terms', 'twitter_terms', 'youtube_terms', 'instagram_terms', 'mp3_file', 'cover_image', 'background_file','slug','playlists_link','track_link','album_link','spotify_artist_links','artist_links','soundcloud_tracks', 'download_url','status', 'blur','external_download_link'];
    
    protected $appends = ['artist_name','artist_avatar','artist_slug','favourite_count','track_genres','track_sub_genres','campaign_moods','play_count', 'download_count', 'visit_count', 'soundcloud_user_id', 'track_type'];

	// use SoftDeletes;

	public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'track_name'
            ]
        ];
    }

    public function getSoundcloudUserIdAttribute()
    {
        if(isset($this->attributes['user_id'])){
            $scAccount = \App\OauthIdentity::where('provider', 'soundcloud')->where('user_id', $this->user_id)->select('provider_user_id')->orderBy('created_at')->first();
            return $scAccount ? $scAccount->provider_user_id : null;
        }
        return null;
    }

    public function profile()
	{
		return $this->belongsTo('\App\ArtistsProfile', 'user_id', 'user_id')->select('first_name','last_name','avatar','id','genre','name');
		//->with(['thumbnail']);
	}

    public function getMp3FileAttribute(){
        if($this->attributes['type'] == 'remix'){
            // if(isset($this->attributes['external_download_link']) && $this->attributes['external_download_link']){
            //     return $this->attributes['external_download_link'];
            // }
            // return $this->attributes['mp3_file'].'?client_id='.env('SOUNDCLOUD_KEY');
            if(!strpos($this->attributes['mp3_file'], 'client_id'))
                return $this->attributes['mp3_file'].'?client_id='.env('SOUNDCLOUD_KEY');
            // return $this->attributes['external_download_link'];
        }else{
            return $this->attributes['mp3_file'];
        }
    }


    // public function getDownloadUrlAttribute(){
    //     if($this->attributes['type'] == 'remix'){
    //         $remixLink = $this->attributes['external_download_link'];
    //         $remixLink = str_replace('dl=0', 'dl=1', $remixLink);


    //         return $remixLink;
    //         // return $this->attributes['download_url'].'?client_id='.env('SOUNDCLOUD_KEY');
    //     }else{
    //         return $this->attributes['download_url'];
    //     }
    // }

    public function getCoverImageAttribute(){
        if($this->attributes['type'] == 'remix'){
            $coverImageLink = $this->attributes['cover_image'];
            $coverImageLink = str_replace('large.jpg', 't500x500.jpg', $coverImageLink);


            return $coverImageLink;
            // return $this->attributes['download_url'].'?client_id='.env('SOUNDCLOUD_KEY');
        }else{
            return $this->attributes['cover_image'];
        }
    }


    public function getExternalDownloadLinkAttribute(){
        if($this->attributes['type'] == 'remix'){
            $remixLink = $this->attributes['external_download_link'];
            $remixLink = str_replace('dl=0', 'dl=1', $remixLink);


            return $remixLink;
            // return $this->attributes['download_url'].'?client_id='.env('SOUNDCLOUD_KEY');
        }else{
            return $this->attributes['external_download_link'];
        }
    }


    public function getDownloadCountAttribute(){
        $downloadCount = $this->downloadHistory()->count();
        return $downloadCount;
    }

    public function downloadHistory(){
        return $this->hasMany('App\DownloadHistory', 'campaign_id', 'id');
    }

    // public function getAvatarAttribute()
    // {
    //     return $this->attributes['avatar'] ? 'uploads/artist/profiles/'.$this->attributes['avatar'] : '';
    // }

    public function getArtistNameAttribute(){
        $artist = $this->artist()->first(); 
        return $artist ? $artist->name : null;
    }

    public function getTrackTypeAttribute(){ 
        return $type = "campaign";
    }

    public function genres(){
        return $this->belongsToMany('App\Genres', 'campaign_genres', 'campaign_id','genre_id')
        			->withPivot('type')->wherePivot('type','parent');
    }

    public function allGenres(){
        return $this->belongsToMany('App\Genres', 'campaign_genres', 'campaign_id','genre_id');
                    
    }

    public function subGenres(){
        return $this->belongsToMany('App\Genres', 'campaign_genres', 'campaign_id','genre_id')
        			->withPivot('type')->wherePivot('type','sub');
    }

    public function moods(){
        return $this->belongsToMany('App\Mood', 'campaign_moods', 'campaign_id','mood_id');
    }

      //Get tracks genres 
    public function getCampaignMoodsAttribute(){
        $ids = $this->campaignMoods()->lists('mood_id');
        $genre_names = $this->campaignMoodNames($ids)->select('name','id','slug')->get();
        // $genre_names = $this->campaignMoodNames($ids)->select('name','id')->get();
        return $genre_names;
    }

    public function campaignMoods(){
        // return $this->hasMany('App\CampaignMoods', 'campaign_id', 'id');
        return $this->hasOne('App\CampaignMoods', 'campaign_id', 'id');
    }

    public function campaignMoodNames($genres){
       $result = \DB::table('moods')
					->whereIn('moods.id',$genres)
					->select('moods.*');
		return $result;
    }

    public function getPlayCountAttribute(){
         $totalcount = $this->playHistory()->sum('count');
        return $playHistory = $totalcount?$totalcount:0;
        
        // return $playHistory = $this->playHistory()->sum('count');
        // $playHistory = $this->playHistory()->first();
        // return $playHistory ? $playHistory->count : 0 ;
    }

    public function playHistory(){
        return $this->hasMany('App\PlayHistory', 'campaign_id', 'id');
        // return $this->hasOne('App\PlayHistory', 'campaign_id', 'id')->where('type','campaign');
    }

    //Get tracks genres 
    public function getTrackGenresAttribute(){
        $ids = $this->campaignGenres()->lists('genre_id'); 
        // $genre_names = $this->campaignGenresNames($ids)->select('name','id','parent_id')->get();
        $genre_names = $this->campaignGenresNames($ids)->select('name','id','parent_id','slug')->get();
        return $genre_names;
    }

    public function campaignGenres(){
        return $this->hasOne('App\CampaignGenres', 'campaign_id', 'id')->where('type','parent');
        // return $this->hasMany('App\CampaignGenres', 'campaign_id', 'id')->where('type','parent');
    }

    public function campaignGenresNames($genres){
       $result = \DB::table('genres')
					->whereIn('genres.id',$genres)
					->select('genres.*');
		return $result;
    }
    // Get tracks sub-genres 
    public function getTrackSubGenresAttribute(){
        $ids = $this->campaignSubGenres()->lists('genre_id'); 
        // $genre_names = $this->campaignSubGenresNames($ids)->get();
        $genre_names = $this->campaignSubGenresNames($ids)->get();
        return $genre_names ? $genre_names : '';
    }

    public function campaignSubGenres(){
        return $this->hasOne('App\CampaignGenres', 'campaign_id', 'id')->where('type','sub');
        // return $this->hasMany('App\CampaignGenres', 'campaign_id', 'id')->where('type','sub');

    }

    public function campaignSubGenresNames($genres){
       $result = \DB::table('genres')
					->whereIn('genres.id',$genres)
					->select('genres.*');
		return $result;
    }

	public function getFavouriteCountAttribute(){
        $favouriteCount = $this->favourites()->count();
        return $favouriteCount;
    }
    
    public function getArtistSlugAttribute(){
        $artist = $this->artist()->first(); 
        return $artist ? $artist->slug : '';
    }

    public function getArtistAvatarAttribute(){
        $artist = $this->artist()->first(); 
        return $artist ? $artist->avatar : '';
    }

    public function artist(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function favourites(){
        return $this->hasMany('App\Favourite', 'campaign_id', 'id');
    }

    public function playlistTracks()
    {
        return $this->belongsToMany('App\Playlist', 'playlist_tracks', 'track_id', 'playlist_id');
    }

	static function getUnregisteredUsersTracks()
	{
		return self::where('campaigns.user_id', '=', 0)
				->select('id','title','soundcloud_url','avatar_url','username','status','favourite');
	}

	static function getMembersTracks()
	{
		return self::where('campaigns.user_id', '!=', 0)
				->select('id','title','soundcloud_url','avatar_url','username','status','favourite');
	}

	static function getProUsersTracks()
	{
		$result = \DB::table('tracks')->join('users', 'campaigns.user_id', '=', 'users.id')
					->where('users.subscription','=','PROPREMIUM')
					->orWhere('users.subscription','=','PROUNLIMITED')
					->select('campaigns.id','campaigns.title', 'campaigns.soundcloud_url','campaigns.avatar_url','campaigns.username','campaigns.status','campaigns.favourite');
		return $result;
	}

	static function getFavouritesTracks()
	{
		// $result = \DB::table('tracks')->join('favourites', 'campaigns.id', '=', 'favourites.track_id')
		// 			->join('users', 'favourites.user_id', '=', 'users.id')
		// 			->orWhere('users.user_type','=','admin')
		// 			->select('campaigns.id','campaigns.title', 'campaigns.soundcloud_url','campaigns.avatar_url','campaigns.username','campaigns.status');
		// return $result;
		return self::where('campaigns.favourite', '=', 1)
				->select('id','title','soundcloud_url','avatar_url','username','status');
	}

	static function getDeletedTracks()
	{
		return Campaign::onlyTrashed()->select('id','title','soundcloud_url','avatar_url','username');
	}

	public function users()
	{
		return $this->belongsToMany('\App\User', 'favourites', 'track_id', 'user_id');
	}	
	static function getArtistTracks($id)
	{
		return self::where('campaigns.user_id', '=', $id)
				->select('id','title','soundcloud_url','avatar_url','username','status','favourite')->get();
	}


    public function campaignVisit(){
        return $this->hasMany('App\CampaignVisit', 'campaign_id', 'id');
    }

    public function getVisitCountAttribute(){
        $visitCount = $this->campaignVisit()->count();
        return $visitCount ? $visitCount : 0 ;
    }


    public static function boot(){
        parent::boot();

        Campaign::deleting(function($campaign){
            // echo '<pre>'; print_r($campaign); die; 

            $activities = Activity::where('object_id', $campaign['id'])
                                  ->where('object_type','campaign')
                                  ->get();
            foreach ($activities as $activity) {
                $activity->delete();
            }

            $videoReleases = StmVideoRelease::where('track_id', $campaign['id'])->get();
            foreach ($videoReleases as $videoRelease) {
                $videoRelease->delete();
            }

            $campaignGenres = CampaignGenres::where('campaign_id', $campaign['id'])->get();
            foreach ($campaignGenres as $campaignGenre) {
                $campaignGenre->delete();
            }

            $campaignMoods = CampaignMoods::where('campaign_id', $campaign['id'])->get();
            foreach ($campaignMoods as $campaignMood) {
                $campaignMood->delete();
            }


            $campaignVisits = CampaignVisit::where('campaign_id', $campaign['id'])->get();
            foreach ($campaignVisits as $campaignVisit) {
                $campaignVisit->delete();
            }

            $trackDemos = TrackDemo::where('campaign_id', $campaign['id'])->get();
            foreach ($trackDemos as $trackDemo) {
                $trackDemo->delete();
            }

            $favourites = Favourite::where('campaign_id', $campaign['id'])->get();
            foreach ($favourites as $favourites) {
                $favourites->delete();
            }

            $playlistTracks = PlaylistTrack::where('campaign_id', $campaign['id'])->get();
            foreach ($playlistTracks as $playlistTrack) {
                $playlistTrack->delete();
            }


        });


        Campaign::updated(function($campaign){

            $campaign = $campaign->toArray();
            $demoTracks = \App\TrackDemo::where('campaign_id', $campaign['id'])->get();
            unset($campaign['slug']);
            foreach ($demoTracks as $demoTrack) {

                $demoTrack->track_name = $campaign['track_name'];
                $demoTrack->artist_links = $campaign['artist_links'];
                $demoTrack->souncloud_terms = $campaign['souncloud_terms'];
                $demoTrack->facebook_terms = $campaign['facebook_terms'];
                $demoTrack->twitter_terms = $campaign['twitter_terms'];
                $demoTrack->youtube_terms = $campaign['youtube_terms'];
                $demoTrack->instagram_terms = $campaign['instagram_terms'];
                $demoTrack->mp3_file = $campaign['mp3_file'];
                $demoTrack->cover_image = $campaign['cover_image'];
                $demoTrack->background_file = $campaign['background_file'];
                $demoTrack->download_url = $campaign['download_url'] ? $campaign['download_url'] : '';
                $demoTrack->external_download_link = $campaign['external_download_link'] ? $campaign['external_download_link'] : '';

                if($campaign['soundcloud_tracks']){
                    $demoTrack->sc_id = $campaign['soundcloud_tracks'];
                }

                $demoTrack->save();
            }
            
        });
    }

}
