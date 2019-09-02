<?php  namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class UserProfile extends Model
{
	use Sluggable;

    protected $table = 'user_profiles';

    protected $fillable = ['user_id','slug','name','first_name','last_name','country','city','website','youtube_channel','paypal_email','avatar','cover','souncloud_url','facebook_url','twitter_url','youtube_url','instagram_url','bio','isArtist'];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function getAvatarAttribute()
    {
        $path = $this->attributes['avatar'] ? $this->attributes['avatar'] : '';
        return $path;
    }

    public function getCoverAttribute()
    {
        $path = $this->attributes['cover'] ? $this->attributes['cover'] : config('constants.default_banner_image');
        return $path;
    }

    // public static function boot(){
    //     parent::boot();

    //     UserProfile::deleting(function($userProfile){

    //         if($userProfile['isArtist'] != 1){
    //             $userActivities = Activity::where('user_id', $userProfile['user_id'])
    //                                         ->orWhere('sender_id', $userProfile['user_id'])
    //                                         ->get();
    //             foreach ($userActivities as $userActivity) {
    //                 $userActivity->delete();
    //             }

    //             $userTrackDemos = TrackDemo::where('user_id', $userProfile['user_id'])->get();
    //             foreach ($userTrackDemos as $userTrackDemo) {
    //                 $userTrackDemo->delete();
    //             }

    //             $userDonations = Donation::where('user_id', $userProfile['user_id'])->get();
    //             foreach ($userDonations as $userDonation) {
    //                 $userDonation->delete();
    //             }

    //             $userFavourites = Favourite::where('user_id', $userProfile['user_id'])->get();
    //             foreach ($userFavourites as $userFavourite) {
    //                 $userFavourite->delete();
    //             }

    //             $userFavouriteVideos = FavouriteVideo::where('user_id', $userProfile['user_id'])->get();
    //             foreach ($userFavouriteVideos as $userFavouriteVideo) {
    //                 $userFavouriteVideo->delete();
    //             }

    //             $userFollowers = Follower::where('user_id', $userProfile['user_id'])->get();
    //             foreach ($userFollowers as $userFollower) {
    //                 $userFollower->delete();
    //             }

    //             $userPlaylists = Playlist::where('user_id', $userProfile['user_id'])->get();
    //             foreach ($userPlaylists as $userPlaylist) {
    //                 $userPlaylist->delete();
    //             }
    //         }
    //     });
    // }
}
