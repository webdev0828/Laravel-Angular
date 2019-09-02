<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/
// Route::get('truncate', 'WelcomeController@truncate');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
 header('Access-Control-Allow-Headers: origin, content-type, accept, x-requested-with');

Route::group(['prefix' => 'admin','middleware' => ['web']], function () {
	// Route::get('/', 'FrontController@index');
	// Authentication routes...
	Route::get('/', 'Auth\AuthController@getLogin');
	Route::get('/login', 'Auth\AuthController@getLogin');
	Route::post('/login', 'Auth\AuthController@postLogin');
	Route::get('/logout', 'Auth\AuthController@getLogout');

	Route::get('/forgot-password', 'Auth\PasswordController@getForgotPasswordPage');
	Route::post('/password/email', 'Auth\PasswordController@postForgotPasswordEmail');
	Route::get('/reset-password/{token}', 'Auth\PasswordController@getPasswordResetForm');
	Route::post('/reset-password', 'Auth\PasswordController@postPasswordReset');


	Route::controllers([
	   'password' => 'Auth\PasswordController',
	]);
});

// ------------Admin Users-------------------
Route::group(['middleware'=>['web','auth','admin']], function()
{
	Route::get('/admin/dashboard', 'HomeController@index');
	Route::get('/admin/api/subscribers', 'HomeController@getTodaysSubscribers');

	Route::group(['prefix' => 'admin'], function()
	{
		// Route::get('/dripfeed-start','DripFeedController@startDripFeedJobs');
		// Route::get('/dripfeed-stop','DripFeedController@stopDripFeed');

		Route::get('/dripfeed','DripFeedController@getDripFeedData');
		Route::post('/save-dripfeed','DripFeedController@saveDripFeedData');
		Route::get('/dripfeed-start','DripFeedController@DripFeedStart');
		Route::get('/dripfeed-stop','DripFeedController@DripFeedStop');

		Route::get('/api/navigation/newqueue', 'NavigationController@getNewQueue');

		// ------------Admin Dashboard(home page)------------------
		Route::resource('/users', 'UsersController');  
		Route::get('/api/users', 'UsersController@getUsers');
		Route::get('users/sendmail/{artist_id}', 'UsersController@sendMail');
		Route::post('users/uniqueEmail', 'UsersController@uniqueEmail');
		Route::get('profile/edit', 'UsersController@editProfile');
		Route::post('profile/update', 'UsersController@updateProfile');
		Route::post('sendmailto/user', 'UsersController@sendmailtoUser');
		
		Route::resource('artists', 'ArtistsController');  
		Route::post('api/artists', 'ArtistsController@getUsers');
		Route::get('artists/sendmail/{artist_id}', 'ArtistsController@sendMail');
		Route::get('artists/tracks/{artist_id}', 'ArtistsController@getTracks');
		Route::get('artists/campaigns/{artist_id}', 'ArtistsController@getTrackDemos');
		Route::get('api/campaigns/{artist_id}', 'ArtistsController@getArtistTrackDemos');
		Route::post('sendmailto/artist', 'ArtistsController@sendmailtoArtist');

		Route::get('get-artist', 'ArtistsController@getArtistList');

		Route::resource('adminusers', 'AdminUsersController'); 
		Route::get('/api/adminusers', 'AdminUsersController@getUsers');

		Route::resource('categories', 'CategoriesController');  
		Route::get('/api/categories', 'CategoriesController@getCategories');

		Route::resource('newscategories', 'NewsCategoriesController');  
		Route::get('/api/newscategories', 'NewsCategoriesController@getNewsCategories');

		Route::resource('genres', 'GenresController');
		Route::post('/api/genre-exist', 'GenresController@checkGenreExist');
		Route::post('/api/sub-genre-exist', 'SubGenresController@checkSubGenreExist');
		Route::get('/api/genres', 'GenresController@getGenres');
		// Route::get('/api/sub-genres/{id}', 'GenresController@getSubGenres');

		Route::resource('genres/{id}/sub-genres', 'SubGenresController');
		Route::get('/api/genres/{id}/sub-genres', 'SubGenresController@getSubGenres');

		Route::post('/api/mood-exist', 'MoodsController@checkMoodExist');
		Route::resource('vibes', 'MoodsController');
		Route::get('/api/moods', 'MoodsController@getMoods');

		Route::resource('queue', 'QueueController');
		Route::get('/api/memberstracks', 'QueueController@getMembersTracks');
		Route::get('/api/unregisteredtracks', 'QueueController@getUnregisteredTracks');
		Route::get('/api/prouserstracks', 'QueueController@getProUsersTracks');
		Route::get('/api/bintracks', 'QueueController@getBinTracks');
		Route::get('/api/favouritetracks', 'QueueController@getFavouritesTracks');
		Route::post('/track/like', 'QueueController@trackLike');
		Route::post('/track/favourite', 'QueueController@favouriteTrack');
		Route::get('/queue_counts', 'QueueController@get_queue_counts');
		
		Route::resource('payment', 'PaymentController');
		Route::post('/api/payments', 'PaymentController@getTransactions');
		Route::get('export', 'PaymentController@export');

		Route::resource('news', 'NewsController');
		Route::get('news/{id}/delete','NewsController@deleteNews');
		Route::get('/api/news', 'NewsController@getNews');
		Route::get('news/comments/{id}', ['as' => 'news.comment', 'uses' => 'NewsController@getComment']);
		Route::get('news/comments/delete/{id}', ['as' => 'news.comment.delete', 'uses' => 'NewsController@deleteComment']);
		
		Route::resource('/donation','DonationController');
		Route::get('/api/donation', 'DonationController@getDonation');

		Route::resource('faqs', 'FaqController');
		Route::get('faqs/{id}/delete','FaqController@deleteFaq');
		Route::get('/api/faqs', 'FaqController@getFaq');

		Route::resource('competition', 'CompetitionController');
		Route::get('/api/competition', 'CompetitionController@getCompetitions');
		Route::get('/api/competition/{id}/artist-data/', 'CompetitionController@getArtistData');
		// Route::post('/api/competition/select-winner', 'CompetitionController@selectWinner');
		// Route::get('/api/competition/{id}/winner-data', 'CompetitionController@getWinnerData');
		Route::delete('/api/competition/remove-file', 'CompetitionController@removeFile');
		Route::post('/api/competition/sub_genres', 'CompetitionController@getSubGenres');
		Route::any('competition-finalist/{id}/participates', 'CompetitionController@getParticipates');
		Route::any('competition-finalist/store', 'CompetitionController@saveWinner');

		Route::get('/api/get-stm-artist-tracks', 'CompetitionController@getStmArtistTracks');
		Route::get('/api/get-stm-artist-remix-tracks', 'CompetitionController@getStmArtistRemixTracks');
		Route::get('/api/get-music-videos', 'CompetitionController@getMusicVideos');

		

		Route::resource('discover', 'DiscoverTrackController');
		Route::get('/api/discover', 'DiscoverTrackController@getTracks');
		Route::post('/api/discover/change-state', 'DiscoverTrackController@changeState');
		Route::get('/api/discover/approved-tracks', 'DiscoverTrackController@approvedTracks');
		Route::get('/api/discover/add-top', 'DiscoverTrackController@addTop');
		Route::get('/discover/{id}/details', 'DiscoverTrackController@getTrackDetails');
		Route::get('discover/trackplay/{artist_id}', 'DiscoverTrackController@showPlayer');
		Route::any('/discover-reject-track', 'DiscoverTrackController@rejectTrack');

		Route::resource('repost', 'RepostTrackController');
		Route::get('/api/repost', 'RepostTrackController@getTracks');
		Route::post('/api/repost/change-state', 'RepostTrackController@changeState');
		Route::get('/repost/{id}/details', 'RepostTrackController@getTrackDetails');

		Route::resource('remix', 'RemixTrackController');
		Route::get('/api/remix', 'RemixTrackController@getTracks');
		Route::post('/api/remix/change-state', 'RemixTrackController@changeState');
		Route::get('/api/remix/approved-tracks', 'RemixTrackController@approvedTracks');
		Route::get('/remix/{id}/details', 'RemixTrackController@getTrackDetails');

		Route::resource('quality-control', 'QualityController');
		Route::get('/api/quality-control', 'QualityController@getTracks');
		Route::post('/api/quality-control/change-state', 'QualityController@changeState');
		Route::get('/quality-control/{id}/details', 'QualityController@getTrackDetails');

		Route::resource('music-video', 'MusicVideoController');
		Route::get('/api/music-video', 'MusicVideoController@getMusicVideo');
		Route::post('/api/music-video/change-state', 'MusicVideoController@changeState');
		Route::get('/api/video/approved-videos', 'MusicVideoController@approvedTracks');
		// Route::get('/music-video/{id}/details', 'MusicVideoController@getTrackDetails');
		// Route::get('/api/video/add-top', 'MusicVideoController@addTop');

		Route::resource('remix-competition', 'RemixCompetitionController');
		Route::get('/api/remix-competition', 'RemixCompetitionController@getRemixCompetition');
		Route::get('/get-competition-name', 'RemixCompetitionController@getCompetitionList');
		// Route::post('/api/remix-competition/change-state', 'RemixCompetitionController@changeState');
		/*Route::any('competition-state', 'RemixCompetitionController@changeState');*/
		Route::post('/api/remix-competition/change-state', 'RemixCompetitionController@changeState');


		Route::resource('recommended_tracks', 'RecommendedTracksController');
		Route::get('/api/recommended_tracks', 'RecommendedTracksController@getRecommendedTracks');

		Route::resource('music_video_release', 'VideoReleasesController');
		Route::get('/music_video_release/{id}/delete','VideoReleasesController@delete');
		Route::get('/api/music_video_release', 'VideoReleasesController@getNewReleases');
		Route::post('/api/music_video_release/sub_genres', 'VideoReleasesController@getSubGenres');
		Route::get('/api/music_video_release/add-top', 'VideoReleasesController@addTop');
		Route::get('/api/music_video_release/get-artist-tracks', 'VideoReleasesController@getArtistTracks');

		Route::resource('demo-limit', 'DemoLimitController');
		Route::resource('email-notification', 'AdminSettingsController');
		Route::get('/api/email-notification/change-status', 'AdminSettingsController@changeStatus');

		Route::get('connect-soundcloud','SoundCloudController@connectSoundcloud');
		Route::get('connect/{provider}', 'SoundCloudController@redirectToProvider');
		Route::resource('soundcloud-gating', 'SoundCloudController');
		Route::get('socialite/remove/{provider}', 'SoundCloudController@removeProviderToken');
		Route::get('/api/sc-url', 'SoundCloudController@getScUrl');
		// Route::get('socialite/remove/{provider}', 'FrontWeb\SocialAuthController@removeProviderToken');

		
		Route::resource('discover-tracks', 'AdminCampaignsController');
		// Route::get('discover-tracks', 'AdminCampaignsController@getDiscover');
		Route::get('/api/discover-tracks', 'AdminCampaignsController@getDiscoverTracks');
		Route::get('/track/{id}/details', 'AdminCampaignsController@getTrackDetails');
		Route::get('remix-tracks', 'AdminCampaignsController@getRemix');
		Route::get('/api/remix-tracks', 'AdminCampaignsController@getRemixTracks');
		Route::get('/discover-tracks/remove/{id}', 'AdminCampaignsController@removeDiscoverTrack');
		Route::get('/discover-tracks/add/{id}', 'AdminCampaignsController@addDiscoverTrack');

		Route::resource('video_template', 'VideoTemplateController');
		Route::get('/api/video_template', 'VideoTemplateController@getVideoTemplates');
	});
});

View::composer('*.*', function($view)
{   
    $user = \Auth::user();
    if($user){

    	$DripfeedData = \App\Setting::where('name', 'dripfeed')->first(); 
    	if($DripfeedData){
    		$view->with('dripfeed_status', $DripfeedData->current_status);
    	}else{
    		$view->with('dripfeed_status', '');
    	}
    }
    
});
