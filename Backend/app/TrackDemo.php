<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class TrackDemo extends Model
{
    use Sluggable;

    protected $table = 'track_demos';
    protected $fillable = ['user_id','campaign_id', 'track_name', 'type', 'status', 'slug', 'spotify_artist_links', 'playlists_link', 'track_link', 'albumn_link', 'artist_links', 'souncloud_terms', 'facebook_terms', 'twitter_terms', 'youtube_terms', 'instagram_terms', 'mp3_file', 'cover_image', 'background_file', 'repost_track_id','sc_id', 'download_url','external_download_link', 'blur', 'check'];
    protected $guarded = [];
    protected $appends = ['artist_name','artist_avatar','artist_slug','favourite_count','track_genres','track_sub_genres','track_moods','play_count', 'download_count', 'soundcloud_user_id', 'track_type'];


    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'track_name'
            ]
        ];
    }

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




    public function getSoundcloudUserIdAttribute()
    {
        if($this->user_id){
            $scAccount = \App\OauthIdentity::where('provider', 'soundcloud')->where('user_id', $this->user_id)->select('provider_user_id')->first();
            return $scAccount ? $scAccount->provider_user_id : null;
        }
        return null;
    }

    // public function getAvatarAttribute()
    // {
    //     return $this->attributes['avatar'] ? 'uploads/artist/profiles/'.$this->attributes['avatar'] : '';
    // }

    public function getMp3FileAttribute(){
        if($this->attributes['type'] == 'remix'){

            // return $this->attributes['external_download_link'];
            // if($this->external_download_link){
            //     return $this->attributes['external_download_link'];
            // }
            if(!strpos($this->attributes['mp3_file'], 'client_id'))
                return $this->attributes['mp3_file'].'?client_id='.env('SOUNDCLOUD_KEY');
        }

        if($this->attributes['type'] == 'music_video' && $this->attributes['slug'] && $this->attributes['user_id']){
            $track = \App\TrackDemo::where('slug',$this->attributes['slug'])->where('user_id',$this->attributes['user_id'])->select('sc_id')->first();
            $scAccount = \App\SoundcloudArtist::where('sc_id', $track->sc_id)->where('artist_id',$this->attributes['user_id'])->select('secret_token')->first();
            if($scAccount) {
                $secret_token = $scAccount->secret_token;
            
                if($secret_token)
                    return $this->attributes['mp3_file'].'?secret_token='.$secret_token.'&client_id='.env('SOUNDCLOUD_KEY');
            }
        }
        return $this->attributes['mp3_file'];
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

    public function getArtistNameAttribute(){
        $artist = $this->artist()->first();
        return $artist? $artist->name:'';
    }

    public function getTrackTypeAttribute(){ 
        return "track";
    }

    public function getPlayCountAttribute(){
         $totalcount = $this->playHistory()->sum('count');
        return $playHistory = $totalcount?$totalcount:0;
        // return $playHistory = $this->playHistory()->sum('count');
    }

    public function playHistory(){
        return $this->hasMany('App\PlayHistory', 'campaign_id', 'campaign_id');
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

    public function profile()
    {
        return $this->belongsTo('\App\ArtistsProfile', 'user_id')->select('first_name','last_name','avatar','id','genre','name');
        //->with(['thumbnail']);
    }

    public function genres(){
        return $this->belongsToMany('App\Genres', 'track_demo_genres', 'demo_track_id','genre_id')
                    ->withPivot('type')->wherePivot('type','parent');
    }

    public function allGenres(){
        return $this->belongsToMany('App\Genres', 'track_demo_genres', 'demo_track_id','genre_id');
                    
    }

    public function subGenres(){
        return $this->belongsToMany('App\Genres', 'track_demo_genres', 'demo_track_id','genre_id')
                    ->withPivot('type')->wherePivot('type','sub');
    }

    public function moods(){
        return $this->belongsToMany('App\Mood', 'track_demo_moods', 'demo_track_id','mood_id');
    }


    public function getTrackGenresAttribute(){
        $ids = $this->trackGenres()->lists('genre_id');
        // $genre_names = $this->trackGenresNames($ids)->get();
        $genre_names = $this->trackGenresNames($ids)->get();
        return $genre_names ? $genre_names  : [] ;
    }

    public function trackGenres(){
        // return $this->hasMany('App\TrackDemoGenres', 'demo_track_id', 'id')
        //                      ->where('type','parent');
        return $this->hasOne('App\TrackDemoGenres', 'demo_track_id', 'id')
                             ->where('type','parent');
    }

    public function trackGenresNames($genres){
       $result = \DB::table('genres')
                    ->whereIn('genres.id',$genres)
                    ->select('genres.*');
        return $result;
    }

     public function getTrackSubGenresAttribute(){
        $ids = $this->trackDemoSubGenres()->lists('genre_id'); 
        $genre_names = $this->trackDemoSubGenresNames($ids)->get();
        // $genre_names = $this->trackDemoSubGenresNames($ids)->get();
        return $genre_names ? $genre_names  : [] ;
    }

    public function trackDemoSubGenres(){
        // return $this->hasMany('App\TrackDemoGenres', 'demo_track_id', 'id');
        return $this->hasOne('App\TrackDemoGenres', 'demo_track_id', 'id')
                           ->where('type','sub');
    }

    public function trackDemoSubGenresNames($genres){
       $result = \DB::table('genres')
                    ->whereIn('genres.id',$genres)
                    ->select('genres.*');
        return $result;
    }

       //Get tracks moods 
    public function getTrackMoodsAttribute(){
        $ids = $this->trackDemoMoods()->lists('mood_id'); 
        // $genre_names = $this->trackMoodNames($ids)->select('name','id')->get();
        $genre_names = $this->trackMoodNames($ids)->select('name','id','slug')->get();
        return $genre_names ? $genre_names  : [] ;
    }

    public function trackDemoMoods(){
        // return $this->hasMany('App\TrackDemoMoods', 'demo_track_id', 'id');
        return $this->hasOne('App\TrackDemoMoods', 'demo_track_id', 'id');
    }

    public function trackMoodNames($genres){
       $result = \DB::table('moods')
                    ->whereIn('moods.id',$genres)
                    ->select('moods.*');
        return $result;
    }


    static function getPenddingTracksCount()
    {
        return self::where('status', 'pending')->count();
    }



    public function getFavouriteCountAttribute(){
        $favouriteCount = $this->favourites()->count();
        return $favouriteCount;
    }

    public function favourites(){
        return $this->hasMany('App\Favourite', 'campaign_id', 'campaign_id')
                    ->where('type', 'campaign');
    }


    public function getDownloadCountAttribute(){
        $downloadCount = $this->downloadHistory()->count();
        return $downloadCount;
    }

    public function downloadHistory(){
        return $this->hasMany('App\DownloadHistory', 'track_id', 'id');
    }

    public static function boot(){
        parent::boot();

        TrackDemo::deleting(function($trackDemo){
            // echo '<pre>'; print_r($campaign); die; 

            $activities = Activity::where('object_id', $trackDemo['id'])
                                  ->where('object_type','track')
                                  ->get();
            foreach ($activities as $activity) {
                $activity->delete();
            }

            $trackDemoGenres = TrackDemoGenres::where('demo_track_id', $trackDemo['id'])->get();
            foreach ($trackDemoGenres as $trackDemoGenre) {
                $trackDemoGenre->delete();
            }

            $trackDemoMoods = TrackDemoMoods::where('demo_track_id', $trackDemo['id'])->get();
            foreach ($trackDemoMoods as $trackDemoMood) {
                $trackDemoMood->delete();
            }

            $favourites = Favourite::where('track_id', $trackDemo['id'])->get();
            foreach ($favourites as $favourite) {
                $favourite->delete();
            }

            $playlistTracks = PlaylistTrack::where('track_id', $trackDemo['id'])->get();
            foreach ($playlistTracks as $playlistTrack) {
                $playlistTrack->delete();
            }

            $topItems = TopItems::where('object_id', $trackDemo['id'])->where('object_type', 'spotlight_discover')->get();
            foreach ($topItems as $topItem) {
                $topItem->delete();
            }

            $playHistoryDetails = PlayHistoryDetails::where('track_id', $trackDemo['id'])->whereIn('type', ['video','track'])->get();
            foreach ($playHistoryDetails as $playHistoryDetail) {
                $playHistoryDetail->delete();
            }
            
        });
    }
}
