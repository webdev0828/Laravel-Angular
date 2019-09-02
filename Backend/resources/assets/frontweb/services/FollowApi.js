angular.module('app.services')

.factory('FollowApi', ['$resource',function($resource, API_URL) {
	return $resource('frontapi/follow-artist', null, {
	  'post':    {method:'POST'},
	  'unfollow':    {method:'POST',url:'frontapi/unfollow-artist'},
	  'getfollowing': {method:'POST', url:'frontapi/get-following'},
	  'getMoreFollowing': {method:'POST', url:'frontapi/get-more-following'},
	  'getFans': {method:'POST', url:'frontapi/get-fans'},
	  'getMoreFans': {method:'POST', url:'frontapi/get-more-fans'},
	});
}]);
