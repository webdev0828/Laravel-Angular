angular.module('app.controllers')
.controller('DashboardCtrl', function($scope ,$http, $window, $uibModal, DashboardApi,SharedData,$rootScope ,$state ,$timeout,SoundCloudApi, SpotifyApi, ArtistApi, FacebookService) {

	$scope.track = {};
    $scope.streamline = {};
    $scope.subGenres =[];

    // $scope.images = [];

    // angular.forEach($scope._banners,function(image){
    //     $scope.images.push(image);
    // });

    $scope.images = $scope._banners;

    $scope.campaignsCurrentPage = 1;
    $scope.demoTracksCurrentPage = 1;
    $scope.streamlinesCurrentPage = 1;

    $scope.numPerPage = 10;
    $scope.maxSize = 10;
    $scope.totalDemoTracks = 0;
    $scope.scPublicTracks = [];
    // $scope.index = '';
    // $scope.images = ['frontweb/assets/images/admin/img_page-background.jpg', 'frontweb/assets/images/admin/bgd_send-demo.jpg',  'frontweb/assets/images/admin/bgd_image.jpg', 'frontweb/assets/images/admin/img_track-cover.jpg'];
    $scope.state = {
        spotify : SharedData._oauthProviders.indexOf('spotify') != -1,
        soundcloud : SharedData._oauthProviders.indexOf('soundcloud') != -1,
        facebook : SharedData._oauthProviders.indexOf('facebook') != -1,
        twitter : SharedData._oauthProviders.indexOf('twitter') != -1,
        youtube : SharedData._oauthProviders.indexOf('youtube') != -1,
        instagram : SharedData._oauthProviders.indexOf('instagram') != -1
    };

    $scope.createType = 'campaign';
    $scope.chartType = 'campaign';

    $scope.testEmailNotification = function() {
        ArtistApi.submitDemos(null, {});
    }

	$scope.getTracksData = function() {
        DashboardApi.get({page:1}, function(data) {
            $scope.soundCloudTracks = data.soundCloudTracks;
            $scope.campaigns = data.campaigns.data;
            $scope.campaignsCurrentPage = data.campaigns.current_page;
            $scope.totalCampaigns = data.campaigns.total;

            $scope.streamlines = data.streamlines.data;
            $scope.streamlinesCurrentPage = data.streamlines.current_page;
            $scope.totalStreamlines = data.streamlines.total;

            $scope.demoTracks = data.demoTracks.data;
            $scope.demoTracksCurrentPage = data.demoTracks.current_page;
            $scope.totalDemoTracks = data.demoTracks.total;
        });
    };

    $scope.getCampaigns = function() {
        DashboardApi.getCampaign({page:$scope.campaignsCurrentPage}, function(data) {
            $scope.campaigns = data.campaigns.data;
            $scope.campaignsCurrentPage = data.campaigns.current_page;
            $scope.totalCampaigns = data.campaigns.total;
        });
    };

   $scope.getTrackDemos = function() {
        DashboardApi.getTrackDemo({page:$scope.demoTracksCurrentPage}, function(data) {
            $scope.demoTracks = data.demoTracks.data;
            $scope.demoTracksCurrentPage = data.demoTracks.current_page;
            $scope.totalDemoTracks = data.demoTracks.total;
        });
    };

    $scope.getStreamlines = function() {
        DashboardApi.getStreamline({page:$scope.streamlinesCurrentPage}, function(data) {
            $scope.streamlines = data.streamlines.data;
            $scope.streamlinesCurrentPage = data.streamlines.current_page;
            $scope.totalStreamlines = data.streamlines.total;
        });
    };

   $scope.setCreateType = function(type) {
       $scope.createType = type;
   }

   $scope.setChartType = function(type) {
       $scope.chartType = type;
   }

   $scope.openStreamlineModal = function(streamline, type) {
       var instance = $uibModal.open({
           templateUrl: 'streamlineModal',
           controller: function ($scope, $uibModalInstance, SharedData, DashboardApi, StreamlinesData, parentScope) {
               $scope.getRemixCampaigns = function() {
                   DashboardApi.getRemixCampaign(function(data) {
                       $scope.remixCampaigns = data.remixCampaigns;
                   });
               };

               $scope.$watch('streamline.custom', function() {
                   if (typeof $scope.streamline.custom == "undefined") return;
                   if (typeof $scope.streamline.stm == "undefined") return;

                   console.log('custom:', $scope.streamline.custom);
                   if ($scope.streamline.custom && $scope.streamline.stm) {
                       $scope.streamline.stm = false;
                   }
               });

               $scope.$watch('streamline.stm', function() {
                   if (typeof $scope.streamline.custom == "undefined") return;
                   if (typeof $scope.streamline.stm == "undefined") return;

                   console.log('stm:', $scope.streamline.stm);
                   console.log('stm1:', $scope.track.artist_name);
                   if ($scope.streamline.custom && $scope.streamline.stm) {
                       $scope.streamline.custom = false;
                   }
               });

               $scope.test = function() {
                   console.log('111');
               }

               $scope.getMainInfo = function(value) {
                   if (value != '' && value != null) {
                       $rootScope.loadingTrack = true;

                       SoundCloudApi.getTrackFromURL({url: value}, function(data) {
                           $rootScope.loadingTrack = false;

                           $scope.artistName = data.mainInfo.user.username;
                           $scope.audioName = data.mainInfo.title;
                           $scope.image_source = data.mainInfo.artwork_url;
                           $scope.trackId = data.mainInfo.id;
                           $scope.streamline.sc_link = value;
                       }, function(r) {
                           $rootScope.loadingTrack = false;
                           toastr.err(r.data.message);
                       });
                   }
               }

               $scope.cancel = function () {
                   $scope.streamline ={};
                   $uibModalInstance.dismiss('cancel');
               };

               $scope.stepName = 'first';
               $scope.setStep = function(step) {
                   $scope.stepName = step;
               }

               $scope.setFile = function(element) {
                   var reader = new FileReader();
                   reader.onload = function(event) {
                       $scope.custom_image_source = event.target.result;
                       $scope.$apply();
                   }
                   reader.readAsDataURL(element.files[0]);
               }

               $scope.setBackground = function (element){
                   var reader = new FileReader();
                   reader.onload = function(event) {
                       $scope.image_source_background = event.target.result
                       $scope.$apply();
                   }
                   reader.readAsDataURL(element.files[0]);
               }

               $scope.clearFileInput = function (type) {
                   if(type == 'artwork'){
                       angular.element("input.upload_artwork[type='file']").val(null);
                       $scope.custom_image_source = streamline.custom_art_file;
                   }
                   if(type == 'page-background'){
                       angular.element("input.upload_background[type='file']").val(null);
                       if($scope.images.indexOf(streamline.bg_file) == -1) {
                           $scope.image_source_background =  streamline.bg_file;
                       } else {
                           $scope.image_source_background = '';
                       }
                   }
               }

               $scope.moveSecondStep = function() {
                   $scope.setStep('second');
               }
               $scope.moveThirdStep = function(track) {
                   $scope.setStep('third');
               }

               $scope.addStreamline = function(streamline) {
                   $scope.isSubmitting = true;

                   var fd = new FormData();
                   angular.forEach(streamline, function(item, key){
                       item = item ? item : '';
                       fd.append(key, item);
                   });

                   fd.append('artwork_link', $scope.image_source);
                   fd.append('artist_name', $scope.artistName);
                   fd.append('track_name', $scope.audioName);
                   fd.append('track_id', $scope.trackId);

                   $rootScope.showLoading = true;

                   DashboardApi.storeStreamline(fd, function(data) {
                       $scope.completed = 'true';
                       $scope.isSubmitting = false;
                       $scope.streamlines = data.streamlines;
                       parentScope.getTracksData();
                       $scope.cancel();
                       $rootScope.showLoading = false;
                       parentScope._currentPlanDetails.streamlineCount = parentScope._currentPlanDetails.streamlineCount + 1;

                       parentScope.createType = 'streamline';
                       parentScope.$apply();
                       /*if(type == "add"){
                        swal({
                        title: "",
                        text: 'Please add your gated page URL link to your track on Soundcloud!<br><span style="color:red;font-size:11px;">PLEASE NOTE IF THIS IS NOT DONE, WE WILL NOT PROMOTE YOUR TRACK IN DISCOVER OR REPOST!</span>',
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: 'Confirm',
                        closeOnCancel: true,
                        html:true
                        });
                        }*/
                   },function(r){
                       $rootScope.showLoading = false;
                       $scope.isSubmitting = false;
                   });
               }

               $scope.images = parentScope.images ;//['frontweb/assets/images/admin/img_page-background.jpg', 'frontweb/assets/images/admin/bgd_send-demo.jpg',  'frontweb/assets/images/admin/bgd_image.jpg', 'frontweb/assets/images/admin/img_track-cover.jpg'];
               $scope.streamline = StreamlinesData;
               $scope.streamline.action = type;

               if (type == 'add') {
                   $scope.streamline.status = 1;
                   $scope.streamline.blur = 0;

                   $scope.streamline.soundcloud = '';
                   $scope.streamline.spotify = '';
                   $scope.streamline.youtube = '';
                   $scope.streamline.apple = '';
                   $scope.streamline.tidal = '';
                   $scope.streamline.deezer = '';
                   $scope.streamline.amazon = '';
                   $scope.streamline.itune = '';
                   $scope.streamline.googleplay = '';
                   $scope.streamline.beatport = '';
                   $scope.streamline.amazonmp3 = '';
                   $scope.streamline.bandcamp = '';
                   $scope.streamline.juno = '';
                   $scope.streamline.trackitdown = '';
                   $scope.streamline.stm = '';
                   $scope.streamline.custom = '';
               } else if (type == 'edit') {
                   $scope.streamline = angular.copy(streamline);

                   var social_terms = jQuery.parseJSON(streamline.social_terms);

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

                   $scope.streamline.action = type;
                   $scope.streamline.id = streamline.id;

                   if($scope.images.indexOf(streamline.bg_file) == -1) {
                       $scope.image_source_background =  streamline.bg_file;
                   }
                   $scope.image_source = streamline.artwork_link;
                   $scope.custom_image_source = $scope.streamline.custom ? streamline.custom_art_file : '';
                   $scope.artistName = streamline.artist_name;
                   $scope.audioName = streamline.track_name;
                   $scope.trackId = streamline.track_id;
               }
           },
           size: '',
           resolve: {
               StreamlinesData: function () {
                   return $scope.streamline;
               },
               parentScope: function () {
                   return $scope;
               }
           }
       });

       instance.result.then(function(){}, function(){
           $( "input:checkbox .checkbox-link" ).bootstrapSwitch('state', false, false);
           $scope.streamline ={};
       });
   }

   $scope.openCampaignModal = function(track,type) {
        var instance = $uibModal.open({
            // animation: true,
            templateUrl : 'campaignModal',
            controller  : function($scope, $uibModalInstance, SharedData, TracksData, DashboardApi, CampaignsData, DemoTracksData, parentScope,SoundcloudTracksData){
                $scope.getSoundcloudTracks = function() {
                    $scope.loading = true;
                    SoundCloudApi.get({type: type,sc_id: track ? track.soundcloud_tracks : null}, function(data) {
                        $scope.loading = false;
                        $scope.soundCloudPublicTracks = data.soundCloudTracks;
                        // $scope.findSoundcloudImage(track.soundcloud_track);
                    },function(r){
                        $scope.loading = false;
                        toastr.error(r.data.message);
                    });
                };
                $scope.clearFileInput = function (type) {
                    if(type == 'artwork'){
                        angular.element("input.upload_artwork[type='file']").val(null);
                        $scope.image_source = track.cover_image;
                    }
                    if(type == 'mp3'){
                        angular.element("input.upload_mp3[type='file']").val(null);
                    }
                    if(type == 'page-background'){
                        angular.element("input.upload_background[type='file']").val(null);
                        $scope.image_source_background = track.background_file;
                    }
                }
                $scope.findSoundcloudImage = function(value){
                        if($scope.soundCloudPublicTracks.length){
                            for(var i=0; i<$scope.soundCloudPublicTracks.length; i++){
                                if($scope.soundCloudPublicTracks[i].sc_id == value){
                                    $scope.image_source = $scope.soundCloudPublicTracks[i].cover_image;
                                    $scope.souncloudTrackName = $scope.soundCloudPublicTracks[i].track_name;
                                }
                            }
                        }
                }

                $scope.track      = TracksData;
                $scope.campaigns  = CampaignsData;
                $scope.demoTracks = DemoTracksData;
                $scope.soundCloudPublicTracks = [];// SoundcloudTracksData;

                $scope.index      = '';
                $scope.images     = parentScope.images ;//['frontweb/assets/images/admin/img_page-background.jpg', 'frontweb/assets/images/admin/bgd_send-demo.jpg',  'frontweb/assets/images/admin/bgd_image.jpg', 'frontweb/assets/images/admin/img_track-cover.jpg'];
                $scope.checkUrl = /^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/;
                // $scope.checkUrl2 = /(.mp3|.MP3)$/;
                $scope.gener = [];
                $scope.subGenres = [] ;
                $scope.temp = [] ;
                $scope.social_state = parentScope.state;
                $scope.disabled = false;
                $scope.soundcloudLinks = [];
                $scope.track.action = type;
                $scope.subGenreData = [];
                $scope.campaignGenreData = SharedData._genres;
                $scope.vibeData = SharedData._moods;
                $scope.campaignGenreModel = [];
                $scope.subGenreModel = [];
                $scope.vibeModel = [];
                $scope.scArtists = [];
                $scope.spotifyArtists = [];
                $scope.track.sc_artists = [];
                $scope.track.spotify_artists = [];

                $scope.getScArtists = function(q) {
                    if(q) {
                        $scope.scArtists = SoundCloudApi.getSCUsers({'q':q, 'limit':10});
                    } else {
                        $scope.scArtists = [];
                    }
                }

                $scope.getSpotifyArtists = function(q) {
                    if (q) {
                        $scope.spotifyArtists = SpotifyApi.artist(SharedData._oauthProviderTokens['spotify']).getSpotifyUsers({'q':q, 'type':'artist'});
                    } else {
                        $scope.spotifyArtists = [];
                    }
                }

                if(type == "add"){
                    $scope.track.status = 1;
                    $scope.track.blur = 0;
                    $scope.disabled = false;
                    $scope.campaignGenreSettings = {
                                                        smartButtonMaxItems: 1,
                                                        enableSearch: false,
                                                        selectionLimit: 1,
                                                        closeOnSelect : true,
                                                        smartButtonTextConverter: function(itemText, originalItem) {
                                                            return itemText;
                                                        }
                                                    };
                    if ($scope.social_state.spotify == true) {
                        $scope.track.spotifyFollow  = true;
                    }

                    if($scope.social_state.soundcloud == true){
                        $scope.track.soundcloudFollow  = true;
                        $scope.track.soundcloudLike    = true;
                        $scope.track.soundcloudComment = true;
                        $scope.track.soundcloudRepost  = true;
                    }

                    if($scope.social_state.twitter == true){
                        $scope.track.twitterFollow = true;
                        $scope.track.twitterTweet = true;
                    }

                    if($scope.social_state.facebook == true){
                        $scope.track.facebookShare = true;
                        $scope.track.facebookLike  = true;
                    }

                    if($scope.social_state.youtube == true){
                        $scope.track.youtubeSubscribe = true;
                    }

                    if($scope.social_state.instagram == true){
                        $scope.track.instagramSubscribe = true;
                    }


                    $scope.campaignGenreText = { buttonDefaultText:'Genre'};
                    $scope.subGenreText = { buttonDefaultText: 'Sub-Genre'};
                    $scope.vibeText = { buttonDefaultText: 'Vibe'};
                    $scope.campaignGenreEvents = {
                        onItemSelect: function(evt) {
                            $scope.updateSubGenre(evt, 'add');
                            $scope.subGenreModel = [];
                        }
                    }
                }

                if(type == "edit"){
                    $scope.track = angular.copy(track);
                    var str = $scope.track.mp3_file;
                    var n = str.lastIndexOf('/');
                    $scope.mp3_file_name = str.substring(n + 1);
                    $scope.souncloudTrackName = track.track_name;
                    $scope.track.soundcloud_track = track.soundcloud_tracks ? track.soundcloud_tracks : '';
                    $scope.track.external_link = track.external_download_link;

                    var scDecode = track.artist_links ? jQuery.parseJSON(track.artist_links) : '';
                    $scope.track.sc_artists = scDecode;
                    var spDecode = track.spotify_artist_links ? jQuery.parseJSON(track.spotify_artist_links) : '';
                    $scope.track.spotify_artists = spDecode;

                    $scope.track.type=track.type;
                    $scope.track.action =type;
                    $scope.track.id = track.id;
                    $scope.track.track_name = track.track_name;
                    $scope.track.track_id = track.track_id;

                    $scope.campaignGenreModel = track.track_genres[0] ? track.track_genres[0].name : '';
                    $scope.vibeModel = track.campaign_moods[0] ? track.campaign_moods[0].name : '';
                    $scope.subGenreModel = track.track_sub_genres[0] ? track.track_sub_genres[0].name : '';

                    $scope.disabled = true;

                    $scope.track._genres = $scope.gener;
                    $scope._genres = SharedData._genres;

                    $scope.track.mp3_file = track.mp3_file;
                    $scope.track.artwork = track.cover_image;
                    $scope.image_source  = track.cover_image;
                    $scope.track.backgroundImg = track.background_file;


                    if($scope.images.indexOf(track.background_file) == -1) {
                        $scope.image_source_background =  track.background_file;
                    }

                    // $scope.findSoundcloudImage(track.soundcloud_track);

                    if (track.spotify_terms == '') {
                        track.spotify_terms = '{"follow":"false"}';
                    }
                    var spotify_terms = jQuery.parseJSON(track.spotify_terms);
                    var soundcloud_terms = jQuery.parseJSON(track.souncloud_terms);
                    var twitter_terms    = jQuery.parseJSON(track.twitter_terms);
                    var facebook_terms   = jQuery.parseJSON(track.facebook_terms);
                    var youtube_terms    = jQuery.parseJSON(track.youtube_terms);
                    var instagram_terms  = jQuery.parseJSON(track.instagram_terms);

                    $scope.track.spotifyFollow  = spotify_terms.follow;

                    $scope.track.soundcloudFollow  = soundcloud_terms.follow;
                    $scope.track.soundcloudLike    = soundcloud_terms.like;
                    $scope.track.soundcloudComment = soundcloud_terms.comment;
                    $scope.track.soundcloudRepost  = soundcloud_terms.repost;

                    $scope.track.twitterFollow = twitter_terms.follow;
                    $scope.track.twitterTweet = twitter_terms.tweet;

                    $scope.track.facebookShare = facebook_terms.share;
                    $scope.track.facebookLike  = facebook_terms.like;

                    $scope.track.youtubeSubscribe = youtube_terms.subscribe;
                    $scope.track.instagramSubscribe = instagram_terms.subscribe;


                    /*if(track.type =="remix"){
                        $scope.getSoundcloudTracks(); //added to handle the remix sc track load
                    }*/
                }

                $scope.updateSubGenre = function(genre) {
                    $scope.subGenr = [];
                    if( genre && genre.id ) {
                        for (var i = 0; i < $scope.campaignGenreData.length; i++) {
                            if($scope.campaignGenreData[i].id == genre.id){
                                if(typeof $scope.campaignGenreData[i].sub_genres != 'undefined' && $scope.campaignGenreData[i].sub_genres ){
                                    for (var j = 0; j < $scope.campaignGenreData[i].sub_genres.length; j++) {
                                        $scope.subGenr = $scope.campaignGenreData[i].sub_genres;
                                    }
                                }
                            }
                        }
                    }
                    $scope.subGenreData = $scope.subGenr;
                };

                $scope.setFile = function(element) {
                    var reader = new FileReader();
                    reader.onload = function(event) {
                    $scope.image_source = event.target.result;
                    $scope.$apply();
                    }
                  reader.readAsDataURL(element.files[0]);
                }

                $scope.setBackground = function (element){
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        $scope.image_source_background = event.target.result
                        $scope.$apply();
                    }
                    reader.readAsDataURL(element.files[0]);
                }
                // $scope.addLinks = function() {
                //     if($scope.soundcloudLinks.length <= 3){
                //         $scope.soundcloudLinks.push({"url":''})
                //     }
                //     else{
                //         toastr.error("A maximum of 4 links can be added to your profile.");
                //     }
                // }




                // $scope.soundCloudTracks = [{'id':1,'artist_id':4,'sc_id':27293549,'track_name':'testing remix track test - 2','cover_image':'https://i1.sndcdn.com/artworks-000170342007-qtgu5j-large.jpg','url':'https://api.soundcloud.com/tracks/272135979/stream','download_url':'https://api.soundcloud.com/tracks/272135979/download'},{'id':2,'artist_id':4,'sc_id':21223549,'track_name':'test - 2','cover_image':'https://i1.sndcdn.com/artworks-000170342007-qtgu5j-large.jpg','url':'https://api.soundcloud.com/tracks/272135979/stream','download_url':'https://api.soundcloud.com/tracks/272135979/download'}]

                // $scope.removeLinks = function(index) {
                //     if($scope.soundcloudLinks.length > 1){
                //         $scope.soundcloudLinks.splice(index,1);
                //     }
                //     else if($scope.soundcloudLinks.length == 1){
                //         $scope.soundcloudLinks[0].url = '';
                //     }
                // }

                $scope.cancel = function () {
                        $scope.track ={};
                        $uibModalInstance.dismiss('cancel');
                };
                $scope.reset = function () {

                        $scope.subGenreData ='';
                        $scope.image_source ='';
                        $scope.image_source_background = '';
                        $scope.track ={};
                        $scope.track.status = 1;
                        $scope.track.blur = 0;

                        if ($scope.social_state.spotify == true) {
                            $scope.track.spotifyFollow  = true;
                        }

                        if($scope.social_state.soundcloud == true){
                            $scope.track.soundcloudFollow  = true;
                            $scope.track.soundcloudLike    = true;
                            $scope.track.soundcloudComment = true;
                            $scope.track.soundcloudRepost  = true;
                        }

                        if($scope.social_state.twitter == true){
                            $scope.track.twitterFollow = true;
                            $scope.track.twitterTweet = true;
                        }

                        if($scope.social_state.facebook == true){
                            $scope.track.facebookShare = true;
                            $scope.track.facebookLike  = true;
                        }

                        if($scope.social_state.youtube == true){
                            $scope.track.youtubeSubscribe = true;
                        }

                        if($scope.social_state.instagram == true){
                            $scope.track.instagramSubscribe = true;
                        }


                        $scope.track.action = type;
                        $scope.soundcloudLinks = [];
                };
                $scope.setType = function (type) {
                    /*$scope.tabName =type;*/
                    $scope.typeName = type;
                };
                $scope.setStep = function(step) {
                    $scope.stepName = step;
                }

                $scope.moveSecondStep = function() {
                    $scope.setStep('second');
                }
                $scope.moveThirdStep = function(track) {
                    $scope.setStep('third');
                }
                $scope.moveFourthStep = function(track) {
                    if(!$scope.campaignGenreModel.id && type !='edit')
                        return;
                    if(!$scope.vibeModel.id && type !='edit')
                        return;

                    $scope.setStep('fourth');
                }
                $scope.moveFifthStep = function(track) {
                    $scope.setStep('fifth');
                }

                $scope.addCampaign = function(track) {
                    track.campaignGenreModel = $scope.campaignGenreModel.id ? $scope.campaignGenreModel.id :'';
                    track.subGenreModel = $scope.subGenreModel.id ? $scope.subGenreModel.id : '';
                    track.vibeModel = $scope.vibeModel.id ? $scope.vibeModel.id : '';

                    $scope.isSubmitting = true;

                    var fd = new FormData();
                    angular.forEach(track, function(item, key){
                        item = item ? item : '';
                        fd.append(key, item);

                    });
                    fd.append('_token', $window.globalObj._token);
                    fd.append('type', $scope.typeName);

                    var scArtists = [];
                    for(i in track.sc_artists) {
                        scArtists.push({id:track.sc_artists[i].id, 'username': track.sc_artists[i].username});
                    }
                    fd.append('links', JSON.stringify(scArtists));

                    var spotifyArtists = [];
                    for(var i in track.spotify_artists) {
                        spotifyArtists.push({id: track.spotify_artists[i].id, 'name': track.spotify_artists[i].name});
                    }
                    fd.append('spotify_links', JSON.stringify(spotifyArtists));

                    $rootScope.showLoading = true;

                    DashboardApi.post(fd, function(data) {
                        $scope.completed = 'true';
                        $scope.isSubmitting = false;
                        $scope.error_external_link = false;
                        $scope.campaigns = data.campaigns;
                        parentScope.getTracksData();
                        $scope.cancel();
                        $rootScope.showLoading = false;

                        parentScope.createType = 'campaign';
                        parentScope.$apply();

                        /*if(type == "add"){
                            swal({
                                title: "",
                                text: 'Please add your gated page URL link to your track on Soundcloud!<br><span style="color:red;font-size:11px;">PLEASE NOTE IF THIS IS NOT DONE, WE WILL NOT PROMOTE YOUR TRACK IN DISCOVER OR REPOST!</span>',
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: 'Confirm',
                                closeOnCancel: true,
                                html:true
                            });
                        }*/
                    },function(r){
                        if(r.data){
                            if(r.data.status == 'external_link'){
                                $scope.error_external_link = true;
                                $scope.message_external_link = r.data.message;
                            }
                            if(r.data.status == 'artwork_file'){
                                $scope.error_artwork_file = true;
                            }
                        }

                        $rootScope.showLoading = false;
                        $scope.isSubmitting = false;
                    });
                }

                /*$scope.addCampaign = function(track,campaignType) {
                    // var links = [];
                    if(!$scope.campaignGenreModel.id && type !='edit')
                        return;
                    // if($scope.subGenreData.length > 0 && !$scope.subGenreModel.id && type !='edit')
                    //     return;
                    if(!$scope.vibeModel.id && type !='edit')
                        return;
                    track.campaignGenreModel = $scope.campaignGenreModel.id ? $scope.campaignGenreModel.id :'';
                    track.subGenreModel = $scope.subGenreModel.id ? $scope.subGenreModel.id : '';
                    track.vibeModel = $scope.vibeModel.id ? $scope.vibeModel.id : '';

                    $scope.isSubmitting = true;
                    // for (var i = 0; i < $scope.soundcloudLinks.length; i++) {

                    //     links.push({url :$scope.soundcloudLinks[i].url});
                    // };

                    var fd = new FormData();
                    angular.forEach(track, function(item, key){
                        item = item ? item : '';
                        fd.append(key, item);

                    });
                    fd.append('_token', $window.globalObj._token);
                    fd.append('type', campaignType);

                    var scArtists = [];
                    for(i in track.sc_artists) {
                        scArtists.push({id:track.sc_artists[i].id, 'username': track.sc_artists[i].username});
                    }

                    fd.append('links', JSON.stringify(scArtists));

                    var spotifyArtists = [];
                    for(var i in track.spotify_artists) {
                        spotifyArtists.push({id: track.spotify_artists[i].id, 'name': track.spotify_artists[i].name});
                    }

                    fd.append('spotify_links', JSON.stringify(spotifyArtists));

                    $rootScope.showLoading = true;

                    DashboardApi.post(fd, function(data) {
                        $scope.completed = 'true';
                        $scope.isSubmitting = false;
                        $scope.error_external_link = false;
                        // toastr.success('',data.success);
                        $scope.campaigns = data.campaigns;
                        parentScope.getTracksData();
                        $scope.cancel();
                        $rootScope.showLoading = false;
                         if(type == "add"){
                            swal({
                              title: "",
                              text: 'Please add your gated page URL link to your track on Soundcloud!<br><span style="color:red;font-size:11px;">PLEASE NOTE IF THIS IS NOT DONE, WE WILL NOT PROMOTE YOUR TRACK IN DISCOVER OR REPOST!</span>',
                              confirmButtonColor: "#DD6B55",
                              confirmButtonText: 'Confirm',
                              closeOnCancel: true,
                              html:true
                            });
                        }


                    },function(r){
                        if(r.data){
                            if(r.data.status == 'external_link'){
                                $scope.error_external_link = true;
                                $scope.message_external_link = r.data.message;
                            }
                            if(r.data.status == 'artwork_file'){
                                $scope.error_artwork_file = true;
                            }
                        }

                        $rootScope.showLoading = false;
                        $scope.isSubmitting = false;
                    });
                };*/
            },
            size: '',
            resolve: {
                TracksData: function () {
                    return angular.copy($scope.track);
                  // return $scope.track;
                },
                CampaignsData: function () {
                  return $scope.campaigns;
                },
                DemoTracksData: function () {
                  return $scope.demoTracks;
                },
                SoundcloudTracksData: function () {
                  return $scope.scPublicTracks;
                },
                parentScope : function() {
                    return $scope;
                }
            }
        });

         instance.result.then(function(){
         }, function(){
            $( "input:checkbox .checkbox-link" ).bootstrapSwitch('state', false, false);
            $scope.track ={};
         });
   }

   $scope.deleteCampaignTrack = function(id, index) {
        swal({
                title :'Are you sure, you want to delete this campaign?',
                text: "This action is irreversible. By clicking ‘OK’, this track will be removed anywhere which it may appear across the platform.",
                // type: "",
                showCancelButton: true,
                closeOnConfirm: true,
                showLoaderOnConfirm: false
            }, function(){
                $rootScope.showLoading = true;
                DashboardApi.deleteCampaign({id : id}, function(data) {
                    $scope.campaigns.splice(index, 1);
                    $scope.getTracksData();
                // toastr.success('',data.success);
                    $rootScope.showLoading = false;
                });
        })
    };

    $scope.deleteStreamline = function(id, index) {
        swal({
            title :'Are you sure, you want to delete this streamline?',
            text: "This action is irreversible. By clicking ‘OK’, this streamline will be removed anywhere which it may appear across the platform.",
            showCancelButton: true,
            closeOnConfirm: true,
            showLoaderOnConfirm: false
        }, function(){
            $rootScope.showLoading = true;
            DashboardApi.deleteStreamline({id : id}, function(data) {
                $scope.streamlines.splice(index, 1);
                $scope.getTracksData();
                $scope._currentPlanDetails.streamlineCount = $scope._currentPlanDetails.streamlineCount - 1;
                $rootScope.showLoading = false;
            });
        })
    }

   $scope.setFacebookPage = function(response){
        $uibModal.open({
            templateUrl : 'frontweb/tpl/modals/get-fb-page-modal.html',
            controller  : function($scope, $uibModalInstance, provider){
                $scope.saveFbPage = function(page_link) {
                    $scope.isSubmitting = true;
                    $rootScope.showLoading = true;

                    FB.api(
                        "/",
                        {
                            "id": page_link
                        },
                        function (r) {
                          if (r && !r.error) {
                            /* handle the result */
                            if(!r.og_object) {
                                ArtistApi.saveFbPage({fb_page: page_link}, function(){
                                    $scope.isSubmitting = false;
                                    // $rootScope.showLoading = false;
                                    $uibModalInstance.dismiss('cancel');
                                    window.location = 'connect/'+provider;
                                });
                            } else {
                                $scope.facebookError = "Incorrect facebook page url";
                                $rootScope.showLoading = false;
                                $scope.isSubmitting = false;
                                $scope.$apply();
                            }
                          }
                        }
                    );
                };

                $scope.cancel = function () {
                    $uibModalInstance.dismiss('cancel');
                };
            },
            resolve: {
                provider : function() {
                    return 'facebook';
                }
            }
        }).closed.then(function(){
            $scope.gateUpdating = false;
        });
    }

   $scope.socialStateChange = function(provider, val) {
        $scope.gateUpdating = true;
        if(!val) {

            if(provider == 'facebook') {
                FacebookService.initialize(SharedData._oauthConfig['facebook']);
                FB.login(function(response){
                    if(response.authResponse && response.authResponse == null){
                        return false;
                    }
                    else if(response.authResponse && response.status == 'connected'){
                        return $scope.setFacebookPage(response);
                    }
                }, {scope: 'email, publish_actions, user_likes, manage_pages'});
                return false;
            } else if(provider == 'youtube') {
                var instance = $uibModal.open({
                    templateUrl : 'frontweb/tpl/modals/get-youtube-channel-modal.html',
                    controller  : function($scope, $uibModalInstance,provider){
                        $scope.saveYoutubeChannel = function(youtube_url) {
                            youtube_url = youtube_url.split('?')[0];
                            youtube_url = youtube_url.split('#')[0];
                            var youtube_channel = '';

                            if(youtube_url.match('((http|https):\/\/|)(www.youtube)\.com\/(channel\/|user\/)[a-zA-Z0-9]{1,}')) {
                                var lastSegment = youtube_url.substr(youtube_url.lastIndexOf('/') + 1);
                                youtube_channel = lastSegment;

                                if(youtube_url.indexOf('/user/') != -1){
                                    youtube_param = 'user';
                                }

                                if(youtube_url.indexOf('/channel/') != -1){
                                    youtube_param = 'channel';
                                }
                            } else {
                                $scope.youtubeError = 'Invalid youtube url link';
                                return;
                            };


                            if(youtube_channel && youtube_param) {
                                var ytoptions = {part : 'id', key: $window.globalObj.google_key};

                                if(youtube_param == 'user') {
                                    ytoptions.forUsername = youtube_channel;
                                } else if(youtube_param ) {
                                    ytoptions.id = youtube_channel;
                                }

                                $scope.isSubmitting = true;
                                $rootScope.showLoading = true;

                                $.get( "https://www.googleapis.com/youtube/v3/channels", ytoptions, function(data){

                                    if(data.items.length > 0){

                                        ArtistApi.saveYoutubeChannel({'youtube_channel': youtube_channel}, function(){
                                            $scope.isSubmitting = false;
                                            $uibModalInstance.dismiss('cancel');
                                            $rootScope.showLoading = true;
                                            window.location = 'connect/'+provider;
                                        });
                                    }
                                    else{
                                        $rootScope.showLoading = false;
                                        $scope.isSubmitting = false;
                                        $scope.youtubeError = 'Youtube channel not found';
                                        $scope.$apply();
                                    }

                                }).fail(function(e){
                                    $rootScope.showLoading = false;
                                    $scope.youtubeError = 'Something went wrong. Please try after some time';
                                    $scope.$apply();
                                });
                            } else {
                                $scope.youtubeError = 'Invalid youtube url link';
                            }
                        };

                        $scope.cancel = function () {
                            $uibModalInstance.dismiss('cancel');
                        };
                    },
                    resolve: {
                        provider : function() {
                            return provider;
                        }
                    }
                }).closed.then(function(){
                    $scope.gateUpdating = false;
                });
                return false;
            } else {
                $scope.gateUpdating = false;
                window.location = 'connect/'+provider;
                return false;
            }
        }
        else{
            $http.get('frontapi/socialite/remove/'+provider).then(function(){
                $scope.state[provider] = '';
                var index = SharedData._oauthProviders.indexOf('provider');
                SharedData._oauthProviders.splice(index, 1);
                $scope.gateUpdating = false;
                return false;
            }, function(error) {
               $scope.gateUpdating = false;
            });
            return false;
        }
    }

});