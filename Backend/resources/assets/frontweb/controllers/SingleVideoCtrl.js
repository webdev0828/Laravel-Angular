angular.module('app.controllers')
.controller('SingleVideoCtrl', function($scope, $sce,TrackApi , $http , $stateParams,$rootScope) {
	$('body').attr('class','landing-page-track');

	var id = $stateParams.trackId;
	var track = TrackApi.getSingleTrack({'id':id, 'type':'video'}, function(r) {
		$scope.track = r.data;
		if($scope.track.type === "video"){
            $scope.typeTrack = false;
            $scope.typeVideo = true;
            $scope.videoURl = $scope.track.url;
            $rootScope.pagebackground = $scope.track.background_file;
        }
	});
});