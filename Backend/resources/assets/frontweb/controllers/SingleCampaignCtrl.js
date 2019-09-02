 angular.module('app.controllers')
.controller('SingleCampaignCtrl', function($state , $scope,$sce, TrackApi , SpotifyApi, $http, $stateParams,PlayerService, SoundcloudService, SharedData, FacebookService , $uibModal ,$location,$window, $rootScope,InstagramServiceApi, $timeout) {
    $('body').attr('class','landing-page-track');
    // $('body').addClass('background-blur');

    $('.animsition').hide();

    var id = $stateParams.trackId;

    $scope.sp_connect = false; // for spotify
    $scope.sc_connect = false; // for soundcloud
    $scope.fb_connect = false; // for facebook
    $scope.twtr_connect = false; // for twitter
    $scope.instagram_connect = true;

    $scope.downloadTermsCompleted = false;
    $scope.typeTrack = true; // this variable is used for audio player intialization as audio / video file
    $scope.activeTab = '';
    $scope.isSubmitting = false;
    $scope.trackType = $state.current.name == 'app.single_campaign' ? 'campaign' : 'track';

    $scope.playerInit = function(track , trackType) {
        sessionStorage.play_count_increases = 0;
        $scope.typeTrack = trackType == 'audio';// for checking audio / video track
        PlayerService.play($scope.track,$scope.track.t_type);
        PlayerService.pause();
    }

    // get Track & download terms information
    var track = TrackApi.getSingleTrack({'id':id,'type': $scope.trackType}, function(r) {
        $scope.track = r.data;

console.log("????????????????????????????????");
        console.log($scope.track);
        console.log("????????????????????????????????");

        if(r.data.artist_links) {
            $scope.track.artist_links = $.parseJSON(r.data.artist_links);
        } else {
            $scope.track.artist_links = [];
        }

        if(r.data.spotify_artist_links) {
            $scope.track.spotify_artist_links = $.parseJSON(r.data.spotify_artist_links);
        } else {
            $scope.track.spotify_artist_links = [];
        }

        if($scope.track.background_file && $("#page-top").hasClass("landing-page-track") ){
            if ($scope.track.blur == '0') {
                $rootScope.pagebackground = $scope.track.background_file;
                $("#page-top .parent").css("display", "none");
            } else {
                $("#page-top .child").css("background-image", "url('" + $scope.track.background_file + "')");
            }
        }
        // Player initialization
        $scope.playerInit($scope.track , 'audio');

        // GATING terms
        $scope.spotifyTerms  = $scope.track.spotify_terms ? $.parseJSON($scope.track.spotify_terms) : $scope.track.spotify_terms;
        $scope.soundcloudTerms  = $scope.track.souncloud_terms ? $.parseJSON($scope.track.souncloud_terms) : $scope.track.souncloud_terms;
        $scope.facebookTerms    = $scope.track.facebook_terms ? $.parseJSON($scope.track.facebook_terms) : $scope.track.facebook_terms;
        $scope.twitterTerms     = $scope.track.twitter_terms ? $.parseJSON($scope.track.twitter_terms) : $scope.track.twitter_terms;
        $scope.youtubeTerms     = $scope.track.youtube_terms ? $.parseJSON($scope.track.youtube_terms) : $scope.track.youtube_terms;
        $scope.instagramTerms   = $scope.track.instagram_terms ? $.parseJSON($scope.track.instagram_terms) : $scope.track.instagram_terms;

        // required for youtube subscription action
        $scope.youtube_channel  = $scope.track.youtube_channel;

        // required for twitter artist follow action
        $scope.twitter_url      = $scope.track.twitter_url;

        // required for instagram follow action
        $scope.instagram_user_id = $scope.track.instagram_user_id;

        // oauthProviders are social providers enabled (i.e. ON) by track artist
        if(!$scope.track.oauthProviders){
            $scope.track.oauthProviders = [];
        }

        // steps for download through spotify : follow
        $scope.spSteps = ['connect'];
        $scope.spCompletedSteps = [];
        if( $scope.track.oauthProviders.indexOf('spotify') != -1 && $scope.track.spotify_user_id) {
            jQuery.each($scope.spotifyTerms, function(i, val) {
                if(val == 'true')  {
                    $scope.spSteps.push(i);
                }
            });
        }

        // steps for download through soundcloud : follow, like ,comment , repost
        $scope.scSteps = ['connect'];
        $scope.scCompletedSteps = [];
        if( $scope.track.oauthProviders.indexOf('soundcloud') != -1 && $scope.track.provider_user_id) {
            jQuery.each($scope.soundcloudTerms, function(i, val) {
                if(val == 'true')  {
                    // like and comment actions are only available for remix type because for these actions track should be availabe on soundcloud
                    /*if(i == 'like' || i == 'comment' || i == 'repost') {
                        if($scope.track.type == 'remix' && i != 'comment')
                            $scope.scSteps.push(i);
                    } else {
                        $scope.scSteps.push(i);
                    }*/

                    /*if (i != 'comment') $scope.scSteps.push(i);*/

                    if ($scope.track.sc_id == null || $scope.track.sc_id == "") {
                        if (i == 'follow') {
                            $scope.scSteps.push(i);
                        }
                    } else {
                        /*if (i != 'comment') $scope.scSteps.push(i);*/
                        $scope.scSteps.push(i);
                    }
                }
            });
        }

        // steps for download through facebook : like , share
        $scope.fbSteps = ['connect'];
        $scope.fbCompletedSteps = [];

        if( $scope.track.oauthProviders.indexOf('facebook') != -1 ){
            jQuery.each($scope.facebookTerms, function(i, val) {
                if(val == 'true'){
                    if(i == 'like') {
                        if($scope.track.fb_page && $scope.track.artist_fb_liked != true) {
                            $scope.fbSteps.push(i);
                            $scope.fbCompletedSteps.push('like');
                        }

                        if($scope.track.stm_fb_page && $scope.track.stm_fb_liked != true) {
                            $scope.fbSteps.push('stm-like');
                            $scope.fbCompletedSteps.push('stm-like');
                        }
                    } else {
                        $scope.fbSteps.push(i);
                    }
                }
            });
        }


        // steps for download through facebook : follow , tweet
        $scope.twtrSteps = [];
        $scope.twtrCompletedSteps = [];
        if( $scope.track.oauthProviders.indexOf('twitter') != -1 && $scope.twitter_url) {
            jQuery.each($scope.twitterTerms, function(i, val) {
                if(val == 'true'){
                    $scope.twtrSteps.push(i);
                }
            });
        }

        // steps for download through youtube : subscribe
        $scope.ytSteps = [];
        $scope.ytCompletedSteps = [];
        if( $scope.track.oauthProviders.indexOf('youtube') != -1 && $scope.youtube_channel) {
            jQuery.each($scope.youtubeTerms, function(i, val) {
                if(val == 'true'){
                    $scope.ytSteps.push(i);
                }
            });
        }

        // steps for download through instagram : subscribe
        $scope.instaSteps = [];//['connect'];
        $scope.instaCompletedSteps = [];
        if( $scope.track.oauthProviders.indexOf('instagram') != -1 && $scope.instagram_user_id && $scope.track.instagram_nickname) {
            jQuery.each($scope.instagramTerms, function(i, val) {
                if(val == 'true'){
                    $scope.instaSteps.push(i);
                }
            });
        }

        if($scope.track.instagram_followed == true) {
            $scope.instaCompletedSteps.push('subscribe');
            $scope.instaAlreadyFollowed = true;
            // activate download button if instagram terms completed initially by user already following artist condition
            // if($scope.instaSteps.length == $scope.instaCompletedSteps.length) {
            //     $scope.downloadTermsCompleted = true;
            // }
        }

        if($scope.track.youtube_subscribed == true) {
            $scope.ytCompletedSteps.push('subscribe');
            $scope.fbAlreadySubscribed = true;
            // activate download button if instagram terms completed initially by user already following artist condition
            // if($scope.ytSteps.length == $scope.ytCompletedSteps.length) {
            //     $scope.downloadTermsCompleted = true;
            // }
        }

        $scope.$watch('currentPage', function(){

        });


        $('.animsition').show();
        $scope.twtr_connect = true;

        if($scope.spSteps.length > 1) {
            $scope.activeTab = 'spotify';
            if($scope.spSteps.length == $scope.spCompletedSteps.length) {
                $scope.downloadTermsCompleted = true;
            }
            else{
                $scope.downloadTermsCompleted = false;
            }
        } else if($scope.scSteps.length > 1) {
            $scope.activeTab = 'soundcloud';
            if($scope.scSteps.length == $scope.scCompletedSteps.length) {
                $scope.downloadTermsCompleted = true;
            }
            else{
                $scope.downloadTermsCompleted = false;
            }
        } else if($scope.fbSteps.length > 1) {
            $scope.activeTab = 'facebook';
            if($scope.fbSteps.length == $scope.fbCompletedSteps.length) {
                $scope.downloadTermsCompleted = true;
            }
            else{
                $scope.downloadTermsCompleted = false;
            }
        } else if($scope.twtrSteps.length > 0) {
            $scope.activeTab = 'twitter';
            if($scope.twtrSteps.length == $scope.twtrCompletedSteps.length) {
                $scope.downloadTermsCompleted = true;
                }
                else{
                    $scope.downloadTermsCompleted = false;
                }
        } else if($scope.ytSteps.length > 0) {
            $scope.activeTab = 'youtube';
            if($scope.track.youtube_subscribed == true) {
                $scope.downloadTermsCompleted = true;
            }
            else{
                $scope.downloadTermsCompleted = false;
            }
        } else if($scope.instaSteps.length > 0) {
            $scope.activeTab = 'instagram';
            if($scope.track.instagram_followed == true) {
                $scope.downloadTermsCompleted = true;
            }
            else{
                $scope.downloadTermsCompleted = false;
            }
        } else {
            $scope.downloadTermsCompleted = true;
        }


        $scope.downloadTerms = function(type){
            if(type == 'youtube'){
                if($scope.track.youtube_subscribed == true) {
                    $scope.downloadTermsCompleted = true;
                }
                else{
                    $scope.downloadTermsCompleted = false;
                }
            }
            if(type == 'instagram'){
                if($scope.track.instagram_followed == true) {
                    $scope.downloadTermsCompleted = true;
                }
                else{
                    $scope.downloadTermsCompleted = false;
                }
            }
            if(type == 'spotify'){
                if($scope.spSteps.length == $scope.spCompletedSteps.length) {
                    $scope.downloadTermsCompleted = true;
                }
                else{
                    $scope.downloadTermsCompleted = false;
                }
            }
            if(type == 'soundcloud'){
                if($scope.scSteps.length == $scope.scCompletedSteps.length) {
                    $scope.downloadTermsCompleted = true;
                }
                else{
                    $scope.downloadTermsCompleted = false;
                }
            }
            if(type == 'facebook'){
                if($scope.fbSteps.length == $scope.fbCompletedSteps.length) {
                    $scope.downloadTermsCompleted = true;
                }
                else{
                    $scope.downloadTermsCompleted = false;
                }
            }
            if(type == 'twitter'){
                if($scope.twtrSteps.length == $scope.twtrCompletedSteps.length) {
                    $scope.downloadTermsCompleted = true;
                }
                else{
                    $scope.downloadTermsCompleted = false;
                }
            }
        }

        FB.XFBML.parse();
    });

    $scope.showGating = function(){
        $('#collapseOne').collapse('hide');
        $('#collapseTwo').collapse('show');
    }

    // trigger to download a track
    $scope.downloadTrack = function() {
        // $('#collapseOne').collapse('toggle');
        // $('#collapseTwo').collapse('toggle');

        localStorage.downloadableTrack = JSON.stringify({trackId:$scope.track.slug,type: $scope.trackType});

        var downloadableTrack = localStorage.downloadableTrack ? JSON.parse(localStorage.downloadableTrack) : null;
        if(downloadableTrack && downloadableTrack.trackId == $stateParams.trackId) {

            url = $rootScope.baseUrl + '/'+$scope.trackType+'/download/' + $scope.track.slug;


            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                    if(SharedData._user){
                        TrackApi.sendDownloadLink({'type':$scope.trackType, 'slug':$scope.track.slug}, function(r) {
                            swal("Thank you for downloading this track","We have sent the download link to your e-mail!");
                            setTimeout(function(){
                                $state.go('app.recommended_campaigns',{trackId:$scope.track.slug,type: $scope.trackType});
                            }, 500);
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
                                    TrackApi.sendDownloadLink({'type':parentScope.trackType, 'slug':parentScope.track.slug,'name':name,'email':email}, function(r) {
                                        $rootScope.showLoading = false;
                                        $uibModalInstance.dismiss('cancel');
                                        swal("Thank you for downloading this track","We have sent the download link to your e-mail!");
                                        setTimeout(function(){
                                            $state.go('app.recommended_campaigns',{trackId:parentScope.track.slug,type: parentScope.trackType});
                                        }, 500);
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
                            //     }
                            if($scope.track){
                                index = $rootScope._followingTo.indexOf($scope.track.user_id);
                                if(index == -1 && $rootScope.user && $scope.track.user_id != $rootScope.user.id){
                                    $rootScope.FollowArtist($scope.track.artist_slug);
                                }

                            }
                            setTimeout(function(){
                                $state.go('app.recommended_campaigns',{trackId:$scope.track.slug,type: $scope.trackType});
                            }, 500);

                        },
                        failCallback: function (html, url) {
                            toastr.error('Currently file is not available to download, please try later');
                        }
                    });
                }
        } else {
            toastr.error("Unable to access file.");
        }

        // if($scope.track.track_type == 'original' || $scope.track.track_type == 'remix') {
        //     url = $rootScope.baseUrl + '/campaign/download/' + $scope.track.slug;
        //     window.location.assign(url);
        //     $state.go('app.recommended_campaigns',{trackId:$scope.track.slug,type:$scope.track.type});
        //     // $('#downloadBtn').click();
        // } else {
        //     toastr.error("Unable to access file.");
        // }
    }

    // save user action info in database about follow, like , subscribe etc
    $scope.saveTrackShares = function (social_user ,action  , type , artistId){

        var provider_user_id = social_user ? social_user : '';
        var share_Action = action;
        var share_type = type;
        var trackId = $scope.track.id;
        var artist_Id = artistId ? artistId : $scope.track.user_id;
        var data = {    artistId : artist_Id ,
                        trackId :trackId ,
                        provider_user_id : provider_user_id,
                        share_Action:share_Action,
                        share_type : share_type,
                        track_type : $scope.track.track_type
                    };
        TrackApi.shares(null, data, function(r) {

        });

    }

    /* Soundcloud Gating */
    // SoundcloudService.initialize(SharedData._oauthConfig['soundcloud']);
    $window.soundcloudApp = {
        connectCallback : null
    };
    $window.spotifyApp = {
        connectCallback : null
    };


    $scope.spotifyConnect = function() {
        var w = 500, h = 400;
        var left = (screen.width/2)-(w/2);
        var top = (screen.height/2)-(h/2);


        var params = [];
        $window.spotifyApp.connectCallback = function(status, token, user){

            if(status == 'success' && user) {

                $scope.spotify_user = user;
                $scope.sp_token = token;

                $scope.sp_connect = true;

                if($scope.spCompletedSteps.indexOf('connect') == -1) {
                    $scope.spCompletedSteps.push('connect');

                    if($scope.spSteps.length == $scope.spCompletedSteps.length) {
                        $scope.downloadTermsCompleted = true;
                    }
                }

                $scope.$apply();
            }
            if(status == 'error') {
                toastr.error("Something went wrong. Please try again");
            }

        };

        var win = window.open ('/gating/login/spotify',"Spotify","menubar=1,resizable=1,width="+w+",height="+h+",top="+top+',left='+left);
        setWindowTitle(win, 'Spotify');
    }

    $scope.spotifyFollow = function(artistId) {
        if(!$scope.sp_connect){
            return false;
        }

        $scope.isSubmitting = true;
        /*var ids = $scope.track.spotify_user_id;*/
        var ids = '';
        for (var i = 0; i < $scope.track.spotify_artist_links.length; i ++) {
            if (!ids.trim()) {
                ids = $scope.track.spotify_artist_links[i].id;
            } else {
                ids += ',' + $scope.track.spotify_artist_links[i].id;
            }
        }

        var playlistLink = $scope.track.playlists_link;
        var ownerId, playlistId = '';
        if (playlistLink != '') {
            ownerId = playlistLink.split('/')[4];
            playlistId = playlistLink.split('/')[6];
        }

        var trackLink = $scope.track.track_link;
        var trackLinkId = '';
        if (trackLink != '') {
            trackLinkId = trackLink.split('/')[4];
        }

        var albumLink = $scope.track.album_link;
        var albumLinkId = '';
        if (albumLink != '') {
            albumLinkId = albumLink.split('/')[4];
        }

        /*var trackAlbumId = '';
        var taType = 'track';
        if (trackAlbumLink != '') {
            taType = trackAlbumLink.split('/')[3];
            trackAlbumId = trackAlbumLink.split('/')[4];
        }*/

        $scope.totalFollowType = 0;
        $scope.checkedFollowCounts = 0;
        if (id != '') {
            $scope.totalFollowType ++;
        }
        if ((ownerId != null && ownerId != '') && (playlistId != null && playlistId != '')) {
            $scope.totalFollowType ++;
        }
        if (trackLinkId != null && trackLinkId != '') {
            $scope.totalFollowType ++;
        }
        if (albumLinkId != null && albumLinkId != '') {
            $scope.totalFollowType ++;
        }

        // Follow Artist.
        if (ids != '') {
            SpotifyApi.artist($scope.sp_token).followArtist({'type':'artist', 'ids':ids}, null, function success() {
                $scope.checkedFollowCounts ++;

                if ($scope.totalFollowType == $scope.checkedFollowCounts) {
                    if($scope.spCompletedSteps.indexOf('follow') == -1) {
                        $scope.spCompletedSteps.push('follow');
                        $timeout(function(){$scope.$apply();}, 0);
                        if($scope.spSteps.length == $scope.spCompletedSteps.length) {
                            $scope.downloadTermsCompleted = true;
                        }

                        $scope.saveTrackShares($scope.spotify_user.id,'follow','spotify');
                    }

                    $scope.isSubmitting = false;
                }
            }, function error() {
                toastr.error("Something went wrong. Please try again");
                $scope.isSubmitting = false;
            });
        } else {
            $scope.spCompletedSteps.push('follow');
            $scope.downloadTermsCompleted = true;
            $scope.isSubmitting = false;
        }

        // Follow Playlist.
        if ((ownerId != null && ownerId != '') && (playlistId != null && playlistId != '')) {
            SpotifyApi.playlist(ownerId, playlistId, $scope.sp_token).followPlaylist(null, null, function success() {
                $scope.checkedFollowCounts ++;

                if ($scope.totalFollowType == $scope.checkedFollowCounts) {
                    if($scope.spCompletedSteps.indexOf('follow') == -1) {
                        $scope.spCompletedSteps.push('follow');
                        $timeout(function(){$scope.$apply();}, 0);
                        if($scope.spSteps.length == $scope.spCompletedSteps.length) {
                            $scope.downloadTermsCompleted = true;
                        }

                        $scope.saveTrackShares($scope.spotify_user.id,'follow','spotify');
                    }

                    $scope.isSubmitting = false;
                }
            }, function error() {
                toastr.error("Something went wrong. Please try again");
                $scope.isSubmitting = false;
            });
        }

        // Track to Save.
        if (trackLinkId != null && trackLinkId != '') {
            SpotifyApi.artist($scope.sp_token).saveTrack({'ids':trackLinkId}, null, function success() {
                $scope.checkedFollowCounts ++;

                if ($scope.totalFollowType == $scope.checkedFollowCounts) {
                    if($scope.spCompletedSteps.indexOf('follow') == -1) {
                        $scope.spCompletedSteps.push('follow');
                        $timeout(function(){$scope.$apply();}, 0);
                        if($scope.spSteps.length == $scope.spCompletedSteps.length) {
                            $scope.downloadTermsCompleted = true;
                        }

                        $scope.saveTrackShares($scope.spotify_user.id,'follow','spotify');
                    }

                    $scope.isSubmitting = false;
                }
            }, function error() {
                toastr.error("Something went wrong. Please try again");
                $scope.isSubmitting = false;
            });
        }

        // Album to Save.
        if (albumLinkId != null && albumLinkId != '') {
            SpotifyApi.artist($scope.sp_token).saveAlbum({'ids':albumLinkId}, null, function success() {
                $scope.checkedFollowCounts ++;

                if ($scope.totalFollowType == $scope.checkedFollowCounts) {
                    if($scope.spCompletedSteps.indexOf('follow') == -1) {
                        $scope.spCompletedSteps.push('follow');
                        $timeout(function(){$scope.$apply();}, 0);
                        if($scope.spSteps.length == $scope.spCompletedSteps.length) {
                            $scope.downloadTermsCompleted = true;
                        }

                        $scope.saveTrackShares($scope.spotify_user.id,'follow','spotify');
                    }

                    $scope.isSubmitting = false;
                }
            }, function error() {
                toastr.error("Something went wrong. Please try again");
                $scope.isSubmitting = false;
            });
        }

        // Track or Album to Save.
        /*if (trackAlbumId != null && trackAlbumId != '') {
            if (taType == 'track') {
                SpotifyApi.artist($scope.sp_token).saveTrack({'ids':trackAlbumId}, null, function success() {
                    $scope.checkedFollowCounts ++;

                    if ($scope.totalFollowType == $scope.checkedFollowCounts) {
                        if($scope.spCompletedSteps.indexOf('follow') == -1) {
                            $scope.spCompletedSteps.push('follow');

                            if($scope.spSteps.length == $scope.spCompletedSteps.length) {
                                $scope.downloadTermsCompleted = true;
                            }

                            $scope.saveTrackShares($scope.spotify_user.id,'follow','spotify');
                        }

                        $scope.isSubmitting = false;
                    }
                }, function error() {
                    toastr.error("Something went wrong. Please try again");
                    $scope.isSubmitting = false;
                });
            } else if (taType == 'album') {
                SpotifyApi.artist($scope.sp_token).saveAlbum({'ids':trackAlbumId}, null, function success() {
                    $scope.checkedFollowCounts ++;

                    if ($scope.totalFollowType == $scope.checkedFollowCounts) {
                        if($scope.spCompletedSteps.indexOf('follow') == -1) {
                            $scope.spCompletedSteps.push('follow');

                            if($scope.spSteps.length == $scope.spCompletedSteps.length) {
                                $scope.downloadTermsCompleted = true;
                            }

                            $scope.saveTrackShares($scope.spotify_user.id,'follow','spotify');
                        }

                        $scope.isSubmitting = false;
                    }
                }, function error() {
                    toastr.error("Something went wrong. Please try again");
                    $scope.isSubmitting = false;
                });
            }
        }*/

    }

    $scope.soundcloudConnect = function() {

        var w = 500, h = 400;
        var left = (screen.width/2)-(w/2);
        var top = (screen.height/2)-(h/2);


        var params = [];
        $window.soundcloudApp.connectCallback = function(status, token, user){

            if(status == 'success' && user) {

                $scope.soundcloud_user = user;
                $scope.sc_token = token;

                $scope.sc_connect = true;

                if($scope.scCompletedSteps.indexOf('connect') == -1) {
                    $scope.scCompletedSteps.push('connect');

                    if($scope.scSteps.length == $scope.scCompletedSteps.length) {
                        $scope.downloadTermsCompleted = true;
                    }
                }

                $scope.$apply();
                // toastr.success('Connected to soundcloud');
            }
            if(status == 'error') {
                toastr.error("Something went wrong. Please try again");
            }

        };

        var win = window.open ($rootScope.baseUrl+'/sc/login',"Soundcloud","menubar=1,resizable=1,width="+w+",height="+h+",top="+top+',left='+left);
        setWindowTitle(win, 'Soundcloud');
    }

    $scope.soundcloudFollow = function(artistId) {
        // artistId = $scope.track.provider_user_id;
        /*if(!$scope.sc_connect){
            return false;
        }*/

        // if(artistId) {
            /*$scope.isSubmitting = true;*/
            SoundcloudService.follow({'artist_id':$scope.track.provider_user_id, 'track_id':$scope.track.id, 'track_type':$scope.trackType,'token' : $scope.sc_token}, function(r) {

                if (r.success) {
                    if($scope.scCompletedSteps.indexOf('follow') == -1) {
                        $scope.scCompletedSteps.push('follow');

                        if($scope.scSteps.length == $scope.scCompletedSteps.length) {
                            $scope.downloadTermsCompleted = true;
                        }

                        $scope.saveTrackShares($scope.soundcloud_user.id,'follow','soundcloud');
                        // toastr.success('You followed this artist at soundcloud successfully');
                        // $scope.$apply();
                    }
                } else if(r.error){
                    toastr.error("Error: " + r.error);
                } else {
                    toastr.error("Something went wrong. Please try again");
                }

                $scope.checkedSoundcloudAction ++;
                if ($scope.totalSoundcloudAction == $scope.checkedSoundcloudAction) {
                    $scope.isSubmitting = false;
                }
            });
        // } else {
        //     toastr.error('Artist not found at soundcloud');
        // }

    }

    $scope.soundcloudLike = function(trackId) {

        /*if(!$scope.sc_connect){
            return false;
        }*/

        if(trackId) {
            /*$scope.isSubmitting = true;*/
            SoundcloudService.like({'track_id':trackId, 'token' : $scope.sc_token}, function(r) {

                if (r.success) {

                    if($scope.scCompletedSteps.indexOf('like') == -1) {
                        $scope.scCompletedSteps.push('like');

                        if($scope.scSteps.length == $scope.scCompletedSteps.length) {
                            $scope.downloadTermsCompleted = true;
                        }

                        $scope.saveTrackShares($scope.soundcloud_user.id,'like','soundcloud');
                        // toastr.success('You liked this track at soudcloud successfully');
                        // $scope.$apply();
                    }
                } else if(r.error){
                    toastr.error("Error: " + r.error);
                } else {
                    toastr.error("Something went wrong. Please try again");
                }

                $scope.checkedSoundcloudAction ++;
                if ($scope.totalSoundcloudAction == $scope.checkedSoundcloudAction) {
                    $scope.isSubmitting = false;
                }
            })
        } else {
            toastr.error('Error: Track not found at soundcloud');
        }
    }

    $scope.soundcloudRepost = function(trackId) {

        /*if(!$scope.sc_connect){
            return false;
        }*/

        if(trackId) {
            /*$scope.isSubmitting = true;*/
            SoundcloudService.repost({'track_id':trackId, 'token' : $scope.sc_token}, function(r) {

                if (r.success) {

                    if($scope.scCompletedSteps.indexOf('repost') == -1) {
                        $scope.scCompletedSteps.push('repost');

                        if($scope.scSteps.length == $scope.scCompletedSteps.length) {
                            $scope.downloadTermsCompleted = true;
                        }

                        $scope.saveTrackShares($scope.soundcloud_user.id,'repost','soundcloud');
                        // toastr.success('You reposted this track at soudcloud successfully');
                        // $scope.$apply();
                    }
                } else if(r.error){
                    toastr.error("Error: " + r.error);
                } else {
                    toastr.error("Something went wrong. Please try again");
                }

                $scope.checkedSoundcloudAction ++;
                if ($scope.totalSoundcloudAction == $scope.checkedSoundcloudAction) {
                    $scope.isSubmitting = false;
                }
            })
        } else {
            toastr.error('Error: Track not found at soundcloud');
        }
    }

    $scope.soundcloudComment = function(trackId, comment) {

        /*if(!$scope.sc_connect){
            return false;
        }*/

        if(trackId) {
            /*$scope.isSubmitting = true;*/
            SoundcloudService.comment({'track_id':trackId, 'token' : $scope.sc_token}, {'comment': comment } , function (r) {
                if (r.success) {

                    if($scope.scCompletedSteps.indexOf('comment') == -1) {
                        $scope.scCompletedSteps.push('comment');

                        if($scope.scSteps.length == $scope.scCompletedSteps.length) {
                            $scope.downloadTermsCompleted = true;
                        }

                        $scope.saveTrackShares($scope.soundcloud_user.id,'comment','soundcloud');
                    }
                } else if(r.error){
                    toastr.error("Error: " + r.error);
                } else {
                    toastr.error("Something went wrong. Please try again");
                }

                $scope.checkedSoundcloudAction ++;
                if ($scope.totalSoundcloudAction == $scope.checkedSoundcloudAction) {
                    $scope.isSubmitting = false;
                }
            });
        } else {
            toastr.error('Error: Track not found at soundcloud');
        }
    }

    $scope.soundcloudActionConfirm = function(sc_id, provider_id, comment) {

        if(!$scope.sc_connect){
            return false;
        }

        $scope.totalSoundcloudAction = 0;
        $scope.checkedSoundcloudAction = 0;

        if ($scope.scSteps.indexOf('like') != -1) $scope.totalSoundcloudAction ++;
        if ($scope.scSteps.indexOf('repost') != -1) $scope.totalSoundcloudAction ++;
        if ($scope.scSteps.indexOf('follow') != -1) $scope.totalSoundcloudAction ++;
        if ($scope.scSteps.indexOf('comment') != -1) $scope.totalSoundcloudAction ++;

        $scope.isSubmitting = true;

        if ($scope.scSteps.indexOf('like') != -1) {
            $scope.soundcloudLike(sc_id);
        }
        if ($scope.scSteps.indexOf('repost') != -1) {
            $scope.soundcloudRepost(sc_id);
        }
        if ($scope.scSteps.indexOf('follow') != -1) {
            $scope.soundcloudFollow(provider_id);
        }
        if ($scope.scSteps.indexOf('comment') != -1) {
            $scope.soundcloudComment(sc_id, comment);
        }
    }

    /*$scope.soundcloudComment = function (trackId) {

        /!*if(!$scope.sc_connect){
         return false;
         }*!/

        $scope.isSubmitting = false;
        $scope.trackId = trackId;

        var instance = $uibModal.open({
            // animation: true,
            templateUrl: 'commentModal',
            controller: function ($scope, parentScope, $uibModalInstance) {
                $scope.trackId = trackId;
                /!*$scope.sc_connect = parentScope.sc_connect;
                 $scope.soundcloud_user = parentScope.soundcloud_user.id;*!/
                $scope.cancel = function () {
                    $scope.track = {};
                    $uibModalInstance.dismiss('cancel');
                };

                $scope.comment = function (comment) {
                    $scope.isSubmitting = true;
                    if ($scope.trackId) {
                        var msg = comment;
                        SoundcloudService.comment({
                            'track_id': $scope.trackId,
                            'token': parentScope.sc_token
                        }, {'comment': msg}, function (r) {

                            if (r.error) {
                                toastr.error("Error: " + r.error);
                            } else {
                                $scope.isSubmitting = false;
                                $uibModalInstance.dismiss('cancel');
                                // $scope.soundcloudActionCompleted = true;

                                if (parentScope.scCompletedSteps.indexOf('comment') == -1) {
                                    parentScope.scCompletedSteps.push('comment');

                                    if (parentScope.scSteps.length == parentScope.scCompletedSteps.length) {
                                        parentScope.downloadTermsCompleted = true;
                                    }

                                    parentScope.saveTrackShares($scope.soundcloud_user, 'comment', 'soundcloud');
                                    // parentScope.$apply();
                                    // toastr.success("Posted comment:  " + msg);

                                }
                            }
                        });
                    }
                }

                $scope.cancel = function () {
                    $uibModalInstance.dismiss('cancel');
                };

            },

            resolve: {
                parentScope: function () {
                    return $scope;
                }
            }
        });
    }*/

    /* Facebook Gating */
    FacebookService.initialize(SharedData._oauthConfig['facebook']);
    FB.XFBML.parse();

    $scope.facebookConnect = function () {
        FB.XFBML.parse();
        // FB.getLoginStatus(function(response){

        //     if(response.status == 'connected'){
        //         // $scope.fb_connect = true ;
        //         $scope.fb_user = response.authResponse.userID;

        //         $scope.fb_token = response.authResponse.accessToken;

        //         if($scope.track.artist_fb_liked && $scope.fbSteps.indexOf('like') != -1){
        //             if($scope.fbCompletedSteps.indexOf('like') == -1)
        //                 $scope.fbCompletedSteps.push('like');
        //         }

        //         if($scope.track.stm_fb_liked && $scope.fbSteps.indexOf('stm-like') != -1){
        //             if($scope.fbCompletedSteps.indexOf('stm-like') == -1)
        //                 $scope.fbCompletedSteps.push('stm-like');
        //         }

        //         $scope.fb_connect = true ;

        //         // FB.api(
        //         //     "/",
        //         //     {
        //         //         "id": $scope.track.fb_page
        //         //     },
        //         //     function (response) {
        //         //       if (response && !response.error) {
        //         //         /* handle the result */
        //         //         FB.api(
        //         //             '/me/likes/'+response.id,
        //         //             'GET',
        //         //             function(r) {
        //         //                 console.log(r);
        //         //                 // Insert your code here
        //         //                 if (r && !r.error && r.data.length) {
        //         //                     $scope.fbCompletedSteps.push('like');
        //         //                     $scope.$apply();
        //         //                 }
        //         //                 $scope.fb_connect = true ;
        //         //           }
        //         //         );
        //         //       } else {
        //         //         $scope.fb_connect = true ;
        //         //       }
        //         //     }
        //         // );

        //         // FB.api(
        //         //     "/",
        //         //     {
        //         //         "id": $scope.track.stm_fb_page
        //         //     },
        //         //     function (response) {
        //         //       if (response && !response.error) {
        //         //         /* handle the result */
        //         //         FB.api(
        //         //             '/me/likes/'+response.id,
        //         //             'GET',
        //         //             function(r) {
        //         //                 console.log(r);
        //         //                 // Insert your code here
        //         //                 if (r && !r.error && r.data.length) {
        //         //                     $scope.fbCompletedSteps.push('stm-like');
        //         //                     $scope.$apply();
        //         //                 }
        //         //                 $scope.fb_connect = true ;
        //         //           }
        //         //         );
        //         //       } else {
        //         //         $scope.fb_connect = true ;
        //         //       }
        //         //     }
        //         // );

        //         if($scope.fbCompletedSteps.indexOf('connect') == -1) {
        //             $scope.fbCompletedSteps.push('connect');

        //             if($scope.fbSteps.length == $scope.fbCompletedSteps.length) {
        //                 $scope.downloadTermsCompleted = true;
        //             }
        //         }
        //         // $scope.$apply();
        //     }
        //     else{
                FB.login(function(response){

                    if(response.authResponse && response.authResponse == null){
                        return false;
                    }
                    else if(response.authResponse && response.status == 'connected'){
                        $scope.fb_token = response.authResponse.accessToken;

                        $scope.fb_user = response.authResponse.userID;

                        if($scope.track.artist_fb_liked && $scope.fbSteps.indexOf('like') != -1){
                            if($scope.fbCompletedSteps.indexOf('like') == -1)
                                $scope.fbCompletedSteps.push('like');
                        }

                        if($scope.track.stm_fb_liked && $scope.fbSteps.indexOf('stm-like') != -1){
                            if($scope.fbCompletedSteps.indexOf('stm-like') == -1)
                                $scope.fbCompletedSteps.push('stm-like');
                        }

                        $scope.fb_connect = true ;
                        // FB.api(
                        //     "/",
                        //     {
                        //         "id": $scope.track.fb_page
                        //     },
                        //     function (response) {
                        //       if (response && !response.error) {
                        //         /* handle the result */
                        //         FB.api(
                        //             '/me/likes/'+response.id,
                        //             'GET',
                        //             function(r) {
                        //                 // Insert your code here
                        //                 console.log(r);
                        //                 if (r && !r.error) {
                        //                     $scope.fbCompletedSteps.push('like');
                        //                     $scope.$apply();
                        //                 }
                        //                 $scope.fb_connect = true ;
                        //           }
                        //         );
                        //       } else {
                        //         $scope.fb_connect = true ;
                        //       }
                        //     }
                        // );

                        // FB.api(
                        //     "/",
                        //     {
                        //         "id": $scope.track.stm_fb_page
                        //     },
                        //     function (response) {
                        //       if (response && !response.error) {
                        //         /* handle the result */
                        //         FB.api(
                        //             '/me/likes/'+response.id,
                        //             'GET',
                        //             function(r) {
                        //                 // Insert your code here
                        //                 console.log(r);
                        //                 if (r && !r.error) {
                        //                     $scope.fbCompletedSteps.push('stm-like');
                        //                     $scope.$apply();
                        //                 }
                        //                 $scope.fb_connect = true ;
                        //           }
                        //         );
                        //       } else {
                        //         $scope.fb_connect = true ;
                        //       }
                        //     }
                        // );

                        // $scope.fb_connect = true ;

                        if($scope.fbCompletedSteps.indexOf('connect') == -1) {
                            $scope.fbCompletedSteps.push('connect');

                            if($scope.fbSteps.length == $scope.fbCompletedSteps.length) {
                                $scope.downloadTermsCompleted = true;
                            }
                        }
                        $scope.$apply();
                    }
                }, {scope: 'email, publish_actions, user_likes, manage_pages'});
        //     }
        // });
    }

    $scope.facebookShare = function(cover_image,track_name){

        if(!$scope.fb_connect){
            return false;
        }

        // link = $location.absUrl();
        // if(link.match('^http://')){
        //     link = link.replace("http://","https://")
        // }
        // if(cover_image){
        //     cover_image = $window.globalObj.baseUrl + '/' +cover_image;
        // }

        if(cover_image.match('^http://')){
            cover_image = cover_image.replace("http://","https://")
        } else if(cover_image.match('^https://')) {
        } else {
            cover_image = $window.globalObj.baseUrl + '/' +cover_image;
        }

        // if(cover_image){
        //     var checkHttp = cover_image.search('http://');
        //     var checkHttps = cover_image.search('https://');

        //     if(!(checkHttp || checkHttps)) {
        //         cover_image = $rootScope.baseUrl+'/images/logo_crop.jpg';
        //     }
        // }

        var fbOptions = {   'method' : 'feed',
                            // link: $rootScope.baseUrl + '/fb/player' +'/' +$scope.trackType +  '/'+$stateParams.trackId,
                        };

        // if($scope.track.type == 'remix') {
            fbOptions.link = $location.absUrl();
            //fbOptions.name = $scope.track.track_name;
            //fbOptions.picture = cover_image;
            // caption: 'Sore Thumb Media music',
            //fbOptions.description = ($scope.track.genres ? $scope.track.genres.join('-') : '') + '-' + ($scope.track.moods ? $scope.track.moods.join('-') : '');
        // }

        FB.ui(fbOptions,function(response) {
            if (response && !response.error_code) {
                // toastr.success('You shared this track at facebook successfully.');
                $scope.saveTrackShares($scope.fb_user,'share','facebook');

                if($scope.fbCompletedSteps.indexOf('share') == -1) {
                    $scope.fbCompletedSteps.push('share');

                    if($scope.fbSteps.length == $scope.fbCompletedSteps.length) {
                        $scope.downloadTermsCompleted = true;
                    }
                }
                $scope.$apply();
            } else {

            }
        });
    }

    $scope.facebookLike = function(href) {
        if(href == $scope.track.fb_page) {
            $scope.track_fb_liked = true;
            // if($scope.fbCompletedSteps.indexOf('like') == -1) {
                $scope.saveTrackShares($scope.fb_user,'like','facebook');
            //     $scope.fbCompletedSteps.push('like');
            // }
        }

        if(href == $scope.track.stm_fb_page) {
            $scope.stm_fb_liked = true;
            // if($scope.fbCompletedSteps.indexOf('stm-like') == -1) {
                $scope.saveTrackShares($scope.fb_user,'like','facebook' , $scope.track.stm_artist_id);
            //     $scope.fbCompletedSteps.push('stm-like');
            // }
        }


        if($scope.fbSteps.length == $scope.fbCompletedSteps.length) {
            $scope.downloadTermsCompleted = true;
        }
    }
    /*
    $scope.facebookLike = function(facebook_url){

        var postData = {
            fb_token    : $scope.fb_token,
            post_link   : $location.absUrl(),
            track_slug  : $scope.track.slug,
            track_type  : $scope.trackType,
            caption     : $scope.track.track_name,
            picture     : $scope.track.cover_image ? $scope.track.cover_image.cover_image : $rootScope.baseUrl+'/images/logo_crop.jpg',
            description : ($scope.track.genres ? $scope.track.genres.join(', ') : '') + ' ' + ($scope.track.moods ? $scope.track.moods.join(', ') : '')
        }

        $scope.isSubmitting = true;
        FacebookService.like(postData).then(function(r){
             $scope.isSubmitting = false;
            if(r.data.success){
                if($scope.fbCompletedSteps.indexOf('like') == -1) {
                    $scope.fbCompletedSteps.push('like');

                    if($scope.fbSteps.length == $scope.fbCompletedSteps.length) {
                        $scope.downloadTermsCompleted = true;
                    }
                }
                // toastr.success('You liked this artist at facebook successfully.');
                $scope.saveTrackShares($scope.fb_user,'like','facebook');
                // $scope.$apply();
            } else if(r.data.error) {
                toastr.error(r.data.error);
            } else {
                toastr.error("Something went wrong.Please try again.");
            }
        });

        // if(!$scope.track.facebook_user_id){
        //     toastr.error("Artists facebook profile not found.");
        //     return false;
        // }
        // if(!$scope.fb_connect){
        //     return false;
        // }
        // if(facebook_url.match('^http://')){
        //     facebook_url = facebook_url.replace("http://","https://")
        // }

        // FB.ui({
        //     method: 'share_open_graph',
        //     action_type: 'og.likes',
        //     action_properties: JSON.stringify({
        //     // 'profile': $scope.track.facebook_user_id
        //         object : $location.absUrl()
        //     })
        // },
        // function(response) {
        //     if(response.error_code){
        //         if(response.error_code == '3501'){
        //             toastr.error("You already like this user on facebook")
        //         }
        //     }
        //     console.log(response);
        //     if(!response.error_code){
        //         if($scope.fbCompletedSteps.indexOf('like') == -1) {
        //             $scope.fbCompletedSteps.push('like');

        //             if($scope.fbSteps.length == $scope.fbCompletedSteps.length) {
        //                 $scope.downloadTermsCompleted = true;
        //             }
        //         }
        //         toastr.success('You liked this artist at facebook successfully.');
        //         $scope.saveTrackShares($scope.fb_user,'like','facebook');
        //         $scope.$apply();
        //     }
        //     if (response && response.post_id) {

        //     } else {

        //     }
        // });

    }
    */

    /* Instagram Gating */
    $window.instagramApp =   {
                                // user_name : "stmdeveloper",
                                // user_id : SharedData._oauthProviderUsers['instagram'],
                                client_id : SharedData._oauthConfig['instagram'],//"dc872bfafb6d4533b8f950cb04f0c182",
                                redirect_uri : $rootScope.baseUrl+"/instagram/process",
                                connectCallback : instaConnectCallback
                            };

    $scope.instagramConnect = function(){
        var w = 500, h = 400;
        var left = (screen.width/2)-(w/2);
        var top = (screen.height/2)-(h/2);
        var win = window.open ("https://instagram.com/oauth/authorize/?client_id="+$window.instagramApp.client_id+"&redirect_uri="+$window.instagramApp.redirect_uri+"&response_type=token&scope=basic+public_content+relationships","Instagram","menubar=1,resizable=1,width="+w+",height="+h+",top="+top+',left='+left);
        // setWindowTitle(win, 'Instagram');
    }

    $scope.instagramFollow = function() {

        if($scope.track.instagram_nickname) {
            if($scope.instaCompletedSteps.indexOf('subscribe') == -1) {
                $scope.saveTrackShares(null,'follow','instagram');
                $scope.instaCompletedSteps.push('subscribe');

                if($scope.instaSteps.length == $scope.instaCompletedSteps.length) {
                    $scope.downloadTermsCompleted = true;
                }
            }
        }
        // if($window.instagramApp.access_token) {
        //     $scope.isSubmitting = true;

        //     InstagramServiceApi.follow(null,{access_token:$window.instagramApp.access_token,instagram_user_id:$scope.instagram_user_id},function(r){
        //         response = $.parseJSON(r.data);
        //         if(r.data == false || response.data == null){
        //             toastr.error("Something went wrong.Please try again.");
        //             return false;
        //         }
        //         if(response.data.outgoing_status && response.data.outgoing_status == 'follows'){
        //             // toastr.success('You followed this artist at instagram successfully.');
        //             $scope.saveTrackShares(null,'follow','instagram');

        //             if($scope.instaCompletedSteps.indexOf('subscribe') == -1) {
        //                 $scope.instaCompletedSteps.push('subscribe');

        //                 if($scope.instaSteps.length == $scope.instaCompletedSteps.length) {
        //                     $scope.downloadTermsCompleted = true;
        //                 }
        //             }
        //         }
        //         $scope.isSubmitting = false;
        //     },function(r){
        //         if(r.data.status == 'error')
        //         toastr.error(r.data.message);
        //     });
        // } else {
        //     toastr.error("Unable to perform this action");
        // }
    }

    // var scope = $scope;
    function instaConnectCallback(token){

        if(token) {
            $window.instagramApp.access_token = token;

            $scope.instagram_connect = true;

            if($scope.instaCompletedSteps.indexOf('connect') == -1) {
                $scope.instaCompletedSteps.push('connect');

                if($scope.instaSteps.length == $scope.instaCompletedSteps.length) {
                    $scope.downloadTermsCompleted = true;
                }
            }

            // toastr.success('Connected to instagram');
            $scope.$apply();
        }

    }

    /* Twitter Gating */
    $window.twitterApp =   {
                                connectCallback : null
                            };

    $scope.twitterConnect = function(action , data, callback){
        var w = 500, h = 400;
        var left = (screen.width/2)-(w/2);
        var top = (screen.height/2)-(h/2);
        $window.twitterApp.connectCallback = callback;
        // var params = [
        //     'artist='+$scope.track.twitter_nickname,
        //     'track='+$scope.track.slug,
        //     'type='+$scope.track.type
        // ];

        var params = [];
        if(data) {
            jQuery.each(data, function(i, val) {
              $("#" + i).append(document.createTextNode(" - " + val));
              var param = i+'='+val
              params.push(param);
            });
        }

        win = window.open($rootScope.baseUrl+'/twitter/login/'+action+'?'+params.join('&'),"Twitter","menubar=1,resizable=1,width="+w+",height="+h+",top="+top+',left='+left);
        setWindowTitle(win, 'Twitter');
    }

    $scope.twitterFollow = function() {

        var data = {
            artist : $scope.track.twitter_nickname
        }

        $scope.twitterConnect('follow', data, function(status){
            if(status == 'success') {
                if($scope.twtrCompletedSteps.indexOf('follow') == -1) {
                    $scope.twtrCompletedSteps.push('follow');

                    if($scope.twtrSteps.length == $scope.twtrCompletedSteps.length) {
                        $scope.downloadTermsCompleted = true;
                    }

                    // toastr.success('You followed this artist at twitter successfully.');
                    $scope.saveTrackShares(null,'follow','twitter');
                    $scope.$apply();
                }
            }
        });
     }

     $scope.twitterTweet = function() {

        var instance = $uibModal.open({
            templateUrl : 'tweetModal',
            controller  : function($scope,parentScope,$uibModalInstance){
                $scope.trackId = parentScope.track.id;

                $scope.comment = function(comment){
                    // $scope.isSubmitting = true;
                    var data = {
                        'artist' : parentScope.track.twitter_nickname,
                        'track'  : parentScope.track.slug,
                        'type'   : parentScope.trackType,
                        'tweet'  : comment
                    };

                    if(parentScope.track.id) {
                        parentScope.isSubmitting = true;
                        parentScope.twitterConnect('tweet', data , function(status){
                            if(status == 'success') {
                                parentScope.isSubmitting = false;
                                if(parentScope.twtrCompletedSteps.indexOf('tweet') == -1) {
                                    parentScope.twtrCompletedSteps.push('tweet');

                                    if(parentScope.twtrSteps && parentScope.twtrCompletedSteps && parentScope.twtrSteps.length == parentScope.twtrCompletedSteps.length) {
                                        parentScope.downloadTermsCompleted = true;
                                    }

                                    parentScope.saveTrackShares(null,'tweet','twitter');

                                    // toastr.success('Tweet posted successfully');

                                    parentScope.$apply();
                                }
                                $uibModalInstance.dismiss('cancel');
                            }
                        });
                    }
                }

                $scope.cancel = function () {
                        $uibModalInstance.dismiss('cancel');
                };

            },
            resolve: {
                parentScope : function() {
                    return $scope;
                }
            }
        });
     }


    // Youtube Gating
    $window.youtubeApp = {
        connectCallback : null
    }

     $scope.youtubeSubscribe = function(){
        if(!$scope.youtube_channel) {
            toastr.error('Youtube channel not found');
            return;
        }

        var data = {
            youtube_channel : $scope.youtube_channel
        }

        // $scope.isSubmitting = true;
        $scope.youtubeConnect(data, function(){
            if($scope.ytCompletedSteps.indexOf('subscribe') == -1) {
                $scope.ytCompletedSteps.push('subscribe');

                if($scope.ytSteps.length == $scope.ytCompletedSteps.length) {
                    $scope.downloadTermsCompleted = true;
                }

                // toastr.success('You subscribed to artist\'s youtube channel successfully.');
                $scope.saveTrackShares(null,'subscribe','youtube');
                // $scope.isSubmitting = false;
                $scope.$apply();
            }
        });
     }

     $scope.youtubeConnect = function(data, callback){
        var w = 500, h = 400;
        var left = (screen.width/2)-(w/2);
        var top = (screen.height/2)-(h/2);
        $window.youtubeApp.connectCallback = callback;

        var params = [];
        if(data) {
            jQuery.each(data, function(i, val) {
              $("#" + i).append(document.createTextNode(" - " + val));
              var param = i+'='+val
              params.push(param);
            });
        }


        var win = window.open ($rootScope.baseUrl+'/youtube/process?'+params.join('&'),"Youtube","menubar=1,resizable=1,width="+w+",height="+h+",top="+top+',left='+left);
        setWindowTitle(win, 'YouTube');
    }


    function setWindowTitle(win, title) {
        // if(win.document) { // if loaded
        //     win.document.title = title; // set title
        // } else { // if not loaded yet
        //     setTimeout(check, 10); // check in another 10ms
        // }
    }
});