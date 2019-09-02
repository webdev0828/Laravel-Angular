angular.module('app.services')

.factory('SpotifyApi', function($resource) {
    return {
        artist: function(token) {
            return $resource('frontapi/sp/artist-tracks', {}, {
                'getSpotifyUsers' : {method : 'GET', url:'https://api.spotify.com/v1/search', isArray : false},
                'followArtist' : {method : 'PUT', url:'https://api.spotify.com/v1/me/following', headers: {'Authorization' : 'Bearer ' + token}, isArray: false},
                'saveAlbum' : {method : 'PUT', url:'https://api.spotify.com/v1/me/albums', headers: {'Authorization' : 'Bearer ' + token}, isArray: false},
                'saveTrack' : {method : 'PUT', url:'https://api.spotify.com/v1/me/tracks', headers: {'Authorization' : 'Bearer ' + token}, isArray: false}
            });
        },
        playlist: function(ownerId, playlistId, token) {
            return $resource('frontapi/sp/playlist', {}, {
                'followPlaylist' : {method : 'PUT', url: 'https://api.spotify.com/v1/users/' + ownerId + '/playlists/' + playlistId + '/followers', headers: {'Authorization' : 'Bearer ' + token}, isArray: false}
            });
        }
    }
});