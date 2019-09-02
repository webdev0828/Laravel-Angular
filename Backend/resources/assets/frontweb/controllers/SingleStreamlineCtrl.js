angular.module('app.controllers')
.controller('SingleStreamlineCtrl', function($state , $scope,$sce, StreamlineApi, $http, $stateParams,SharedData, $uibModal ,$location,$window, $rootScope) {
    $('body').attr('class','landing-page-track');

    /*$rootScope.showLoading = true;*/
    var id = $stateParams.streamlineId;
    var streamline = StreamlineApi.getSingleStreamline({'id':id}, function(r) {
        $scope.streamline = r.data;

        if($scope.streamline.bg_file && $("#page-top").hasClass("landing-page-track") ){
            if ($scope.streamline.blur == '0') {
                $rootScope.pagebackground = $scope.streamline.bg_file;
                $("#page-top .parent").css("display", "none");
            } else {
                $("#page-top .child").css("background-image", "url('" + $scope.streamline.bg_file + "')");
            }
        }

        var social_terms = jQuery.parseJSON($scope.streamline.social_terms);

        $scope.streamline.soundcloud = social_terms.soundcloud == 'true' ? true : false;
        $scope.streamline.youtube = social_terms.youtube == 'true' ? true : false;
        $scope.streamline.itune = social_terms.itune == 'true' ? true : false;
        $scope.streamline.apple = social_terms.apple == 'true' ? true : false;
        $scope.streamline.spotify = social_terms.spotify == 'true' ? true : false;
        $scope.streamline.googleplay = social_terms.googleplay == 'true' ? true : false;
        $scope.streamline.tidal = social_terms.tidal == 'true' ? true : false;
        $scope.streamline.deezer = social_terms.deezer == 'true' ? true : false;
        $scope.streamline.beatport = social_terms.beatport == 'true' ? true : false;
        $scope.streamline.amazon = social_terms.amazon == 'true' ? true : false;
        $scope.streamline.amazonmp3 = social_terms.amazonmp3 == 'true' ? true : false;
        $scope.streamline.bandcamp = social_terms.bandcamp == 'true' ? true : false;
        $scope.streamline.juno = social_terms.juno == 'true' ? true : false;
        $scope.streamline.trackitdown = social_terms.trackitdown == 'true' ? true : false;
        $scope.streamline.stm = social_terms.stm == 'true' ? true : false;
        $scope.streamline.custom = social_terms.custom == 'true' ? true : false;

        $scope.activeServiceCnt = 0;
        if ($scope.streamline.stm) $scope.activeServiceCnt ++;
        if ($scope.streamline.soundcloud) $scope.activeServiceCnt ++;
        if ($scope.streamline.youtube) $scope.activeServiceCnt ++;
        if ($scope.streamline.itune) $scope.activeServiceCnt ++;
        if ($scope.streamline.apple) $scope.activeServiceCnt ++;
        if ($scope.streamline.spotify) $scope.activeServiceCnt ++;
        if ($scope.streamline.googleplay) $scope.activeServiceCnt ++;
        if ($scope.streamline.tidal) $scope.activeServiceCnt ++;
        if ($scope.streamline.deezer) $scope.activeServiceCnt ++;
        if ($scope.streamline.beatport) $scope.activeServiceCnt ++;
        if ($scope.streamline.amazon) $scope.activeServiceCnt ++;
        if ($scope.streamline.amazonmp3) $scope.activeServiceCnt ++;
        if ($scope.streamline.bandcamp) $scope.activeServiceCnt ++;
        if ($scope.streamline.juno) $scope.activeServiceCnt ++;
        if ($scope.streamline.trackitdown) $scope.activeServiceCnt ++;
        if ($scope.streamline.custom) $scope.activeServiceCnt ++;

        /*$rootScope.showLoading = false;*/
    });

    $scope.downloadTrack = function() {
        var data = {
            streamlineId: $scope.streamline.id,
            streamlineUserId: $scope.streamline.user_id,
            shareService: 'STM'
        };

        StreamlineApi.shares(null, data, function(r) {
        });

        localStorage.downloadableTrack = JSON.stringify({trackId:$scope.streamline.camp_slug,type: 'campaign'});

        var downloadableTrack = localStorage.downloadableTrack ? JSON.parse(localStorage.downloadableTrack) : null;
        if (downloadableTrack && downloadableTrack.trackId) {
            url = $rootScope.baseUrl + '/campaign/download/' + $scope.streamline.camp_slug;
            if ( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                if (SharedData._user) {
                    TrackApi.sendDownloadLink({'type':'campaign', 'slug':$scope.streamline.camp_slug}, function(r) {
                        swal("Thank you for downloading this track","We have sent the download link to your e-mail!");
                    });
                } else {
                    $uibModal.open({
                        templateUrl: 'GetEmailModal',
                        controller: function($scope, $uibModalInstance , parentScope){
                            $scope.cancel = function () {
                                $uibModalInstance.dismiss('cancel');
                            };

                            $scope.ok = function (name,email) {
                                $rootScope.showLoading = true;
                                TrackApi.sendDownloadLink({'type':'campaign', 'slug':parentScope.streamline.camp_slug,'name':name,'email':email}, function(r) {
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
                            },
                            parentScope: function () {
                                return $scope;
                            }
                        }
                    });
                }
            } else {
                $.fileDownload(url, {
                    successCallback: function (url) {
                    },
                    failCallback: function (html, url) {
                        toastr.error('Currently file is not available to download, please try later');
                    }
                });
            }
        } else {
            toastr.error("Unable to access file.");
        }
    }

    $scope.runLink = function(shareService, serviceLink) {
        console.log(shareService + ':::' + serviceLink);

        var data = {
            streamlineId: $scope.streamline.id,
            streamlineUserId: $scope.streamline.user_id,
            shareService: shareService
        };

        var newWindow = $window.open("", "_blank");
        StreamlineApi.shares(null, data, function(r) {
            newWindow.location.href = serviceLink;
        })
    }
});