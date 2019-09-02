angular.module('app.services')

.factory('VideoApi', function($resource) { 
	return $resource('frontapi/videos/:slug', {}, {
		'getMoreVideos' : { method : 'POST', url:'frontapi/get-more-videos/:slug' ,isArray : false},
	  	'post' : { method : 'POST', isArray : false},
	  	'getSpotlightVideo':    {method:'GET',url:'frontapi/get-spotlightVideo'}
	});
});
