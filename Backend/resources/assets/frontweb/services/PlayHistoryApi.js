angular.module('app.services')

.factory('PlayHistoryApi', function($resource) { 
	return $resource('frontapi/play-history/:id/:slug/:type', {}, {
	  	'post' : { method : 'POST'},
	  	'getCount'		: { method : 'POST',url:'frontapi/play-history/getPlayCount'},
	});
});
