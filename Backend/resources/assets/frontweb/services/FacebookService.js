angular.module('app.services')
.service("FacebookService", function (SharedData,$q,PlayHistoryApi, $rootScope, $timeout, $http) {

	this.isConnected = false;
	this.isInitialized = false;


 	this.initialize = function(app_id) {
 		FB.init({
	      appId: app_id,
	      status: true,
	      cookie: true,
	      xfbml: true,
	      version: 'v2.10'
	    });
		this.isInitialized = true;
 	}

 	this.like = function(postData) {
 		return $http.post('fb/like', postData);
 	}

 	this.share = function(link,cover_image,track_name) {

 	}

});

// angular.module('app.services')

// .factory('FacebookService', function($resource) {
// 	return $resource('/fb', null, {
// 	  	'like' : { method : 'post', url:'fb/like'},
// 	  	'share' : { method : 'post',url:'fb/share'}
// 	});
// });