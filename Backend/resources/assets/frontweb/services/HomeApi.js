angular.module('app.services')

.factory('HomeApi', ['$resource',function($resource, API_URL) {
	return $resource('frontapi', null, {
	  'HomePageData'	: { method : 'get', url:'frontapi/home-page-data'},
	  'contact'	: { method : 'post', url:'frontapi/contactUs/send-mail'},
	});
}]);
