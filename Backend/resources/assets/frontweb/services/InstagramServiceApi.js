angular.module('app.services')

.factory('InstagramServiceApi', function($resource) { 
	return $resource('/instagram/follow', {}, {
	  	'follow' : { method : 'POST'},
	});
});
