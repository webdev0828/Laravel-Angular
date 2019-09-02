angular.module('app.controllers')
.controller('VideoTrackCtrl', function($scope, TrackApi, $state , VideoApi) {
	
	// $scope.discoverTracks =  null;
	var slug = $state.params.slug;

	// $scope.currentPage = 1;
 //    $scope.numPerPage = 10;
 //    $scope.maxSize = 10;
 	$scope.page = 0;
	$scope.lastpage = 1;
	$scope.loading = false;
	$scope.limit = 9 ;

	$scope.getVideos= function() {
		$scope.loading = true;
		VideoApi.get({slug: slug,limit : $scope.limit}, null, function(r) {
			$scope.videoTracks = r.data;
			$scope.page = r.current_page;
			$scope.lastpage =r.last_page;
			$scope.loading = false;
		});
	}

	$scope.loadMoreVideos = function (){
		if(!$scope.loading && ($scope.page !=  $scope.lastpage) && ($scope.page <=  $scope.lastpage)){
			$scope.loading = true;
			VideoApi.getMoreVideos({slug: slug ,page: $scope.page+1 , limit : $scope.limit}, null, function(r) {
				for(i in r.data) {
	                var v = r.data[i];
	                $scope.videoTracks.push(v);
	            }
				$scope.page = r.current_page;
				$scope.lastpage =r.last_page;
				$scope.loading = false;
			});	
		}
	}

	// $scope.$watch('currentPage', function(){
 //        if( $scope.currentPage ) {
 //            $scope.getVideos();
 //        }
 //       //  $('html, body').animate({
 //       //    	scrollTop: 500
 //      	// }, 500);
 //    });
	
});