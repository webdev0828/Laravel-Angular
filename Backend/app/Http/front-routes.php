<?php
Route::get('/add-free-plan', 'FrontWeb\StripeController@addFreePlan');
// Route::post('/update-payment', 'FrontWeb\StripeController@updateMontlyTranscation');
Route::get('/twitter/feed', 'FrontWeb\GatingController@twitterFeed');
Route::get('/download-track', 'FrontWeb\GatingController@downloadSCTrack');
// Route::get('/instagram/load', 'FrontWeb\GatingController@instaLoad');
Route::post('/instagram/follow', 'FrontWeb\GatingController@instaFollow');

Route::get('/fb/player/{type}/{id}', '\FrontWeb\GatingController@fbPlayerEmbed');

Route::post('hook-all', 'WebhookController@handleWebhook');
Route::get('test-all', 'WebhookController@handleInvoicePaymentSucceeded');

Route::group(['middleware' => ['web']], function () {

	// TWITTER GATING
	Route::get('twitter/login/{action}', 'FrontWeb\TwitterController@twitterClientLogin');
	Route::get('twitter/callback/{action}', 'FrontWeb\TwitterController@twitterClientCallback');
	Route::get('twitter/timeline', 'FrontWeb\TwitterController@getTimeline');
	Route::get('twitter/tweet', 'FrontWeb\TwitterController@postTweet');

	// YOUTUBE GATING
	Route::get('youtube/process', 'FrontWeb\GatingController@youtubeSubscribe');

	// FACEBOOK GATING
	Route::get('fb/login', 'FrontWeb\GatingController@fbConnect');
	Route::get('fb/callback', 'FrontWeb\GatingController@fbConnectCallback');
	Route::any('fb/like' , 'FrontWeb\GatingController@fbLike');
	Route::any('fb/share' , 'FrontWeb\GatingController@fbShare');
	Route::get('fb/get' , 'FrontWeb\GatingController@fbGetUser');
	Route::get('fb/unfollow' , 'FrontWeb\GatingController@fbUnfollowUser');

	// SOUNDCLOUD GATING
	Route::get('sc/login', 'FrontWeb\GatingController@scConnect');
	Route::get('sc/callback', 'FrontWeb\GatingController@scConnectCallback');
	Route::get('sc/like' , 'FrontWeb\GatingController@scLike');
	Route::post('sc/comment' , 'FrontWeb\GatingController@scComment');
	Route::get('sc/follow' , 'FrontWeb\GatingController@scFollow');
	Route::post('sc/repost' , 'FrontWeb\GatingController@scRepost');
	Route::get('sc/artist/follow' , 'FrontWeb\GatingController@scArtistFollow');

	// SPOTIFY GATING
	Route::get('sp/login', 'FrontWeb\GatingController@spConnect');
	Route::get('sp/callback', 'FrontWeb\GatingController@spConnectCallback');
	Route::get('sp/follow' , 'FrontWeb\GatingController@spFollow');

	// INSTAGRAM GATING
	Route::get('/instagram/process', 'FrontWeb\GatingController@instagramProcess');

	// SOCIAL GATE ON / OFF
	Route::get('/gating/login/{provider}', 'FrontWeb\SocialAuthController@redirectToSpotifyProvider');
	Route::get('/connect/{provider}', 'FrontWeb\SocialAuthController@redirectToProvider');
	Route::get('/gating/callback/{provider}', 'FrontWeb\SocialAuthController@handleProviderCallback');
	Route::get('/sc/index', 'FrontWeb\SoundCloudController@index');

	// Route::get('/install-plans', 'FrontWeb\StripeController@setupPlans');
	// Route::get('/testemail', 'FrontWeb\GatingController@sendMail');
	// Route::get('/gate', 'FrontWeb\GatingController@index');
	// Route::get('/gate/process', 'FrontWeb\GatingController@scConnectCallback');
	Route::get('/account/invoices/{invoice}', 'FrontWeb\StripeController@downloadInvoice');
	Route::get('/facebook/redirect', 'FrontWeb\SocialAuthController@fbLogin');
    Route::get('/facebook/callback', 'FrontWeb\SocialAuthController@fbLoginCallback');


	Route::get('/', 'FrontWeb\FrontWebController@index');

	Route::get('/{type}/download/{trackid}', 'FrontWeb\FrontWebController@downloadtrack');

	Route::get('/download-track-mail/', 'FrontWeb\FrontWebController@senddownloadtrackEmail');

	Route::get('/{type}/download-track/{trackid}', 'FrontWeb\FrontWebController@downloadtrackEmail');

	Route::get('activate/{activationCode}', ['as' => 'activation-code', 'uses' => 'FrontWeb\AuthController@getActivationCode']);

	// Update email
	Route::get('change-email/{activationCode}','FrontWeb\AuthController@updateEmail');

	Route::group(['prefix'=>'frontapi'], function(){
		Route::post('auth/login', 'FrontWeb\AuthController@login');
		Route::post('auth/register', 'FrontWeb\AuthController@register');
		Route::post('auth/check-fb-user', 'FrontWeb\AuthController@checkFbUser');
		Route::post('auth/check-sc-user', 'FrontWeb\AuthController@checkSCUser');
		Route::post('auth/fbregister', 'FrontWeb\SocialAuthController@fbRegister');
		Route::post('auth/forgot-password', 'FrontWeb\AuthController@forgotPassword');
		Route::post('auth/reset-password', 'FrontWeb\AuthController@resetPassword');
		Route::get('auth/logout', 'FrontWeb\AuthController@logout');

		// Get sc tracks before registration
		Route::get('/sc-tracks', 'FrontWeb\SoundCloudController@GetScTracks');
		// Get artist's soundcloud tracks
		Route::get('/sc/artist-tracks', 'FrontWeb\SoundCloudController@GetScArtistTracks');
		Route::get('/sc/artist-remix-tracks', 'FrontWeb\SoundCloudController@GetScArtistRemixTracks');
		Route::get('/sc/track-by-url', 'FrontWeb\SoundCloudController@getTrackFromUrl');

		Route::get('home-page-data', 'FrontWeb\HomeController@index');

		Route::resource('/news', 'FrontWeb\NewsController', ['only' => ['index', 'show']]);


		Route::get('/faqs', 'FrontWeb\FrontWebController@faq');
		Route::get('single-track/{type}/{trackId}', 'FrontWeb\TrackController@getSingleTrack');

		Route::get('single-streamline/{id}', 'FrontWeb\StreamlineController@getSingleStreamline');

		// Get video tracks
		Route::get('video-tracks/{slug}', 'FrontWeb\TrackController@getVideoTracks');

		//---- user data : shared data to all front pages like genres, moods , etc
		Route::get('user/data', 'FrontWeb\APiController@getUserData');

		//---- Get compitions
		Route::get('get-compititions-page-data', 'FrontWeb\APiController@getCompititions');
		Route::post('get-more-runnerUp', 'FrontWeb\APiController@getMoreRunnerUp');
		Route::post('add-compitition-remix-track', 'FrontWeb\APiController@addRemixTrack');
		Route::post('get-past-compitition', 'FrontWeb\APiController@getPastCompetition');

		//---- Get SpotLight tracks
		Route::get('get-spotlightTracks', 'FrontWeb\TrackController@getSpotlightTracks');


		//---- Get SpotLight video
		Route::get('get-spotlightVideos', 'FrontWeb\TrackController@getSpotlightVideos');

		//---- contact us form
		Route::post('contactUs/send-mail', 'FrontWeb\HomeController@contactUs');

		// Discover tracks & resorces
		Route::any('tracks/{type?}/{slug?}', 'FrontWeb\TrackController@getTracks');

		// Campaign tracks & resorces
		Route::any('campaigns/{slug?}', 'FrontWeb\CampaignController@getTracks');

		// Get more campaigns using Lazy loading
		Route::any('get-more-campaigns/{slug?}', 'FrontWeb\CampaignController@getMoreTracks');

		// Get more tracks using Lazy loading
		Route::any('get-more-tracks/{type?}/{slug?}', 'FrontWeb\TrackController@getMoreTracks');

		// Get more videos using Lazy loading
		Route::any('get-more-videos/{slug?}', 'FrontWeb\VideoController@getMoreVideos');

		//---- Get More EveryThing page data using lazy-loading
		Route::get('get-more-everything/{slug}', 'FrontWeb\CampaignController@getMoreEverythingData');

		//---- Get More EveryThing page data using lazy-loading
		Route::get('get-more-news', 'FrontWeb\NewsController@getMoreNews');

		//---- Save track shares data
		Route::any('track-shares', 'FrontWeb\TrackController@saveTrackShares');

		//---- Save streamline shares data
		Route::any('streamline-shares', 'FrontWeb\StreamlineController@saveStreamlineShares');

		// Route::get('get-favourites', 'FrontWeb\TrackController@getfavouritesIds');
		// - resorces

		// Music videos
		Route::any('videos/{slug?}', 'FrontWeb\VideoController@getVideos');

		// Recomended Tracks
		Route::any('recommended-tracks/{type}/{trackId}', 'FrontWeb\TrackController@getRecommendedTracks');

		// Manage tracks Play-History .
		Route::post('play-history/{id}/{slug}/{type}', 'FrontWeb\PlaylistController@updatePlayHistory');
		Route::post('play-history/getPlayCount', 'FrontWeb\PlaylistController@getPlayCount');


		// Artist Profile
		Route::get('artist/profile/{slug}', 'FrontWeb\ArtistController@getArtistProfile');

		//---- Get chart data

		Route::get('get-chart-data', 'FrontWeb\ArtistController@getChart');
		// ---- Get following
		Route::post('get-following', 'FrontWeb\FollowingController@getFollowing');

		// ---- Get more following
		Route::post('get-more-following', 'FrontWeb\FollowingController@getMoreFollowing');

		// ---- Get fans
		Route::post('get-fans', 'FrontWeb\FollowingController@getFans');

		// ---- Get more fans
		Route::post('get-more-fans', 'FrontWeb\FollowingController@getMoreFans');

		//artist Favourites
		Route::resource('favourite', 'FrontWeb\FavouriteController');

		//---- Get More Favourites using lazy-loading
		Route::get('get-more-favourites-tracks', 'FrontWeb\FavouriteController@getMoreFavouritesTracks');
		Route::get('get-more-favourites-campaigns', 'FrontWeb\FavouriteController@getMoreFavouritesCampaigns');
		Route::get('get-more-favourites-videos', 'FrontWeb\FavouriteController@getMoreFavouritesVideos');
		Route::get('get-more-favourites-remix', 'FrontWeb\FavouriteController@getMoreFavouritesRemix');

		// Get external link for play track
		// Route::post('get-external-link', 'FrontWeb\PlaylistController@getExternalLink');

		Route::group(['middleware'=>['user']], function(){
			Route::get('auth/user', 'FrontWeb\AuthController@getUser');

			Route::get('socialite/remove/{provider}', 'FrontWeb\SocialAuthController@removeProviderToken');

			// Get artists genres
			Route::get('/artist-genres', 'FrontWeb\ArtistController@GetArtistGenres');

			// Get artist's soundcloud tracks
			Route::get('/sc/artist-tracks', 'FrontWeb\SoundCloudController@GetScArtistTracks');

			//---- Get EveryThing page data
			Route::get('everything/data/{slug}', 'FrontWeb\CampaignController@getEverythingData');

			//---- billing and plan from profile
			Route::get('billing', 'FrontWeb\ArtistController@getPlanBillingInfo');

			//---- Get campaigns for submit demo
			Route::get('get-campaign', 'FrontWeb\ArtistController@getCampaigns');

			//---- Get All genres
			Route::get('genres', 'FrontWeb\TrackController@getGenres');

			//---- Get users all activities
			// Route::get('activities/{slug}', 'FrontWeb\ArtistController@getActivities');

			//---- Get followers tracks
			Route::any('followers-tracks', 'FrontWeb\ArtistController@getFollowersTracks');

			//---- Get more followers tracks using lazy-loading
			Route::post('get-more-followers-tracks', 'FrontWeb\ArtistController@getMoreFollowersTracks');

			//---- Follow artist
			Route::post('follow-artist', 'FrontWeb\FollowingController@FollowArtist');

			//---- Unfollow artist
			Route::post('unfollow-artist', 'FrontWeb\FollowingController@UnfollowArtist');

			//---- Submit demos
			Route::post('submit-demos', 'FrontWeb\APiController@submitDemos');

			//---- Submit demos for rejected artists
			Route::post('submit-track-demos', 'FrontWeb\APiController@submitTrackDemos');

			//---- Submit repost
			Route::post('submit-repost', 'FrontWeb\APiController@submitRepost');

			//---- Get Demo tracks
			Route::get('demo-tracks', 'FrontWeb\APiController@getDemoTracks');

			//---- Get More Artist Activities using lazy-loading
			Route::get('get-more-activities', 'FrontWeb\ArtistController@getMoreActivities');


			//---- Get More Playlists using lazy-loading
			Route::get('get-more-playlist-tracks', 'FrontWeb\PlaylistController@getMorePlaylistsTracks');
			Route::get('get-more-playlist-campaigns', 'FrontWeb\PlaylistController@getMorePlaylistsCampaigns');
			Route::get('get-more-playlist-remix', 'FrontWeb\PlaylistController@getMorePlaylistsRemix');
			Route::get('get-more-playlist-videos', 'FrontWeb\PlaylistController@getMorePlaylistsVideos');

			//---- Donation
			Route::post('artist/donation', 'FrontWeb\ArtistController@makeDonation');

			//---- payment details
			// Route::get('payment-details', 'FrontWeb\StripeController@getPaymentDetails');

			//---- Subscription
			Route::any('subscribe-customer', 'FrontWeb\StripeController@subscribeCustomer');

			//---- Update payment details
			Route::any('update-payment-details', 'FrontWeb\StripeController@updateBillingCard');

			// Cancel Subscription
			Route::any('cancel-subscription', 'FrontWeb\StripeController@cancelSubscription');
			// Resume Subscription
			Route::any('resume-subscription', 'FrontWeb\StripeController@resumeSubscription');
			Route::any('undo-subscription', 'FrontWeb\StripeController@undoDowngradeSubscription');
			// Change password
			Route::post('change-password', 'FrontWeb\ArtistController@changePassword');

			// Check password
			Route::post('check-password', 'FrontWeb\ArtistController@checkPassword');

			// Send Update Email link
			Route::post('update-email', 'FrontWeb\ArtistController@sendUpdateEmailLink');


			//---- artist profile
			Route::post('artist/update', 'FrontWeb\ArtistController@updateArtist');
			Route::post('artist/bio-update', 'FrontWeb\ArtistController@updateArtistBio');
			Route::post('artist/profile-image-update', 'FrontWeb\ArtistController@updateArtistProfileImage');
			Route::post('artist/banner-image-update', 'FrontWeb\ArtistController@updateArtistBannerImage');
			Route::post('artist/banner-image-select', 'FrontWeb\ArtistController@selectBannerImage');
			Route::post('artist/delete', 'FrontWeb\ArtistController@destroy');
			Route::post('artist/remove-notification', 'FrontWeb\ArtistController@removeNotification');
			Route::get('artist/get-notification', 'FrontWeb\ArtistController@getNotification');
			Route::get('artist/remove-notification-count', 'FrontWeb\ArtistController@removeNotificationCount');

			// ON/OFF Email notifications
			Route::post('artist/change-permission', 'FrontWeb\ArtistController@changePermission');

			// Welcome popup
			Route::get('artist/welcome-popup', 'FrontWeb\ArtistController@welcomePopup');

			//---- Get Artist Activities after performing some actions to update activity secction instantly
			Route::get('artist/get-activities', 'FrontWeb\ArtistController@getActivities');

			//---- Get Artist followings after following artist
			Route::get('artist/get-followings', 'FrontWeb\ArtistController@getFollowings');

			Route::post('artist/save-fb-page', 'FrontWeb\ArtistController@saveFacebookPage');
			Route::post('artist/save-youtube-channel', 'FrontWeb\ArtistController@saveYoutubeChannel');

			// Route::post('artist/get-data', 'FrontWeb\ArtistController@getData');


			//artist Playlist
			Route::resource('playlist', 'FrontWeb\PlaylistController');
			Route::delete('playlist/track/{id}', 'FrontWeb\PlaylistController@deleteTrack');
			Route::post('playlist/update', 'FrontWeb\PlaylistController@update');


			//TrackList
			Route::resource('tracklist', 'FrontWeb\TrackListController');
			Route::resource('tracklist/remove-playlist-track', 'FrontWeb\TrackListController@removePlaylistTrack');

			Route::post('artist/add-track', 'FrontWeb\PlaylistController@addTrack');

			//artist Favourites
			Route::post('video-favourite', 'FrontWeb\FavouriteController@addFavouriteVideo');
			Route::post('delete-favourite', 'FrontWeb\FavouriteController@deleteFavourite');

			Route::resource('dashboard', 'FrontWeb\DashboardController');
			Route::get('next-campaigns', 'FrontWeb\DashboardController@getCampaigns');
			Route::get('remix-campaigns', 'FrontWeb\DashboardController@getRemixCampaigns');
			Route::get('get-demotrack', 'FrontWeb\DashboardController@getDemoTracks');
			Route::get('next-streamlines', 'FrontWeb\DashboardController@getStreamlines');
			Route::post('store-streamline', 'FrontWeb\DashboardController@storeStreamline');
			Route::post('destroy-streamline', 'FrontWeb\DashboardController@destroyStreamline');

			//---- user profile
			Route::get('user/profile/{slug}', 'FrontWeb\UserController@getProfile');
			Route::post('user/update', 	'FrontWeb\UserController@updateProfile');
			Route::post('user/bio-update', 'FrontWeb\UserController@updateBio');
			Route::post('user/profile-image-update', 'FrontWeb\UserController@updateProfileImage');
			Route::post('user/banner-image-update', 'FrontWeb\UserController@updateBannerImage');
			Route::post('user/banner-image-select', 'FrontWeb\UserController@selectBannerImage');
			Route::get('user/get-more-activities', 'FrontWeb\UserController@getMoreActivities');
			Route::get('user/delete', 'FrontWeb\UserController@destroy');
		});
	});

	Route::get('player/{type}/{id}', 'FrontWeb\TwitterController@twitterPlayerCard');

	Route::get('player-content/{type}/{id}', 'FrontWeb\TwitterController@twitterPlayerEmbed');

	Route::get('track/{slug}',  'FrontWeb\FrontWebController@showTrackWithMeta');
	Route::get('campaign/{slug}',  'FrontWeb\FrontWebController@showCampaignWithMeta');


	Route::get('{undefinedRoute}', 'FrontWeb\FrontWebController@index')
			->where('undefinedRoute', '([A-z\d-\/_.]+)?');
});

