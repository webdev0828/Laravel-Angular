angular.module('app.controllers')
.controller('EverythingCtrl', function($scope, $http, API_URL, SharedData, CampaignApi, $state, PlayerService) {

	var slug = $state.params.slug;

	$scope.page = 0;
	$scope.lastpage = 1;
	$scope.loading = false;
	$scope.limit = 9 ;
	

	// console.log(SharedData);
	// $scope.playlist = SharedData._playlists;
	$scope.favouritesTrack = SharedData._favTrackIds;
	// $scope.tracks = CampaignApi.get({'slug' : $userSlug});
	
	$scope.getTracks= function() {
		$scope.loading = true;
		CampaignApi.getEverythingPageData({'slug' : slug, limit:$scope.limit}, function(r){
			$scope.tracks = r.data.tracks;
			$scope.topVideos = r.videos;
			PlayerService.tracks = $scope.tracks;

			$scope.loading = false;
			$scope.page = r.data.current_page;
			$scope.lastpage =r.data.last_page;
		}, function(error) {
           $scope.loading = false;
        });
	}

	$scope.loadMoreEverythingTracks = function (){

		if(!$scope.loading && ($scope.page !=  $scope.lastpage) && ($scope.page <=  $scope.lastpage)){
			$scope.loading = true;
			CampaignApi.getMoreEverythingPageData({slug: slug ,page: $scope.page+1 , limit : $scope.limit}, null, function(r) {
				for(i in r.data.tracks) {
	                var v = r.data.tracks[i];
	                $scope.tracks.push(v);
	            }
				$scope.page = r.data.current_page;
				$scope.lastpage =r.data.last_page;
				$scope.loading = false;
			}, function(error) {
               $scope.loading = false;
            });	
		}
	}

	// $scope.$watch('currentPage', function(){
 //        if( $scope.currentPage ) {
 //            $scope.getTracks();
 //        }
 //       //  $('html, body').animate({
 //       //    	scrollTop: 500
 //      	// }, 500);
 //    });

});