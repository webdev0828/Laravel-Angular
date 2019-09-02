angular.module('app.services')

.factory('SoundCloudApi', function($resource, $window) {
	return $resource('frontapi/sc/artist-tracks', {}, {
		'getRemix' :{ method : 'GET', url:'frontapi/sc/artist-remix-tracks'},
		'getSCUsers' : {method : 'GET', url:'https://api.soundcloud.com/users.json?client_id='+$window.globalObj.sc_key, isArray : true},
		'getScTracks' :{ method : 'GET', url:'frontapi/sc-tracks'},
		'getTrackFromURL': {method: 'GET', url: 'frontapi/sc/track-by-url'}
	});
});