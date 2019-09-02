<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Auth;

use Cviebrock\EloquentSluggable\Sluggable;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Billable;


class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
	use Sluggable;
    use Authenticatable, CanResetPassword;
    use Billable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['name','first_name','last_name','country','city','website','genre', 'email', 'password','phone','status','subscription','user_type','avatar','cover','souncloud_url','facebook_url','twitter_url','youtube_url','instagram_url','slug','notifications','isEmail','isNotification','email_notification','web_notification'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    protected $appends = ['follower_count'];

	
    
    /**
     * Authenticate a user by username and password.
     *
     * @param string $username The username
     * @param string $password Plain text password
     * @return bool|user The user if the password matches the user's stored password, false otherwise.
     */

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    // public function getAvatarAttribute()
    // {
    // 	if($this->attributes['avatar']) {
	   //  	$type = $this->user_type == 'artist' ? 'artist' : 'user';
	   //      $path = 'uploads/'.$type.'/profiles/'.$this->attributes['avatar'] ;
	   //      return $path;
	   //  } else {
	   //  	return '';
	   //  }
    // }

    public function getFollowerCountAttribute(){
        $followerCount = $this->followers()->count();
        return $followerCount;
    }

    public function followers(){
        return $this->hasMany('App\Follower', 'follower_id', 'id');
    }

    public function activities(){
        return $this->hasMany('App\Activity', 'user_id', 'id');
    }

    public function socialActivities(){
        return $this->hasMany('App\TrackShares', 'artist_id', 'id');
    }

    public function authenticate($email, $password)
    {
   
		
         	if(Auth::attempt(["email" =>  $email,'password'	=> $password], true))
        	{
				$userID = Auth::id(); 
				$user = User::find($userID);
				return $user;
			}
			else 
			{	 
				return false;
			} 
    }
    
    public static function validate($user_id = null, $data = null)
	{
		$emailRule = 'required|email|unique:users,email,NULL,id';
		$password = 'required';
		
		if(!empty($user_id))
		{
			if($data['email'] == trim(\Input::get('email')))
			{
				$emailRule = 'required|email';
			}

			$password = '';
		}

		$validator = \Validator::make(
				    array(
				        'email' => trim(\Input::get('email')),
				        'name' => trim(\Input::get('name')),
				        'phone' => \Input::get('phone'),
				        'password' => trim(\Input::get('password')),
				    ),
				    array(
				        'email' => $emailRule,
				        'name' => 'required',
				        'password' => $password,
				        'phone' => 'required'
				    )
			);

		return $validator;
	}

    public static function getCountofUsers($user_type)
	{
		//this month: from 1st of current month to today 
		$currentDate 	= date("Y-m-d H:i:s");
		$currentYear 	= date('Y');
		$month 			= date('m');
		$result1 		= strtotime("{$currentYear}-{$month}-01");
		$monthStartDate =  date('Y-m-d H:i:s', $result1);

		$query = \DB::table('users')->where('user_type', '=', $user_type);
		
		$query->where(function($query) use ($currentDate, $monthStartDate)
		{
			$query->whereBetween('users.created_at', array($monthStartDate, $currentDate));
			
		});

		return $query->count();
	}
	public function tracks()
	{
		return $this->belongsToMany('\App\Campaign', 'favourites', 'user_id', 'track_id');
	}
	public function campaigns()
	{
		return $this->hasMany('App\Campaign', 'user_id', 'id');
	}
    public function authTracks()
    {
        return $this->hasMany('App\Campaign', 'user_id', 'id');
    }
    static function getAllArtists()
	{
		$data = self::where('users.user_type', '=', 'artist')->get();
				
		return $data;
	}
	public function getArtistTracks($id)
	{
		return $this->hasMany('App\Campaign', 'user_id', $id);
	}

	
    public function donations()
    {
        return $this->hasMany('App\Donation', 'user_id', 'id');
    }

    public function adminUser()
    {
        return $this->hasOne('App\AdminUser', 'user_id', 'id');
    }

    public function lableRelease()
    {
        return $this->hasOne('App\ReleaseTracks', 'user_id', 'id');
    }


    public function getResetPasswordCode()
	{

		$this->activation_code = $activationCode = $this->getRandomString();

		$this->save();

		return $activationCode;
	}

    public function getEmailConfirmCode()
    {
        $activationCode = $this->getRandomString();
        return $activationCode;
    }

	public function getRandomString($length = 42)
	{
		// We'll check if the user has OpenSSL installed with PHP. If they do
		// we'll use a better method of getting a random string. Otherwise, we'll
		// fallback to a reasonably reliable method.
		if (function_exists('openssl_random_pseudo_bytes'))
		{
			// We generate twice as many bytes here because we want to ensure we have
			// enough after we base64 encode it to get the length we need because we
			// take out the "/", "+", and "=" characters.
			$bytes = openssl_random_pseudo_bytes($length * 2);

			// We want to stop execution if the key fails because, well, that is bad.
			if ($bytes === false)
			{
				throw new \RuntimeException('Unable to generate random string.');
			}

			return substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $length);
		}

		$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

		return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
	}

	public function userprofile() {
		return $this->hasOne('App\UserProfile');
	}

	public function artistprofile() {
		return $this->hasOne('App\ArtistsProfile');
	}

	public function genres(){
        return $this->belongsToMany('App\Genres', 'user_genres', 'user_id','genre_id');
    }

    public function artistGenres(){
        return $this->belongsToMany('App\Genres', 'artist_genres', 'artist_id','genre_id')
                    ->withPivot('type')->wherePivot('type','artist');
    }

    public function artistLabelGenres(){
        return $this->belongsToMany('App\Genres', 'artist_genres', 'artist_id','genre_id')->withPivot('type')->wherePivot('type','label');
    }

	public static function boot(){
        parent::boot();
        Cashier::useCurrency('gbp', '£');

        User::deleting(function($user){

    		$artistprofile = \App\ArtistsProfile::where('user_id',$user->id)->get();
    		foreach ($artistprofile as $srtist) {
                $srtist->delete();
            }

            $userprofile = \App\UserProfile::where('user_id',$user->id)->get();
    		foreach ($userprofile as $srtist) {
                $srtist->delete();
            }


            $oauthIdentity = \App\OauthIdentity::where('user_id',$user->id)->get();
    		foreach ($oauthIdentity as $oauthIdentities) {
                $oauthIdentities->delete();
            }

        	//  For artist profile 
        	$artistCampaigns = Campaign::where('user_id', $user->id)->get();
            foreach ($artistCampaigns as $artistCampaign) {
                $artistCampaign->delete();
            }

            // $artistTrackDemos = TrackDemo::where('user_id', $user->id)->get();
            // foreach ($artistTrackDemos as $artistTrackDemo) {
            //     $artistTrackDemo->delete();
            // }

            $stmVideoReleases = StmVideoRelease::where('artist_id', $user->id)->get();
            foreach ($stmVideoReleases as $stmVideoRelease) {
                if($stmVideoRelease->artwork_file){
                  \App\libraries\GlobalHelper::deleteFile($stmVideoRelease->artwork_file);
                }
                if($stmVideoRelease->download_link){
                  \App\libraries\GlobalHelper::deleteFile($stmVideoRelease->download_link);
                }
                $stmVideoRelease->delete();
            }

            $artistGenres = ArtistGenres::where('artist_id', $user->id)->get();
            foreach ($artistGenres as $artistGenre) {
                $artistGenre->delete();
            }

            $releaseTracks = ReleaseTracks::where('user_id', $user->id)->get();
            foreach ($releaseTracks as $releaseTrack) {
                $releaseTrack->delete();
            }


            $followers = Follower::where('user_id', $user->id)
                                ->orWhere('follower_id', $user->id)
                                ->get();
            foreach ($followers as $follower) {
                $follower->delete();
            }

            // $activities = Activity::where('user_id', $user->id)
            //                         ->orWhere('sender_id', $user->id)
            //                         ->get();
            // foreach ($activities as $activity) {
            //     $activity->delete();
            // }

            // $playlists = Playlist::where('user_id', $user->id)->get();
            // foreach ($playlists as $playlist) {
            //     $playlist->delete();
            // }

            $donations = Donation::where('artist_id', $user->id)
                                ->orWhere('user_id', $user->id)
                                ->get();
            foreach ($donations as $donation) {
                $donation->delete();
            }

            $soundCloudArtist = SoundcloudArtist::where('artist_id', $user->id)->get();
            foreach ($soundCloudArtist as $scArtist) {
                $scArtist->delete();
            }

            $planBilling = PlanBilling::where('user_id', $user->id)->get();
            foreach ($planBilling as $planBill) {
                $planBill->delete();
            }

            $subscriptions = Subscription::where('user_id', $user->id)->get();
            foreach ($subscriptions as $subscription) {
                $subscription->delete();
            }

            $stripeActions = StripeActions::where('user_id', $user->id)->get();
            foreach ($stripeActions as $stripeAction) {
                $stripeAction->delete();
            }

            // $topItems = TopItems::where('object_id', $user->id)->where('object_type', 'admin_video')->get();
            // foreach ($topItems as $topItem) {
            //     $topItem->delete();
            // }

            $competitionArtists = CompetitionArtist::where('artist_id', $user->id)->get();
            foreach ($competitionArtists as $competitionArtist) {
                $competitionArtist->delete();
            }

            $competitionWinners = CompetitionWinner::where('winner_id', $user->id)->get();
            foreach ($competitionWinners as $competitionWinner) {
                $competitionWinner->delete();
            }

            $playHistories = PlayHistory::where('artist_id', $user->id)->get();
            foreach ($playHistories as $playHistory) {
                $playHistory->delete();
            }


            //  For user
            $userActivities = Activity::where('user_id', $user->id)
                                            ->orWhere('sender_id', $user->id)
                                            ->get();
            foreach ($userActivities as $userActivity) {
                $userActivity->delete();
            }

            $userTrackDemos = TrackDemo::where('user_id', $user->id)->get();
            foreach ($userTrackDemos as $userTrackDemo) {
                $userTrackDemo->delete();
            }

            // $userDonations = Donation::where('user_id', $user->id)->get();
            // foreach ($userDonations as $userDonation) {
            //     $userDonation->delete();
            // }

            $userFavourites = Favourite::where('user_id', $user->id)->get();
            foreach ($userFavourites as $userFavourite) {
                $userFavourite->delete();
            }

            $userFavouriteVideos = FavouriteVideo::where('user_id', $user->id)->get();
            foreach ($userFavouriteVideos as $userFavouriteVideo) {
                $userFavouriteVideo->delete();
            }

            // $userFollowers = Follower::where('user_id', $user->id)->get();
            // foreach ($userFollowers as $userFollower) {
            //     $userFollower->delete();
            // }

            $userPlaylists = Playlist::where('user_id', $user->id)->get();
            foreach ($userPlaylists as $userPlaylist) {
                $userPlaylist->delete();
            }

            $notifications = Notification::where('user_id', $user->id)->get();
            foreach ($notifications as $notification) {
                $notification->delete();
            }
            
        });
    }
}