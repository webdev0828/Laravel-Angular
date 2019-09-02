angular.module('app.services')

.factory('DashboardApi', ['$resource',function($resource) {
	return $resource('frontapi/dashboard/:id', {}, {
		'getCampaign' : { url:'frontapi/next-campaigns', method : 'GET'},
		'getRemixCampaign' : { url:'frontapi/remix-campaigns', method : 'GET'},
		'getTrackDemo' : { url:'frontapi/get-demotrack', method : 'GET'},
		'getStreamline' : { url:'frontapi/next-streamlines', method : 'GET'},
		'post' : { method : 'POST', transformRequest: angular.identity, headers: {'Content-Type': undefined}},
		'deleteCampaign'	: { method : 'DELETE'},
		'deleteStreamline' : {url: 'frontapi/destroy-streamline', method: 'POST'},
		'getScTracks' : { method : 'GET' , url:'/sc/artist-tracks'},
		'storeStreamline' : { url:'frontapi/store-streamline', method : 'POST', transformRequest: angular.identity, headers: {'Content-Type': undefined}},
	});
}]);