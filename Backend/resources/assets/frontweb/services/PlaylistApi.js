angular.module('app.services')

.factory('PlaylistApi', ['$resource',function($resource, API_URL) {
	return $resource('frontapi/playlist/:id', {}, {
		'post' 			: { method : 'POST'},
		'deleteList'	: { method : 'DELETE'},
		'updateList'	: { method : 'POST', url:'frontapi/playlist/update'},
	  	'deleteTrack' 	: { method : 'DELETE', url:'frontapi/playlist/track/:id'},
	  	'addTrack'		: { method : 'POST'},
	  	'getMorePlaylistTracks' :{ method : 'GET', url:'frontapi/get-more-playlist-tracks'},
	  	'getMorePlaylistCampaigns' :{ method : 'GET', url:'frontapi/get-more-playlist-campaigns'},
	  	'getMorePlaylistRemix' :{ method : 'GET', url:'frontapi/get-more-playlist-remix'},
		'getMorePlaylistVideos' :{ method : 'GET', url:'frontapi/get-more-playlist-videos'},
	});
}]);
