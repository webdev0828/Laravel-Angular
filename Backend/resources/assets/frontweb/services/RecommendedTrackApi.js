angular.module('app.services')

.factory('RecommendedTrackApi', function($resource) {
	return $resource('frontapi/recommended-tracks/:type/:id', {}, {
	  	
	});
});