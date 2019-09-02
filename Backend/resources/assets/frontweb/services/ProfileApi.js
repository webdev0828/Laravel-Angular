angular.module('app.services')

// .factory('ArtistApi', ['$resource',function($resource) {
	
// 	return $resource('frontapi/billing', {}, {
// 	  'plans':    {method:'GET'}
// 	});
// }])

.factory('ProfileApi', ['$resource',function($resource) {
	
	return $resource('frontapi/artist-genres', null, {
		
	});
}])

