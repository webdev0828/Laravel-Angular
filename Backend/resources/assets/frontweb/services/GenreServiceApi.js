angular.module('app.services')

.factory('GenreServiceApi', ['$resource',function($resource) {
	
	return $resource('frontapi/genres', {}, {
	  'get':    {method:'GET'}
	});
}])