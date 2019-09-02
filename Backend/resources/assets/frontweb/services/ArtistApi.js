angular.module('app.services')

// .factory('ArtistApi', ['$resource',function($resource) {
	
// 	return $resource('frontapi/billing', {}, {
// 	  'plans':    {method:'GET'}
// 	});
// }])

.factory('ArtistApi', ['$resource',function($resource) {
	
	return $resource('frontapi/', null, {
		'plans'				:   {method:'GET',url:'frontapi/billing'},
		'getCampaign'		: 	{method:'GET',url:'frontapi/get-campaign'},
		'getChartData'		: 	{method:'GET',url:'frontapi/get-chart-data'},
		'payment'			:   {method:'GET',url:'frontapi/payment-details'},
	    'updateImage'		:   {
	    							method:'POST',
	  								url:'frontapi/artist/profile-image-update',
	  								headers: {'Content-Type': undefined}
	  							},
	  	'bannerUpdate'		:   {
	  								method:'POST',
	  								url:'frontapi/artist/banner-image-update',
	  								headers: {'Content-Type': undefined}
	  							},
	  	'bannerSelect'		:	{
							  		method:'POST',
									url:'frontapi/artist/banner-image-select'
								},
	    'updateArtist'		:   {
	    							method:'POST',
	  								url:'frontapi/artist/update',
	  								headers: {'Content-Type': undefined}
	  							},
	  	'getProfile'		:   {method:'GET',url:'frontapi/artist/profile/:slug'},
	    'bioUpdate'			:   {method:'POST',url:'frontapi/artist/bio-update'},
	    'makeDonation'		:   {method:'POST',url:'frontapi/artist/donation'},
	    // 'userActivities':    {method:'GET',url:'frontapi/activities/:slug'},
	    'submitDemos'		:   {method:'POST',url:'frontapi/submit-demos'},
	    'submitTrackDemos'	:   {method:'POST',url:'frontapi/submit-track-demos'},
	    'getDemoTracks'		:   {method:'GET',url:'frontapi/demo-tracks'},
	    'submitRepost'		:   {method:'POST',url:'frontapi/submit-repost'},
	    'getMoreActivities'	:   {method:'GET',url:'frontapi/get-more-activities'},
	    'delete'			:   {method:'post',url:'frontapi/artist/delete'},
	    'changePassword'	:   {method:'post',url:'frontapi/change-password'},
	    'checkPass'			:   {method:'post',url:'frontapi/check-password'},
	    'updateEmail'		:   {method:'post',url:'frontapi/update-email'},
	    'saveFbPage' 		: 	{method:'post',url:'frontapi/artist/save-fb-page',isArray : true},
	    'saveYoutubeChannel': 	{method:'post',url:'frontapi/artist/save-youtube-channel',isArray : true},
	    'removeNotification':   {method:'post',url:'frontapi/artist/remove-notification'},
	    'getNotification'   :   {method:'get',url:'frontapi/artist/get-notification'},
	    'removeNotificationsCount'   :   {method:'get',url:'frontapi/artist/remove-notification-count'},
	    'getActivities'     :   {method:'get',url:'frontapi/artist/get-activities'},
	    'getFollowings'     :   {method:'get',url:'frontapi/artist/get-followings'},
	    'changePermission'  :   {method:'post',url:'frontapi/artist/change-permission'},
	    'welcomePopup'   	:   {method:'get',url:'frontapi/artist/welcome-popup'},

	});
}])

