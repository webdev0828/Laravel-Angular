<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class ArtistsProfile extends Model
{
	// use Sluggable;

    protected $table = 'artists_profile';

    protected $fillable = ['user_id','slug','name','first_name','last_name','country','city','website','youtube_channel','paypal_email','avatar','cover','souncloud_url','facebook_url','twitter_url','youtube_url','instagram_url','bio'];

    // public function sluggable()
    // {
    //     return [
    //         'slug' => [
    //             'source' => 'name'
    //         ]
    //     ];
    // }

    public function getCoverAttribute()
    {
        return $this->attributes['cover'] ? $this->attributes['cover'] : config('constants.default_banner_image');
    }

    // public function getAvatarAttribute()
    // {
    //     return $this->attributes['avatar'] ? 'uploads/artist/profiles/'.$this->attributes['avatar'] : '';
    // }

    protected $appends = ['follower_count'];

    public function genres(){
        return $this->belongsToMany('App\Genres', 'artist_genres', 'artist_id','genre_id')
                    ->withPivot('type')->wherePivot('type','artist');
    }

    public function labelGenres(){
        return $this->belongsToMany('App\Genres', 'artist_genres', 'artist_id','genre_id')->withPivot('type')->wherePivot('type','label');
    }


    public function releaseTrack(){
        return $this->hasOne('App\ReleaseTracks', 'user_id','user_id');
    }

    public function followers(){
        return $this->hasMany('App\Follower', 'follower_id', 'user_id');
    }

    public function getFollowerCountAttribute(){
        $followerCount = $this->followers()->count();
        return $followerCount;
    }


    // public static function boot(){
    //     parent::boot();

    //     ArtistsProfile::deleting(function($artistsProfile){

    //         $artistCampaigns = Campaign::where('user_id', $artistsProfile['user_id'])->get();
    //         foreach ($artistCampaigns as $artistCampaign) {
    //             $artistCampaign->delete();
    //         }

    //         $artistTrackDemos = TrackDemo::where('user_id', $artistsProfile['user_id'])->get();
    //         foreach ($artistTrackDemos as $artistTrackDemo) {
    //             $artistTrackDemo->delete();
    //         }

    //         $stmVideoReleases = StmVideoRelease::where('artist_id', $artistsProfile['user_id'])->get();
    //         foreach ($stmVideoReleases as $stmVideoRelease) {
    //             if($stmVideoRelease->artwork_file){
    //               \App\libraries\GlobalHelper::deleteFile($stmVideoRelease->artwork_file);
    //             }
    //             if($stmVideoRelease->download_link){
    //               \App\libraries\GlobalHelper::deleteFile($stmVideoRelease->download_link);
    //             }
    //             $stmVideoRelease->delete();
    //         }

    //         $artistGenres = ArtistGenres::where('artist_id', $artistsProfile['user_id'])->get();
    //         foreach ($artistGenres as $artistGenre) {
    //             $artistGenre->delete();
    //         }

    //         $releaseTracks = ReleaseTracks::where('user_id', $artistsProfile['user_id'])->get();
    //         foreach ($releaseTracks as $releaseTrack) {
    //             $releaseTrack->delete();
    //         }


    //         $followers = Follower::where('user_id', $artistsProfile['user_id'])
    //                             ->orWhere('follower_id', $artistsProfile['user_id'])
    //                             ->get();
    //         foreach ($followers as $follower) {
    //             $follower->delete();
    //         }

    //         $activities = Activity::where('user_id', $artistsProfile['user_id'])
    //                                 ->orWhere('sender_id', $artistsProfile['user_id'])
    //                                 ->get();
    //         foreach ($activities as $activity) {
    //             $activity->delete();
    //         }

    //         $playlists = Playlist::where('user_id', $artistsProfile['user_id'])->get();
    //         foreach ($playlists as $playlist) {
    //             $playlist->delete();
    //         }

    //         $donations = Donation::where('artist_id', $artistsProfile['user_id'])
    //                             ->orWhere('user_id', $artistsProfile['user_id'])
    //                             ->get();
    //         foreach ($donations as $donation) {
    //             $donation->delete();
    //         }

    //         $soundCloudArtist = SoundcloudArtist::where('artist_id', $artistsProfile['user_id'])->get();
    //         foreach ($soundCloudArtist as $scArtist) {
    //             $scArtist->delete();
    //         }

    //         $planBilling = PlanBilling::where('user_id', $artistsProfile['user_id'])->get();
    //         foreach ($planBilling as $planBill) {
    //             $planBill->delete();
    //         }

    //         $topItems = TopItems::where('object_id', $artistsProfile['user_id'])->where('object_type', 'admin_video')->get();
    //         foreach ($topItems as $topItem) {
    //             $topItem->delete();
    //         }

    //         $competitionArtists = CompetitionArtist::where('artist_id', $artistsProfile['user_id'])->get();
    //         foreach ($competitionArtists as $competitionArtist) {
    //             $competitionArtist->delete();
    //         }

    //         $competitionWinners = CompetitionWinner::where('winner_id', $artistsProfile['user_id'])->get();
    //         foreach ($competitionWinners as $competitionWinner) {
    //             $competitionWinner->delete();
    //         }

    //         $playHistories = PlayHistory::where('artist_id', $artistsProfile['user_id'])->get();
    //         foreach ($playHistories as $playHistory) {
    //             $playHistory->delete();
    //         }

    //     });
    // }

}
