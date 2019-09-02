angular.module('app.controllers')
.controller('PlaylistCtrl', function($scope, $rootScope, $http, API_URL, $uibModal, PlaylistApi, SharedData ,PlayerService) {
	$scope.list = {};
	$scope.playlist  ={};
	$scope.error = {};
	$scope.allPlaylist = [];
	$scope.playlistType = "track";
	$scope.isSubmitting = false;
	$scope.limit = 9;
	$scope.loading = false;
	$scope.pageTracks = 0;
	$scope.lastpageTracks = 1;

	// 	 $scope.setPage = function (pageNo) {
	//   $scope.currentPage = pageNo;
	// };

	// $scope.pageChanged = function() {
	//   console.log('Page changed to: ' + $scope.currentPage);
	// };

	// $scope.setItemsPerPage = function(num) {
	//   $scope.itemsPerPage = num;
	//   $scope.currentPage = 1; //reset to first paghe
	// }
	
	$rootScope.getPlaylist = function() {
		$scope.loading = true;
		PlaylistApi.get({limit: $scope.limit },null, function(r) {
        	$scope.playlistTracks = r.playlistTracks.data;
        	$scope.pageTracks = r.playlistTracks.current_page;
			$scope.lastpageTracks =r.playlistTracks.last_page;
			PlayerService.tracks = $scope.playlistTracks;
			$scope.loading = false;
		},function(error){
			$scope.loading = false;
		});
	};

	// $scope.changeTrack = function(type) {
 //    	if(type == "remix"){
 //    		PlayerService.tracks = $scope.playlistRemixTrack;
 //    	}
 //    	if(type == "track"){
 //    		PlayerService.tracks = $scope.playlistTracks;
 //    	}
 //    };

	$scope.loadMorePlaylistTracks = function (){

		$page = $scope.pageTracks;

		
		if(!$scope.loading && ($page < $scope.lastpageTracks || $page < $scope.lastpageRemix)){

			$scope.loading = true;
			PlaylistApi.getMorePlaylistTracks({page: $page+1 , limit : $scope.limit}, null, function(r) {
				for(i in r.playlistTracks.data) {
	                var x = r.playlistTracks.data[i];
	                $scope.playlistTracks.push(x);
	            }

				$scope.pageTracks = r.playlistTracks.current_page;
				$scope.lastpageTracks =r.playlistTracks.last_page;

				$scope.loading = false;
			},function() {
				$scope.loading = false;
			});	
		}
	}


    $scope.addNewPlaylist = function(playlist) {
    	$scope.isSubmitting = true;

    	PlaylistApi.post($scope.playlist, function(data) {
    		$scope.isSubmitting = false;
    		//toastr.success('',data.success);
    		SharedData._playlists.push(data.newplaylist);
    		$('#addNewPlaylist').modal('hide');
		}, function(r){
			$scope.isSubmitting = false;
			if(r.data.playlist_name){
				$scope.error.msg = r.data.playlist_name[0];
			}
		});
	};


	$scope.reset = function(){
    	var addPlaylist=angular.element('#addPlaylist').scope();
    	if(addPlaylist){
    		addPlaylist.playlist.playlist_name='';
    		addPlaylist.addPlaylistForm.$setPristine();
    	}
	}

	
	$scope.deletePlaylistTrack = function(track, type) {
	 	
      	PlaylistApi.deleteTrack({id : track.playlist_track_id}, function(data) {
      		index = $scope.playlistTracks.indexOf(track);
        	$scope.playlistTracks.splice(index, 1);
    		if(type == "track"){
    			index1 = $scope._playlistTrackIds.indexOf(track.id)
                $scope._playlistTrackIds.splice(index1, 1);

    		} else if(type == "campaign"){
    			index1 = $scope._playlistCampaignIds.indexOf(track.id)
                $scope._playlistCampaignIds.splice(index1, 1);

    		} else if(type == "video"){
    			index1 = $scope._playlistVideoIds.indexOf(track.id)
                $scope._playlistVideoIds.splice(index1, 1);

    		} else {
    			index1 = $scope._playlistRemixIds.indexOf(track.id)
                $scope._playlistRemixIds.splice(index1, 1);

    		}
        	// toastr.success('',data.success);
		});
    };


	// $scope.deletePlaylistVideo = function(video) {
	// 	swal({ 
	//             title: "Are you sure, do you want to delete this video from playlist ?",
	//             type: "info",
	//             showCancelButton: true,
	//             closeOnConfirm: true,  
	//             showLoaderOnConfirm: false,
	//         }, function(){
 //        	PlaylistApi.deleteTrack({id : video.playlist_track_id}, function(data) {
	//     		index = $scope.playlistVideos.indexOf(video)
	//         	$scope.playlistVideos.splice(index, 1);
	//         	toastr.success('',data.success);
	// 		});
 //        })
	// };


    $scope.viewPlaylist = function (playlistData) {
    	// console.log(playlistData);
    	

        $uibModal.open({
          	templateUrl: 'playlistEdit-modal.html',
          	controller: function($scope, $uibModalInstance, playlistData, parentScopeData, playlistTracksData){

          		$scope.playlistData = playlistData;
			  	$scope.viewby = 10;
			  	$scope.totalItems = $scope.playlistData.length;
			  	$scope.playlistCurrentPage = 1;
			  	$scope.itemsPerPage = $scope.viewby;
			  	$scope.maxSize = 5; //Number of pager buttons to show


    			$scope.cancel = function () {
    				$uibModalInstance.dismiss('cancel');
    			};

	            $scope.deletePlaylist = function(playlist, index) {
				    swal({ 
                            title: "Are you sure, you want to delete this playlist ?",
                            // type: "info",
                            showCancelButton: true,
                            closeOnConfirm: true,  
                            showLoaderOnConfirm: false,
                        }, function(){
                    	PlaylistApi.deleteList({id : playlist.id}, function(data) {
				    		angular.forEach(parentScopeData,function(value){
				        		if(value.id == playlist.id){
									parentScopeData.splice(index, 1);
									playlistData.splice(index, 1);
				        		}
				            });
				    		angular.forEach(playlistTracksData,function(value, i){
				        		if(value.playlist_id == playlist.id){
				           			playlistTracksData.splice(i, 1);
				        		}
				            });
				            if(!$scope.playlistData.length){
				        		$uibModalInstance.dismiss('cancel');
				        	}
						});
                    })
				}

				$scope.updatePlaylistName = function(playlist) {
					var formData = {
									  'id'	: playlist.id,
								      'name': playlist.name
								  };
					PlaylistApi.updateList(formData, function(data) {
			    		angular.forEach(parentScopeData,function(value){
			        		if(value.id == playlist.id){
								value.name=playlist.name;
			        		}
			            })
			            playlist.error = {};
			        	playlist.editing = false;
			    	}, function(r){
						if(r.data.name){
							playlist.error = {};
							playlist.error.name = r.data.name[0];
						}
					});
				};

  			},
  			resolve: {
            	playlistData: function () {
  				  	return angular.copy(playlistData);
  				},
  				parentScopeData: function () {
  				  	return playlistData;
  				},
  				playlistTracksData: function () {
  				  	return $scope.playlistTracks;
  				}
  			}
        });
    }

});