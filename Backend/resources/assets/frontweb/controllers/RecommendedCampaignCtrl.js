angular.module('app.controllers')
.controller('RecommendedCampaignCtrl', function($scope, $rootScope ,RecommendedTrackApi , $http , $stateParams, SharedData,TrackApi, $uibModal ) {
	$('body').attr('class','landing-page-track-recommended');
    $scope.trackType = $stateParams.type;
	var id = $stateParams.trackId;
	RecommendedTrackApi.get({'id':id,'type':$scope.trackType}, function(r){
		$scope.artistsRandomTracks = r.data.artistsRandomTracks;
		$scope.stmArtistsGenresTracks = r.data.stmArtistsGenresTracks;
		$scope.stmArtistTracks = r.data.stmArtistTracks;
		$scope.artist = r.data.artist;

        // $rootScope.pagebackground = $scope.artist.background_file;
    }, function(r) {
       $scope.error = r.data;
    });

    $scope.isUndefined = function(thing) {
        return (typeof thing === "undefined");
    }
    $scope.showArrow = function() {
        return (!$scope.isUndefined($scope.artistsRandomTracks) && !$scope.isUndefined($scope.stmArtistsGenresTracks) && !$scope.isUndefined($scope.stmArtistTracks))
            && (($scope.artistsRandomTracks.length == '0' && $scope.stmArtistsGenresTracks.length != '0' && $scope.stmArtistTracks.length != '0')
            || ($scope.artistsRandomTracks.length != '0' && $scope.stmArtistsGenresTracks.length == '0' && $scope.stmArtistTracks.length != '0')
            || ($scope.artistsRandomTracks.length != '0' && $scope.stmArtistsGenresTracks.length != '0' && $scope.stmArtistTracks.length == '0')
            || ($scope.artistsRandomTracks.length != '0' && $scope.stmArtistsGenresTracks.length != '0' && $scope.stmArtistTracks.length != '0'))
    }

    $scope.downloadTrack = function() {
        var downloadableTrack = localStorage.downloadableTrack ? JSON.parse(localStorage.downloadableTrack) : null;
        if(downloadableTrack && downloadableTrack.trackId == $stateParams.trackId) {

            url = $rootScope.baseUrl + '/'+$scope.trackType+'/download/' + $scope.artist.slug;

            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                    if(SharedData._user){
                        TrackApi.sendDownloadLink({'type':$scope.trackType, 'slug':$scope.artist.slug}, function(r) {
                            swal("Thank you for downloading this track","We have sent the download link to your e-mail!");
                        });
                    }
                    else{

                        $uibModal.open({
                            templateUrl: 'GetEmailModal',
                            controller: function($scope, $uibModalInstance , parentScope){
                                $scope.cancel = function () {
                                    $uibModalInstance.dismiss('cancel');
                                };

                                $scope.ok = function (name,email) {
                                    $rootScope.showLoading = true;
                                    TrackApi.sendDownloadLink({'type':parentScope.trackType, 'slug':parentScope.artist.slug,'name':name,'email':email}, function(r) {
                                        $rootScope.showLoading = false;
                                        $uibModalInstance.dismiss('cancel');
                                        swal("Thank you for downloading this track","We have sent the download link to your e-mail!");
                                    });
                                };
                                $scope.cancel = function () {
                                        $uibModalInstance.dismiss('cancel');
                                };
                            },
                            resolve: {
                                donateData: function () {
                                    // return angular.copy(donateData);
                                },
                                parentScope: function () {
                                    return $scope;
                                }
                            }
                        });
                    }

                }
                else{
                    $.fileDownload(url, {
                        successCallback: function (url) {
                            // if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                            //     if(SharedData._user){
                            //         swal("Download link has been sent to your email.");
                            //     }
                            //     else{
                            //         swal("Please login to get download link");
                            //     }
                            // }

                            if($scope.artist){
                                index = $rootScope._followingTo.indexOf($scope.artist.user_id);
                                if(index == -1 && $rootScope.user && $scope.artist.user_id != $rootScope.user.id){
                                    $rootScope.FollowArtist($scope.artist.artist_slug);
                                }

                            }


                            // if($scope.artist && $rootScope.user && $scope.artist.user_id != $rootScope.user.id){
                            //     index = $rootScope._followingTo.indexOf($scope.artist.user_id);
                            //     if(index == -1){
                            //         $rootScope._followingTo.push($scope.artist.user_id);
                            //     }
                            // }
                            // $scope.$apply();
                        },
                        failCallback: function (html, url) {
                            // if($scope.artist && $rootScope.user && $scope.artist.user_id != $rootScope.user.id){
                            //     index = $rootScope._followingTo.indexOf($scope.artist.user_id);
                            //     if(index == -1){
                            //         $rootScope._followingTo.push($scope.artist.user_id);
                            //     }
                            // }
                            // $scope.$apply();

                            toastr.error('Currently file is not available to download, please try later');
                        }
                    });
                }
        } else {
            toastr.error("Unable to access file.");
        }
    }
});