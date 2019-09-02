angular.module('app.services')

.factory('TrackListApi', ['$resource',function($resource, API_URL) {
	return $resource('frontapi/tracklist', {}, {
		'post'	: { method : 'POST'},
		'removePlaylistTrack' : { method : 'post', url:'frontapi/tracklist/remove-playlist-track'}
	});
}]);
