<?php namespace App\Http\Controllers;

class WelcomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('welcome');
	}

	public function truncate() {
/*
        \DB::statement('set foreign_key_checks = false');
        \DB::statement('TRUNCATE `activities`');
        \DB::statement('TRUNCATE `admin_users`');
        \DB::statement('TRUNCATE `artists_profile`');
        \DB::statement('TRUNCATE `artist_genres`');
        \DB::statement('TRUNCATE `campaigns`');
        \DB::statement('TRUNCATE `campaign_genres`');
        \DB::statement('TRUNCATE `campaign_moods`');
        \DB::statement('TRUNCATE `categories`');
        \DB::statement('TRUNCATE `competitions`');
        \DB::statement('TRUNCATE `competition_artists`');
        \DB::statement('TRUNCATE `competition_genres`');
        \DB::statement('TRUNCATE `competition_track_genres`');
        \DB::statement('TRUNCATE `competition_winners`');
        \DB::statement('TRUNCATE `donations`');
        // \DB::statement('TRUNCATE `faqs`');
        \DB::statement('TRUNCATE `favourites`');
        \DB::statement('TRUNCATE `favourite_videos`');
        \DB::statement('TRUNCATE `files`');
        \DB::statement('TRUNCATE `followers`');
        // \DB::statement('TRUNCATE `genres`');
        \DB::statement('TRUNCATE `label_release`');
        \DB::statement('TRUNCATE `likes`');
        // \DB::statement('TRUNCATE `moods`');
        // \DB::statement('TRUNCATE `news`');
        // \DB::statement('TRUNCATE `news_categories`');
        \DB::statement('TRUNCATE `news_comment`');
        \DB::statement('TRUNCATE `oauth_identities`');
        \DB::statement('TRUNCATE `password_resets`');
        \DB::statement('TRUNCATE `plans`');
        \DB::statement('TRUNCATE `plans_billing`');
        \DB::statement('TRUNCATE `playlists`');
        \DB::statement('TRUNCATE `playlist_tracks`');
        \DB::statement('TRUNCATE `sc_artist`');
        \DB::statement('TRUNCATE `sc_track_genres`');
        \DB::statement('TRUNCATE `stm_releases_genres`');
        \DB::statement('TRUNCATE `stm_releases_moods`');
        \DB::statement('TRUNCATE `stm_video_releases`');
        \DB::statement('TRUNCATE `top_items`');
        \DB::statement('TRUNCATE `track_demos`');
        \DB::statement('TRUNCATE `track_demo_genres`');
        \DB::statement('TRUNCATE `track_demo_moods`');
        \DB::statement('TRUNCATE `track_shares`');
        \DB::statement('TRUNCATE `users`');
        \DB::statement('TRUNCATE `user_genres`');
        \DB::statement('TRUNCATE `user_profiles`');
        \DB::statement('TRUNCATE `play_history`');
        \DB::statement('TRUNCATE `play_history_details`');
        \DB::statement('set foreign_key_checks = true');
        
        

// TRUNCATE `activities`;
// TRUNCATE `admin_users`;
// TRUNCATE `artists_profile`;
// TRUNCATE `artist_genres`;
// TRUNCATE `campaigns`;
// TRUNCATE `campaign_genres`;
// TRUNCATE `campaign_moods`;
// TRUNCATE `categories`;
// TRUNCATE `competitions`;
// TRUNCATE `competition_artists`;
// TRUNCATE `competition_genres`;
// TRUNCATE `competition_track_genres`;
// TRUNCATE `competition_winners`;
// TRUNCATE `donations`;
// TRUNCATE `faqs`;
// TRUNCATE `favourites`;
// TRUNCATE `favourite_videos`;
// TRUNCATE `files`;
// TRUNCATE `followers`;
// TRUNCATE `genres`;
// TRUNCATE `label_release`;
// TRUNCATE `likes`;
// TRUNCATE `moods`;
// TRUNCATE `news`;
// TRUNCATE `news_categories`;
// TRUNCATE `news_comment`;
// TRUNCATE `oauth_identities`;
// TRUNCATE `password_resets`;
// TRUNCATE `plans`;
// TRUNCATE `plans_billing`;
// TRUNCATE `playlists`;
// TRUNCATE `playlist_tracks`;
// TRUNCATE `play_history`;
// TRUNCATE `play_history_details`;
// TRUNCATE `sc_artist`;
// TRUNCATE `sc_track_genres`;
// TRUNCATE `stm_releases_genres`;
// TRUNCATE `stm_releases_moods`;
// TRUNCATE `stm_video_releases`;
// TRUNCATE `top_items`;
// TRUNCATE `track_demos`;
// TRUNCATE `track_demo_genres`;
// TRUNCATE `track_demo_moods`;
// TRUNCATE `track_shares`;
// TRUNCATE `users`;
// TRUNCATE `user_genres`;
// TRUNCATE `user_profiles`;

		//  // user seeder
		$user = \App\User::firstOrCreate([
                                        'name' => 'Admin',
                                        'email'    => 'admin@stm.com',
                                        'user_type'    => 'admin',
                                        'password' => \Hash::make('12345'),
                                        'status' => '1',
                                        'slug'=>'admin'

                                    ]);

        $adminUserProfile = \App\AdminUser::firstOrCreate([
                                                        'user_id'   => $user->id, 
                                                        'fname'     => 'Admin',
                                                        'lname'     => '',
                                                        'name'      => 'Admin',
                                                    ]);

        $artist = \App\User::firstOrCreate([
                                        'name' => 'Stm Artist',
                                        'email'    => 'artist@stm.com',
                                        'user_type'    => 'artist',
                                        'password' => \Hash::make('12345'),
                                        'status' => '1',
                                        'isStmArtist' => '1',
                                        'slug'=>'stm-artist'

                                    ]);

        $artistUserProfile = \App\ArtistsProfile::firstOrCreate([
                                                        'user_id'   => $artist->id, 
                                                        'first_name'     => 'Stm Artist',
                                                        'last_name'     => '',
                                                        'name'      => 'Stm Artist',
                                                    ]);


  //       // plan seeder
        \App\Plans::firstOrCreate(array(
            'name' => 'Test The Waters',
            'stripe_plan_key'    => 'free',
            'amount' => 0,
            'discover_demo_limit' => 0
        ));

        \App\Plans::firstOrCreate(array(
            'name' => 'Premium Gating',
            'stripe_plan_key'    => 'premium',
            'amount' => 1.99,
            'discover_demo_limit' => 0
        ));

        \App\Plans::firstOrCreate(array(
            'name' => 'Infinite Gating',
            'stripe_plan_key'    => 'infinite',
            'amount' => 3.99,
            'discover_demo_limit' => 0
        ));

        \App\Plans::firstOrCreate(array(
            'name' => 'Gating & Discover',
            'stripe_plan_key'    => 'gating',
            'amount' => 6.99,
            'discover_demo_limit' => 2
        ));

        \App\Plans::firstOrCreate(array(
            'name' => 'Gating, Discover & Videos',
            'stripe_plan_key'    => 'gating2',
            'amount' => 8.99,
            'discover_demo_limit' => 4
        )); */

        // news category seeder
        /*\App\NewsCategory::firstOrCreate(array(
            'name' => 'Pop',
            'description'    => 'pop track and music related news'
        ));

        \App\NewsCategory::firstOrCreate(array(
            'name' => 'Art song',
            'description'    => 'Art Video news'
        ));

        \App\NewsCategory::firstOrCreate(array(
            'name' => 'Folk music',
            'description'    => 'Folk music news'
        ));

        \App\NewsCategory::firstOrCreate(array(
            'name' => 'Vocal music',
            'description'    => 'Vocal music news'
        ));

        \App\NewsCategory::firstOrCreate(array(
            'name' => 'Theme song',
            'description'    => 'Theme song news'
        ));

        \App\NewsCategory::firstOrCreate(array(
            'name' => 'Blog',
            'description'    => 'Blog News'
        ));*/
	}

}
