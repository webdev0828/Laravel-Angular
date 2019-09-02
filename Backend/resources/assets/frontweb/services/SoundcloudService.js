angular.module('app.services')

.factory('SoundcloudService', function($resource) { 
    return $resource('/sc/connect', {}, {
        // 'connect' : { method : 'get' ,   url : 'sc/connect'},
        'follow'  : { method : 'get' ,   url : 'sc/follow'},
        'followArtist'  : { method : 'get' ,   url : 'sc/artist/follow'},
        'like'    : { method : 'get' ,   url : 'sc/like'},
        'comment' : { method : 'post' ,  url : 'sc/comment'},
        'repost'  : { method : 'post' ,  url : 'sc/repost'}
    });
});

// angular.module('app.services')
// .service("SoundcloudService", function ($timeout, SharedData,$q,PlayHistoryApi, $rootScope, $timeout) { 

// 	var BASE_URL = "http://localhost:96";
// 	this.isConnected = false;
// 	this.isInitialized = false;
	
//  	this.initialize = function(client_id) {
//  		SC.initialize({
// 		  client_id: '1264dcfba780cf7af728c0cb38690274', 
// 		  redirect_uri: $rootScope.baseUrl+"/gate/process"
// 		});

// 		this.isInitialized = true;
//  	}
	
// 	this.connect= function(){
//         return SC.connect();
//  	};

//  	this.follow = function(artistId) {
//  		return SC.put('/me/followings/'+artistId);
//  	};

//  	this.like = function(trackId) {
//  		return SC.put('/me/favorites/'+trackId);
//  	}

//     this.repost = function(trackId) {
//         return SC.put('/me/track_reposts/'+trackId);
//     }

//  	this.comment = function(trackId, msg) {
//  		return SC.post('/tracks/' + trackId + '/comments', { comment: { body: msg, timestamp: 10 } });
//  	}

// });