angular.module('app.services')

// .factory('ArtistApi', ['$resource',function($resource) {
	
// 	return $resource('frontapi/billing', {}, {
// 	  'plans':    {method:'GET'}
// 	});
// }])

.factory('UserProfileApi', ['$resource',function($resource) {
	
	return $resource('frontapi/', null, {
		'get'		 	:   {	method:'GET',
								url:'frontapi/user/profile/:slug'
							},
		'updateImage'	:   {	method:'POST',
	  							url:'frontapi/user/profile-image-update',
	  							headers: {'Content-Type': undefined}
  							},
	  	'bannerUpdate'	:   {	method:'POST',
	  							url:'frontapi/user/banner-image-update',
	  							headers: {'Content-Type': undefined}
  							},
	    'updateArtist'	:   {	method:'POST',
	  							url:'frontapi/user/update',
	  							headers: {'Content-Type': undefined}
  							},
  		'bannerSelect':		{
	  		method:'POST',
			url:'frontapi/user/banner-image-select'},
	  	'bioUpdate'		:   {	method:'POST',
	  							url:'frontapi/user/bio-update'
	  						},
		'delete'		:   {	method:'get',
								url:'frontapi/user/delete'
							},
		'getMoreActivities':    {method:'GET',url:'frontapi/user/get-more-activities'},							
	    // 'makeDonation':    {method:'POST',url:'frontapi/artist/donation'},
	    // 'userActivities':    {method:'GET',url:'frontapi/activities/:slug'},
	    // 'submitDemos':    {method:'POST',url:'frontapi/submit-demos'},
	    // 'getDemoTrack':    {method:'GET',url:'frontapi/demo-tracks'},
	});
}])

