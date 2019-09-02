angular.module('app.services')
// .factory('TrackService', function($resource) { 
// 	return $resource('frontapi/track/:id', {}, {
// 	  'track' : { method : 'get', isArray : true }
// 	}); 
//  })

.factory('TrackApi', function($resource) { 
	return $resource('frontapi/tracks/:type/:slug', {}, {
		'get' : { method : 'GET', isArray : false},
		'getMoreTracks' : { method : 'POST', url:'frontapi/get-more-tracks/:type/:slug' ,isArray : false},
	  	'post' : { method : 'POST', isArray : false},
	  	'getSingleTrack': {method:'GET',url:'frontapi/single-track/:type/:id'},
	  	// 'getEverythingPageData' : {method:'GET',url:'frontapi/everything/data/:slug'},
	  	// 'getMoreEverythingPageData' : {method:'GET',url:'frontapi/get-more-everything/:slug'},
	  	'followersTracks':    {method:'POST', isArray : false, url:'frontapi/followers-tracks'},
	  	'getMoreFollowersTracks' : {method:'POST',url:'frontapi/get-more-followers-tracks',isArray : false},
	  	'shares' : {method:'POST',url:'frontapi/track-shares',isArray : false},
	  	'download' : {method:'GET',url:'/download/:slug',},
	  	'sendDownloadLink' : {method:'GET',url:'/download-track-mail',},
	  	// 'getVideoTracks': {method:'GET',url:'frontapi/video-tracks/:slug'},
	});
});
