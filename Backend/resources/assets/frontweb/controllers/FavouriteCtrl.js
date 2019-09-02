angular.module('app.controllers')
.controller('FavouriteCtrl', function($scope, $state, $rootScope, $http, FavouriteApi, SharedData, PlayerService) {

	$scope.pageTracks = 0;
	$scope.lastpageTracks = 1;

	$scope.loading = false;
	$scope.limit = 9;

    var slug = $state.params.slug;

	$rootScope.getFavourites = function() {
		$scope.loading = true;

		FavouriteApi.get({slug:slug}, function(r) {
	    	$scope.favouriteTracks = r.favouriteTracks.data;

	    	PlayerService.tracks = $scope.favouriteTracks;
			$scope.pageTracks = r.favouriteTracks.current_page;
			$scope.lastpageTracks =r.favouriteTracks.last_page;

			$scope.loading = false;
		},function(error){
			$scope.loading = false;
		});
    };

    // $scope.changeTrack = function(type) {
    // 	if(type == "remix"){
    // 		PlayerService.tracks = $scope.favouriteRemixTracks;
    // 	}
    // 	if(type == "campaign"){
    // 		PlayerService.tracks = $scope.favouriteCampaigns;
    // 	}
    // };

    $scope.loadMoreFavouritesTracks = function (){

		$page = $scope.pageTracks;

		if(!$scope.loading && $page < $scope.lastpageTracks){
			$scope.loading = true;

			FavouriteApi.getMoreFavouritesTracks({page: $page+1 , limit : $scope.limit, slug: slug}, null, function(r) {

				for(i in r.favouriteTracks.data) {
	                var x = r.favouriteTracks.data[i];
	                $scope.favouriteTracks.push(x);
	            }

				$scope.pageTracks = r.favouriteTracks.current_page;
				$scope.lastpageTracks =r.favouriteTracks.last_page;

				$scope.loading = false;
			}, function() {
				$scope.loading = false;
			});
		}
	}


    $scope.deletefavouriteTrack = function(track, index, type) {

		FavouriteApi.deleteTrack({id : track.favourite_id, type: type}, function(data) {

			$rootScope.appScope.favouriteCount -= 1;
			$rootScope.appScope.likeCount -= 1;
			$scope.favouriteTracks.splice(index, 1);

			if(type == "track"){
				index2 = SharedData._favTrackIds.indexOf(track.id)
        		SharedData._favTrackIds.splice(index2, 1);
			}else if(type == "campaign"){
				index2 = SharedData._favCampaignIds.indexOf(track.id)
        		SharedData._favCampaignIds.splice(index2, 1);
			}else{
				index2 = SharedData._favRemixIds.indexOf(track.id)
        		SharedData._favRemixIds.splice(index2, 1);
			}
    		// toastr.success('',data.success);
		});
	};


	// $scope.deletefavouriteVideo = function(video, index) {

	// 	FavouriteApi.deleteTrack({id : video.favourite_id, type: 'video'}, function(data) {

	// 		$scope.$parent.favouriteCount-= 1;
	// 		$scope.$parent.likeCount -= 1;
 //    		$scope.favouriteVideos.splice(index, 1);

 //    		index2 = SharedData._favVideoIds.indexOf(video.id)
 //        	SharedData._favVideoIds.splice(index2, 1);

 //    		toastr.success('',data.success);
	// 	});
	// };

});