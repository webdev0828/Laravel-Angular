angular.module('app.controllers')
.controller('DiscoverTrackCtrl', function($scope, CampaignApi, $state, PlayerService) {
	
	// $scope.discoverTracks =  null;
	var slug = $state.params.slug;

	$scope.page = 0;
	$scope.lastpage = 1;
	$scope.loading = false;
	$scope.limit = 9 ;

	$scope.loadMoreTracks = function (){
		if(!$scope.loading && ($scope.page !=  $scope.lastpage) && ($scope.page <=  $scope.lastpage)){
			$scope.loading = true;
			CampaignApi.getMoreTracks({slug: slug ,page: $scope.page+1 , limit : $scope.limit}, null, function(r) {
				for(i in r.data) {
	                var v = r.data[i];
	                $scope.discoverTracks.push(v);
	            }
				$scope.page = r.current_page;
				$scope.lastpage =r.last_page;
				$scope.loading = false;
			});	
		}
	}
	$scope.getTracks= function() {
		$scope.loading = true;
		CampaignApi.get({slug: slug , limit : $scope.limit}, null, function(r) {
			$scope.loading = false;
			$scope.discoverTracks = r.data;
			PlayerService.tracks = $scope.discoverTracks;
			$scope.page = r.current_page;
			$scope.lastpage =r.last_page;
		});
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