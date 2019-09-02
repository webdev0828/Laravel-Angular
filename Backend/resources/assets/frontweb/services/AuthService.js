angular.module('app.services')

.factory('AuthService', function($resource, $http) {
	return $resource('frontapi/auth', null, {
	  'login'			:    {method:'post', url:'frontapi/auth/login'},
	  'register'		:    {method:'post', url:'frontapi/auth/register'},
	  'forgotPassword'	:    {method:'post', url:'frontapi/auth/forgot-password'},
	  'resetPassword'	:    {method:'post', url:'frontapi/auth/reset-password'},
	  'Fbregister'		:    {method:'post', url:'frontapi/auth/fbregister'},
	  'getUser'			:    {method:'get', url:'frontapi/auth/user'},
	  'checkFbUser'		:    {method:'post', url:'frontapi/auth/check-fb-user'},
	  'checkSCUser'		:    {method:'post', url:'frontapi/auth/check-sc-user'},
	}); 
});
