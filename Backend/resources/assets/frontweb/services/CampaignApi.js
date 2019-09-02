angular.module('app.services')
// .factory('TrackService', function($resource) { 
// 	return $resource('frontapi/track/:id', {}, {
// 	  'track' : { method : 'get', isArray : true }
// 	}); 
//  })

.factory('CampaignApi', function($resource) { 
	return $resource('frontapi/campaigns/:type/:slug', {}, {
		'get' : {method:'GET',url:'frontapi/campaigns/:slug'},
		'getMoreTracks' : { method : 'POST', url:'frontapi/get-more-campaigns/:slug'},
	  	'post' : { method : 'POST', isArray : false},
	  	'getEverythingPageData' : {method:'GET',url:'frontapi/everything/data/:slug'},
	  	'getMoreEverythingPageData' : {method:'GET',url:'frontapi/get-more-everything/:slug'},
	});
});
