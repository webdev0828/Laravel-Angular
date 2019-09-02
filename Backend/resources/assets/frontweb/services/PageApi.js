angular.module('app.services')
.factory('PageApi', ['$resource',function($resource, API_URL) {
	return $resource('frontapi', {}, {
	  // 'getCompetition':    {method:'GET',url:'frontapi/get-compititions'},
	  'getSpotlightTrack':    {method:'GET',url:'frontapi/get-spotlightTracks'},
	  'followersTracks':    {method:'POST',url:'frontapi/followers-tracks'},
	  'getSpotlightVideo':    {method:'GET',url:'frontapi/get-spotlightVideos'},
	});
}]);
