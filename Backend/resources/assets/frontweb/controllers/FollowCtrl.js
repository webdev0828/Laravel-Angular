angular.module('app.controllers')
.controller('FollowCtrl', function($scope, $rootScope, $http, $state , FollowApi,SharedData) {

	$scope.page = 0;
    $scope.lastpage = 1;
    $scope.loading = false;
    $scope.limit = 10 ;

	$scope.getFollowing = function(){	
		$scope.loading = true;
		var slug = $state.params.slug;
		FollowApi.getfollowing(null, {'slug':slug}, function(r) { 
			$scope.followingUser = r.data.followingUser;
			$scope.page = r.data.current_page;
            $scope.lastpage =r.data.last_page;
            $scope.loading = false;
		},function(error){
			$scope.loading = false;
		});
	}

	$scope.loadMoreFollowing = function (){
        
        var slug = $state.params.slug;
        if(!$scope.loading && ($scope.page !=  $scope.lastpage) && ($scope.page <=  $scope.lastpage)){
            $scope.loading = true;
            FollowApi.getMoreFollowing({page: $scope.page+1 , limit : $scope.limit,'slug':slug}, null, function(r) {
                for(i in r.data.followingUser) {
                    var v = r.data.followingUser[i];
                    $scope.followingUser.push(v);
                }
                $scope.page = r.data.current_page;
                $scope.lastpage =r.data.last_page;
                $scope.loading = false;
            }, function(error) {
               $scope.loading = false;
            }); 
        }
    }



    $scope.getFans = function(){
		$scope.loading = true;
		var slug = $state.params.slug;
		FollowApi.getFans(null, {'slug':slug}, function(r) {
			$rootScope.fanUser = r.data.fanUser;
			$scope.page = r.data.current_page;
            $scope.lastpage =r.data.last_page;
            $scope.loading = false;
        }, function(error) {
           $scope.loading = false;
        });
	}

	$scope.loadMoreFans = function (){
        
        var slug = $state.params.slug;
        if(!$scope.loading && ($scope.page !=  $scope.lastpage) && ($scope.page <=  $scope.lastpage)){
            $scope.loading = true;
            FollowApi.getMoreFans({page: $scope.page+1 , limit : $scope.limit,'slug':slug}, null, function(r) {
                for(i in r.data.fanUser) {
                    var v = r.data.fanUser[i];
                    $scope.fanUser.push(v);
                }
                $scope.page = r.data.current_page;
                $scope.lastpage =r.data.last_page;
                $scope.loading = false;
            }, function(error) {
               $scope.loading = false;
            }); 
        }
    }


    $rootScope.FollowArtist = function(slug,value){ 
		if(!slug){
			var slug = $state.params.slug;
		}
		$rootScope.showLoading = true;
		FollowApi.post(null,{'slug':slug}, function(r) {
			$rootScope.showLoading = false;
			var artist_slug = $state.params.slug;
			$rootScope._followingTo.push(r.data.follower_id);
			if(artist_slug && artist_slug != $rootScope.user.slug && artist_slug == slug){
				$rootScope.appScope.fanCount++;
				if($state.current.name == "app.artist.fans"){
					$scope.getFans();
				}
			}

			if(artist_slug && artist_slug == $rootScope.user.slug){
				$rootScope.appScope.followingCount += 1;
			}
			$rootScope.getActivity();
			$rootScope.getFollowing();
			// toastr.success('You followed '+ r.data.name +' successfully.');
		}, function(r){
			$rootScope.showLoading = false;
			toastr.error(r.data.message);
		});
	}

	$scope.UnfollowArtist = function(slug,value){ 
		if(!slug){
			var slug = $state.params.slug;
		}

		FollowApi.unfollow(null,{'slug':slug}, function(r) {

			$rootScope._followingTo.splice($rootScope._followingTo.indexOf(r.data.id),1);
			var artist_slug = $state.params.slug;
			if(value == 'UnFollow' && $scope.followingUser && $scope.followingUser.length){
				var index = -1;
        		for(var i=0; i<$scope.followingUser.length; i++){
        			if($scope.followingUser[i].slug === slug){
						index = i;
						break;
					}
        		}

        		if(artist_slug && artist_slug == $rootScope.user.slug){
					$scope.followingUser.splice(index, 1);
				}
			}

			
			if(artist_slug && artist_slug != $rootScope.user.slug && artist_slug == slug){
				$rootScope.appScope.fanCount -= 1;
				if($state.current.name == "app.artist.fans"){
					$scope.getFans();
				}	
			}

			if(artist_slug && artist_slug == $rootScope.user.slug){
				$rootScope.appScope.followingCount -= 1;
			}
			$rootScope.getActivity();
			$rootScope.getFollowing();

			// toastr.success('You unfollowed '+ r.data.name +' successfully.');
		}, function(r){
			toastr.error(r.data.message);
		});
	}

});