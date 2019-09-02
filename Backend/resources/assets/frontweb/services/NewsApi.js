angular.module('app.services')

.factory('NewsApi', ['$resource',function($resource, API_URL) {
	return $resource('frontapi/news/:id', { id: '@id'}, {
	  'get':    {method:'GET'},
	  'getMoreNews' : {method:'GET',url:'frontapi/get-more-news',isArray : false},
	});
}]);
