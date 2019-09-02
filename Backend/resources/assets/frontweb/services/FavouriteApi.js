angular.module('app.services')

.factory('FavouriteApi', ['$resource',function($resource, API_URL) {
	return $resource('frontapi/favourite/:id', {}, {
		'addfavourite'	: { method : 'POST'},
		'addvideofavourite'	: { method : 'POST', url:'frontapi/video-favourite'},
		'deleteTrack'	: { method : 'POST', url:'frontapi/delete-favourite'},
		'getMoreFavouritesTracks' :{ method : 'GET', url:'frontapi/get-more-favourites-tracks'},
		'getMoreFavouritesCampaigns' :{ method : 'GET', url:'frontapi/get-more-favourites-campaigns'},
		'getMoreFavouritesVideos' :{ method : 'GET', url:'frontapi/get-more-favourites-videos'},
		'getMoreFavouritesRemix' :{ method : 'GET', url:'frontapi/get-more-favourites-remix'},
	});
}]);
