angular.module('app.services')

.factory('FAQApi', function($resource) {
	return $resource('frontapi/faqs', {}, {
	  'get':    {method:'GET'}
	});
});
