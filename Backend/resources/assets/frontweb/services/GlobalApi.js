angular.module('app.services')

.factory('GlobalApi', ['$resource',function($resource) {
	return $resource('frontapi/user/data', null, {
		'getSTMdata':    {method:'GET'}
	});
}]);

