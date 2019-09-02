angular.module('app.controllers')
.controller('RemixCtrl', function($scope, CampaignApi, $state, PlayerService) {
	
	$scope.remixTracks =  [];
	var slug = $state.params.slug;

	$scope.page = 0;
	$scope.lastpage = 1;
	$scope.loading = false;
	$scope.limit = 9 ;


    $scope.getTracks= function() {
    	$scope.loading = true;
		CampaignApi.get({type:"remix", slug: slug ,limit : $scope.limit}, null,  function(r) {
			$scope.remixTracks = r.data;
			PlayerService.tracks = $scope.remixTracks;
			$scope.loading = false;
			$scope.page = r.current_page;
			$scope.lastpage =r.last_page;
			
		});
	}
	$scope.loadMoreTracks = function (){
		if(!$scope.loading && ($scope.page !=  $scope.lastpage) && ($scope.page <=  $scope.lastpage)){
			$scope.loading = true;
			CampaignApi.getMoreTracks({type:"remix", slug: slug ,page: $scope.page+1 , limit : $scope.limit}, null, function(r) {
				for(i in r.data) {
	                var v = r.data[i];
	                $scope.remixTracks.push(v);
	            }
				$scope.page = r.current_page;
				$scope.lastpage =r.last_page;
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