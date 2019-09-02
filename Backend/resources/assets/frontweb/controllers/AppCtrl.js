/*
	Add playlist method
	Add Favourtites
	Home page
*/

angular.module('app.controllers')
.controller('AppCtrl', function($scope, $http, AuthService, SharedData, GlobalApi, FavouriteApi, TrackListApi, HomeApi,$state ,
    $timeout, $rootScope, PlayerService , $window,API_URL,$uibModal, ArtistApi,SoundCloudApi ,SoundcloudService, TrackApi ,SubscriptionApi) {

    $scope._user = SharedData._user;
	$scope._plans = SharedData._plans;
	$scope._playlists = SharedData._playlists;
	$rootScope._favTrackIds = SharedData._favTrackIds;
    $rootScope._favCampaignIds = SharedData._favCampaignIds;
	$rootScope._favRemixIds = SharedData._favRemixIds;
	$rootScope._favVideoIds = SharedData._favVideoIds;
    $rootScope._playlistCampaignIds = SharedData._playlistCampaignIds;
    $rootScope._playlistTrackIds = SharedData._playlistTrackIds;
    $rootScope._playlistVideoIds = SharedData._playlistVideoIds;
    $rootScope._playlistRemixIds = SharedData._playlistRemixIds;
	$scope._genres = SharedData._genres;
    $scope._genresAll = SharedData._genresAll;
	$rootScope._followingTo = SharedData._followingTo;
	$scope._oauthProviders = SharedData._oauthProviders;
    $scope._oauthProviderUsers = SharedData._oauthProviderUsers;
    $scope._oauthProviderTokens = SharedData._oauthProviderTokens;
	$scope._oauthConfig = SharedData._oauthConfig;
	SharedData.loadingImage ='frontweb/assets/images/loading-animation-7.gif';
    SharedData.loadingBannerImage ='frontweb/assets/images/loader.gif';
	$scope._defaults = SharedData._defaults;
    $scope._banners = SharedData._banners;
    $scope.notifications = SharedData.notifications;
    $scope.notifications_count = SharedData.notifications_count;

    $rootScope.checkUrl = /^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/;



    SharedData.commonFilter=[{'name':'Newest to oldest','id': 'DESC' },
                        {'name':'Oldest to newest','id': 'ASC'},
                        {'name':'Most Played'   ,'id': '-play_count'},
                        {'name':'Most Favourited','id' : '-favourite_count'},
                        {'name':'Most Downloaded' ,'id': '-download_count'}
                    ];

    $scope.showIntercom = function() {
        Intercom && Intercom('show');
       // $(".intercom-container").css('display','block');
    }

	// $scope._demoTrackIds = SharedData._demoTrackIds;
	$scope._currentPlanDetails = SharedData._currentPlanDetails;

    $scope.contact = {};
    $scope.contact.name=$rootScope.user ? $rootScope.user.name : '';
    $scope.contact.email=$rootScope.user ? $rootScope.user.email : '';
	$rootScope.appScope = $scope;

	$scope.years =[];
    for (var i = 0; i <10; i++) {
        $scope.years.push(new Date().getFullYear()+i);
    };
	$rootScope.list  ={};
	$scope._error = {};

    $rootScope.showTutorials = function(){
        console.log($scope._user);
        tutorialUrl = 'https://www.youtube.com/embed/gZeycWKzTGE';
        if($scope._user && $scope._user.user_type=="stm_user")
            tutorialUrl = 'https://www.youtube.com/embed/MJ2OS4rnKfU';


        swal({
              title: "",
              text: '<style>.sweet-alert{width: 600px !important;margin: 0px !important;position:absolute;left:calc(50% - 300px) !important;top: calc(50% + 50px) !important;}@media screen and (max-width:767px) {.sweet-alert{width:340px !important;}}}</style><div style="position: relative; width: 100%; height: 0px; padding-bottom: 60%;"><iframe id="youtubelink" style="position: absolute; left: 0px; top: 0px; width: 100%; height: 100%"" src="'+tutorialUrl+'" frameborder="0" allowfullscreen></iframe></div>',
              html:true,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: 'Close',
              closeOnCancel: true
            },function(isConfirm){
            if (isConfirm) {
                var video = $("#youtube").attr("src");
                $("#youtubelink").attr("src","");
                }
            });
    }

    $rootScope.showJoinUs = function(){
        tutorialUrl = 'https://www.youtube.com/embed/weiNCqTtssc';


        swal({
              title: "",
              text: '<style>.sweet-alert{width: 67% !important;}@media screen and (max-width:967px) {.sweet-alert{width:340px !important;}}}</style><div style="position: relative; width: 100%; height: 0px; padding-bottom: 60%;"><iframe id="youtube" style="position: absolute; left: 0px; top: 0px; width: 100%; height: 100%"" src="'+tutorialUrl+'" frameborder="0" allowfullscreen></iframe></div>',
              html:true,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: 'Close',
              closeOnCancel: true,
              closeOnConfirm: true
          }, function(isConfirm){
            if (isConfirm) {
                console.log('111');
                var video = $("#youtube").attr("src");
                $("#youtube").attr("src","");/*
                $("#youtube").attr("src", video);*/
                //$window.location.href = $state.href('app');
            }
        });
    }

    $rootScope.showWelcomePopup = function(){
        if($scope._user && $scope._user.welcome_popup == 1 && $scope._user.user_type=="artist" && $window.globalObj.welcomePopup){
            swal({
              title: "<h2 style='font-size:18px;line-height:18px'></b>Your free trial has now come to an end!</b></h2>",
              text: "<style>.sweet-alert {box-sizing : border-box;max-height : 100% !important;overflow-y : auto !important;}.sweet-alert p{font-size:13px;}.sweet-alert h2{margin: 10px 0 !important}</style><p style='color:red;font-size:13px'>IF YOU HAVE ALREADY UPGRADED PLEASE IGNORE THIS MESSAGE</span><br/><br/><p style='text-align:left !important'>You will need to upgrade in order to make full use of the platform, alternatively you can continue using the free 'Test The Waters' plan with limitations now in place.<br/><br/>Many thanks!<br/>STM Team</p>",
              html:true,
              showCancelButton: true,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "Do not show again",
              cancelButtonText: "cancel",
              closeOnConfirm: true,
              closeOnCancel: true
            },
            function(isConfirm){
              if (isConfirm) {
                    ArtistApi.welcomePopup(null, function(r) {
                    $scope._user.welcome_popup = 0;
                });
              }
            });
            // $uibModal.open({
            //     backdrop  : 'static',
            //     appendTo:$window.find('#main'),
            //     templateUrl : 'frontweb/tpl/modals/welcome-popup.html',
            //     controller  : function($scope, $uibModalInstance,ArtistApi){
            //         $scope.close = function(do_not_show){
            //             if(do_not_show){
            //                 ArtistApi.welcomePopup(null, function(r) {
            //                 });
            //             }
            //             $uibModalInstance.dismiss('cancel');
            //         }
            //         $scope.cancel = function () {
            //             $uibModalInstance.dismiss('cancel');
            //         };
            //     },
            //     resolve: {

            //     }
            // }).closed.then(function(){

            // });
        }
    }

	// guide tour function
    $scope.startTour = function(){
        var tour = new Tour({
          steps: [
          {
            element: ".navbar-brand",
            title: "Welcome To STM",
            content: "Let us show you around and introduce some of the features to you.",
            placement: "bottom",
          },
          {
            element: ".navigation-tour",
            title: "Menu Items",
            content: "Master menu items to control your STM experience.",
            placement: "bottom",
          },
          {
            element: "#socials",
            title: "Socials Links",
            content: "Just open your social gates and allow other user to share, like, subscribe your tracks on social sites.",
            placement: "top",
          },
          {
            element: "#runing-campaigns",
            title: "Running Campaigns",
            content: "Your Running Campaigns are listed here.",
            placement: "top",
          },
            {
            element: "#your-soundcloud-tracks",
            title: "SoundCloud Tracks",
            content: "Your submitted demo tracks are listed here with their status about approved, pending or rejected.",
            placement: "top",
          },
          {
            element: "#send-demo",
            title: "Submit Your Demo",
            content: "You can submit your demo track or video from here.",
            placement: "top",
          },
          {
            element: "#activity-feed",
            title: "User Activity",
            content: "Your activities are shown here",
            placement: "top",
          },
          {
            element: "#following",
            title: "Followings",
            content: "Your followers are shown here",
            placement: "top",
          }
        ],
        container: "body",
        smartPlacement: true,
        keyboard: true,
        storage: false,
        debug: false,
        backdrop: true,
        backdropContainer: 'body',
        backdropPadding: 0,
        redirect: true,
        orphan: false,
        duration: false,
        delay: false
        });
        tour.init();
        tour.start();
    };



    $rootScope.playTrack =function(track, type){
        // if(!$scope._user && $state.current.name != 'index'){
        //     $('#signInModal').modal();
        //     return;
        // }
        PlayerService.play(track, type,'play');
    }
    $rootScope.pauseTrack =function(track){
        PlayerService.pause(track);
    }

    $scope.submitting = false;
    // $rootScope.soundcloudConnect = false;
    $rootScope.playlistFollowScArtist = function(track, type) {

        var w = 500, h = 400;
        var left = (screen.width/2)-(w/2);
        var top = (screen.height/2)-(h/2);


        var params = [];
        $window.soundcloudApp = {};
        $window.soundcloudApp.connectCallback = function(status, token, user){

            if(status == 'success') {
                SoundcloudService.followArtist({'artist_id':track.soundcloud_user_id, 'track_type' : type ,'token' : token}, function(r) {

                    var data = {    artistId    : track.user_id ,
                                    trackId     : track.id ,
                                    provider_user_id : track.soundcloud_user_id,
                                    share_Action: 'follow',
                                    share_type  : 'soundcloud',
                                    track_type  : type
                                };
                    TrackApi.shares(null, data, function(r) {

                    });

                    SharedData._scFollowings.push(track.soundcloud_user_id);

                    if (r.success) {
                        // toastr.success('You followed this artist at soundcloud successfully');
                        $scope.addPlaylistlModal2();
                    } else if(r.error){
                        toastr.error("Error: " + r.error);
                    } else {
                        toastr.error("Something went wrong. Please try again");
                    }

                });
            }

        };

        var win = window.open ($rootScope.baseUrl+'/sc/login',"Soundcloud","menubar=1,resizable=1,width="+w+",height="+h+",top="+top+',left='+left);
        customLib.setWindowTitle(win, 'Soundcloud');
    }

    $rootScope.addPlaylistlModal1 = function(track, type) {
        $scope.list.trackId = track.campaign_id ? track.campaign_id : track.id;
        $scope.list.type = type;
        $scope.list.track = track;
        if(!track.soundcloud_user_id || SharedData._scFollowings.indexOf(track.soundcloud_user_id) != -1 || SharedData._user.slug == track.artist_slug) {
            $('#addToPlaylistModal2').modal();
        } else {
            $('#addToPlaylistModal1').modal();
        }
	};

	$scope.addPlaylistlModal2 = function() {
		$('#addToPlaylistModal1').modal('hide');
		setTimeout(function(){
		  	$('#addToPlaylistModal2').modal();
		}, 600);
	};


	$rootScope.addInPlaylist = function(list) {
		$scope.isSubmitting = true;
        $rootScope.showLoading = true;
		TrackListApi.post(list, function(data) {
			$scope.isSubmitting = false;
            $rootScope.showLoading = false;
        	if(data.newplaylist){
        		$scope._playlists.push(data.newplaylist);
        	}

            // if(list.type == 'track'){
            //     $scope._playlistTrackIds.push(list.trackId);
            // }else
            if(list.type == 'campaign'){
                $scope._playlistCampaignIds.push(list.trackId);
            }else if(list.type == 'video'){
                $scope._playlistVideoIds.push(list.trackId);
            }else{
                $scope._playlistRemixIds.push(list.trackId);
            }

            if($state.current.name == "app.artist.playlists" || $state.current.name == "app.user.playlists"){
                $rootScope.getPlaylist();
            }



        //	toastr.success('',data.success);
            $('#addToPlaylistModal2').modal('hide');
		}, function(r){
			$scope.isSubmitting = false;
            $rootScope.showLoading = false;
			if(r.data.message){
				$scope._error.msg = r.data.message;
			}
			if(r.data.playlist_name){
				$scope._error.msg = r.data.playlist_name[0];
			}
		});
	};

    $rootScope.removePlaylistTrack = function(track, type){
        TrackListApi.removePlaylistTrack({id : track.campaign_id ? track.campaign_id : track.id, type : type}, function(data) {
            // if(type == 'track'){
            //     index = $scope._playlistTrackIds.indexOf(track.id)
            //     $scope._playlistTrackIds.splice(index, 1);
            // }else
            if(type == 'campaign'){
                index1 = $scope._playlistCampaignIds.indexOf(track.campaign_id ? track.campaign_id :  track.id)
                $scope._playlistCampaignIds.splice(index1, 1);
            }else if(type == 'video'){
                index2 = $scope._playlistVideoIds.indexOf(track.id)
                $scope._playlistVideoIds.splice(index2, 1);
            }else{
                index3 = $scope._playlistRemixIds.indexOf(track.id)
                $scope._playlistRemixIds.splice(index3, 1);
            }

            if($state.current.name == "app.artist.playlists" || $state.current.name == "app.user.playlists"){
                $rootScope.getPlaylist();
            }
          //  toastr.success('',data.success);
        }, function(r){

        });
    }

	$rootScope.addInFavourites = function(track, type, index ) {
		FavouriteApi.addfavourite({id:track.campaign_id ? track.campaign_id : track.id, type: type}, function(data) {
			//toastr.success('',data.success);

            if(data.action == "add"){
                track.favourite_count += 1;;
                if(PlayerService.currentTrack && PlayerService.currentTrack.campaign_id == track.campaign_id){
                    PlayerService.currentTrack.favourite_count = track.favourite_count;
                }
                // console.log([$state.params.slug, $rootScope.user.slug])
                if($state.params.slug && $state.params.slug == $rootScope.user.slug){
                    $scope.likeCount += 1;
                    $scope.favouriteCount += 1;
                }

                // if(type == 'track'){
                //     $scope._favTrackIds.push(track.id);
                // }else
                if(type == 'campaign'){
                    $scope._favCampaignIds.push(track.campaign_id ? track.campaign_id : track.id);
                }else{
                    $scope._favRemixIds.push(track.id);
                }

                if($state.current.name == "app.artist.favourites" || $state.current.name == "app.user.favourites"){
                    $rootScope.getFavourites();
                }
                $rootScope.getActivity();
            }

            if(data.action == "remove"){
                // console.log([$state.params.slug, $rootScope.user.slug])
                track.favourite_count-= 1;
                if(PlayerService.currentTrack && PlayerService.currentTrack.campaign_id == track.campaign_id){
                    PlayerService.currentTrack.favourite_count = track.favourite_count;
                }
                // $scope.likeCount -= 1;
                if($state.params.slug && $state.params.slug == $rootScope.user.slug){
                    $scope.likeCount -= 1;
                    $scope.favouriteCount -= 1;
                }

                // if(type == 'track'){
                //     index = $scope._favTrackIds.indexOf(track.id)
                //     $scope._favTrackIds.splice(index, 1);
                // }else
                if(type == 'campaign'){
                    index1 = $scope._favCampaignIds.indexOf(track.campaign_id ? track.campaign_id : track.id)
                    $scope._favCampaignIds.splice(index1, 1);
                    // $scope._favCampaignIds.push(track.id);
                }else{
                    index2 = $scope._favRemixIds.indexOf(track.id)
                    $scope._favRemixIds.splice(index2, 1);
                    // $scope._favRemixIds.push(track.id);
                }

                if($state.current.name == "app.artist.favourites" || $state.current.name == "app.user.favourites"){
                    $rootScope.getFavourites();
                }
                $rootScope.getActivity();
            }


            angular.forEach(PlayerService.tracks,function(trackx){
                if(track.id == trackx.id){
                    trackx.favourite_count = track.favourite_count;
                }
            });

        },function (r){
            toastr.error('',r.data.error);
        });
	};


	$scope.addInVideoFavourites = function(video) {
		FavouriteApi.addvideofavourite({id:video.id}, function(data) {
			// toastr.success('',data.success);
            if(data.action == "add"){
                video.favourite_count++;

                // $scope.likeCount += 1;
                if(video.artist_slug == $rootScope.user.slug){
                    $scope.likeCount += 1;
                    $scope.favouriteCount+= 1;
                }
                $scope._favVideoIds.push(video.id);
            }
            if(data.action == "remove"){
                video.favourite_count--;

                // $scope.likeCount -= 1;
                if(video.artist_slug == $rootScope.user.slug){
                    $scope.likeCount -= 1;
                    $scope.favouriteCount-= 1;
                }
                index2 = SharedData._favVideoIds.indexOf(video.id)
                SharedData._favVideoIds.splice(index2, 1);
            }

		},function(r){
            toastr.error('',r.data.error);
        });
	};


	$(document).on("hidden.bs.modal", '#addToPlaylistModal2', function () {
		var addPlaylist=angular.element('#addToPlaylistModal2').scope();
    	if(addPlaylist){
    		addPlaylist.trackForm.$setPristine();
    	}
		$scope.list.playListId = "";
		$scope.list.playlist_name = "";
		$scope._error.msg = "";
		$scope.list.required_error = "";
	});

	$scope.toastr = function(type, message) {
		if(type == "success"){
			toastr.success(message);
		}else{
			toastr.error(message);
		}
	};


	$scope.goBack = function() {

        if(window.history.length > 1) {
            window.history.go(-1);
        } else {
            $state.go('index');
        }

        // if($rootScope.lastState){
        //     if($rootScope.lastParam.slug){
        //         $state.go($rootScope.lastState, {slug : $rootScope.lastParam.slug});
        //     }else{
        //         $state.go($rootScope.lastState);
        //     }

        // }
    }
    $scope.goBack1 = function() {

        if(window.history.length > 1) {
            window.history.go(-1);
        } else {
            $state.go('app.single_streamline', {streamlineId: 'delta-jack-drunk'});
        }

        // if($rootScope.lastState){
        //     if($rootScope.lastParam.slug){
        //         $state.go($rootScope.lastState, {slug : $rootScope.lastParam.slug});
        //     }else{
        //         $state.go($rootScope.lastState);
        //     }

        // }
    }

    $scope.goTo = function() {
        $state.go('index');
    }

    $scope.contactUs = function (user,modal) {

        $scope.isSubmitting = true;
        $rootScope.showLoading = true;
        var fd = angular.copy(user);
        fd._token = $window.globalObj._token;

        HomeApi.contact(null,fd,function(r){

            $scope.isSubmitting = false;
            $rootScope.showLoading = false;
            $('#contactForm')[0].reset();
            var contactForm=angular.element('#contactForm').scope();
            if(contactForm){
                contactForm.contact ={};
                if($rootScope.user){
                    contactForm.contact.name = $rootScope.user.name;
                    contactForm.contact.email = $rootScope.user.email;
                }
                contactForm.contactForm.$setPristine();
                contactForm.contactForm.$setUntouched();
            }
            if(modal){
                $('#contactUsModal').modal('hide');
            }
            // toastr.success('Email sent successfully. We will contact to you soon.')
        },function(r){
            $scope.isSubmitting = false;
            $rootScope.showLoading = false;
            toastr.error(r.message);
        });

        // return $http.post(API_URL +'/contactUs/send-mail', fd)
        // .success(function(r) {
        //  $scope.isSubmitting = false;
        //  $('#contactForm')[0].reset();
        //  toastr.success('Email sent successfully. We will contact to you soon.')
        // })
        // .error(function(r){
        //  $scope.isSubmitting = false;
        //  toastr.error(r.message);
        // });
    }

    $scope.deleteAccount = function(){
            $uibModal.open({
                templateUrl: 'frontweb/tpl/modals/enter-password-modal.html',
                controller: function($scope, $rootScope  ,$uibModalInstance, $timeout ,SharedData ){
                    $scope.provider = SharedData._user.provider;
                    $scope.cancel = function () {
                        $uibModalInstance.dismiss('cancel');
                    };

                    $scope.delete = function (password) {
                        $rootScope.showLoading = true;
                        $scope.error = '';
                        ArtistApi.checkPass(null,{password:password,email:SharedData._user.email}, function(r){
                            $uibModalInstance.dismiss('cancel');
                            $rootScope.showLoading = false;
                            $scope.isSubmitting = false;
                            swal({
                                title: "Are you sure, you want to deactivate your account ?",
                                // type: "info",
                                showCancelButton: true,
                                closeOnConfirm: true,
                                showLoaderOnConfirm: false,
                            }, function(){
                                $rootScope.showLoading = true;
                                $scope.isSubmitting = true;
                                ArtistApi.delete(null,{password:password,email:SharedData._user.email}, function(r){
                                    $rootScope.showLoading = false;
                                    $window.location.href = "/";
                                    $scope.isSubmitting = false;
                                }, function(r){
                                    $rootScope.showLoading = false;
                                    $scope.isSubmitting = false;
                                    if(r.data.status == 'incorrect'){
                                        $scope.error = r.data.message;
                                    }
                                });

                            })
                        }, function(r){
                            $rootScope.showLoading = false;
                            $scope.isSubmitting = false;
                            if(r.data.status == 'incorrect'){
                                $scope.error = r.data.message;
                            }
                        });
                    };
                },
                resolve: {
                    SharedData: function () {
                        return SharedData;
                    },
                }
            });
        // }
        return false;

        // if(confirm('Are you sure, do you want to delete your account permanently?')) {
        //     ArtistApi.delete(function(r){
        //         $window.location.href = "/";
        //     }, function(r){
        //         toastr.error('Something went wrong.Please try again.');
        //     });
        // }
    }

    $scope.emailUpdate = function(){
        if($scope._user.provider == 'facebook'){
            return false;
        }
        $uibModal.open({
            templateUrl: 'frontweb/tpl/modals/change-email-modal.html',
            controller: function($scope, $rootScope  ,$uibModalInstance, $timeout ,SharedData ){
                $scope.provider = SharedData._user.provider;
                $scope.cancel = function () {
                    $uibModalInstance.dismiss('cancel');
                };

                $scope.update = function(email,password){
                    $rootScope.showLoading = true;
                    $scope.auth_error = '';
                    $scope.email_error = '';
                    $scope.isSubmitting = true;
                    ArtistApi.updateEmail(null,{email:email,password:password}, function(r){
                        $rootScope.showLoading = false;
                        $scope.isSubmitting = false;
                        $scope.email_error = '';
                        if(r.status == 'success'){
                            $uibModalInstance.dismiss('cancel');
                            swal("Change email activation link has been sent to your email address.");
                       //     toastr.success('Please check your email to confirm.');
                            // SharedData._user.email = email;
                        }
                    }, function(r){
                        $rootScope.showLoading = false;
                        $scope.auth_error = r.data.message;
                        $scope.email_error = r.data;
                        $scope.isSubmitting = false;
                        // toastr.error(r.error);
                    });
                }
            },
            resolve: {
                SharedData: function () {
                    return SharedData;
                },
            }
        });
        return false;
    }

    $scope.getCampaigns = function(){
        $scope.campaigns = [];
        ArtistApi.getCampaign(null, function(r) {
            $scope.campaigns = r.data;
            $scope.scTracks = r.scTracks;
        });
    }

    $scope.getSoundcloudRemix = function() {
        $scope.loading = true;
        SoundCloudApi.getRemix(null, function(data) {
            $scope.loading = false;
            $scope.soundcloudRemix = data.soundCloudRemix;
        },function(r){
            $scope.loading = false;
            toastr.error(r.data.message);
        });
    };
    $scope.getSoundcloudTracks = function() {
        $scope.loading = true;
        SoundCloudApi.get(null, function(r) {
            $scope.loading = false;
            $scope.scTracks = r.soundCloudTracks;
        },function(r){
            $scope.loading = false;
            toastr.error(r.data.message);
        });
    };

    $scope.closeModal = function(type){
        $(type).modal('hide');
        $state.go('app.subscriptions');
    }
    $scope.submitDemo = function (track,type) {

        if(type == 'discover' && SharedData._currentPlanDetails.plan.discover_demo_limit - SharedData._currentPlanDetails.demoCount <= 0 ){
            return false;
        }
        if(type == 'remix' && SharedData._currentPlanDetails.plan.remix_demo_limit - SharedData._currentPlanDetails.remixCount <= 0 ){
            return false;
        }
        if(type == 'video' && SharedData._currentPlanDetails.plan.video_demo_limit - SharedData._currentPlanDetails.videoCount <= 0 ){
            return false;
        }
        var parentScope=angular.element('#dashboard_ctrl').scope();
        $scope.isSubmitting = true;
        $rootScope.showLoading = true;
        var data = ArtistApi.submitDemos(null, { 'slug' : track.slug , 'type': type,'_token' : $window.globalObj._token,'source':'local'},  function(r){
            $scope.isSubmitting = false;
            $rootScope.showLoading = false;
            if(angular.isDefined(parentScope)){
                parentScope.demoTracks.unshift(r.data);
            }
            if(type == 'discover'){
                SharedData._currentPlanDetails.demoCount = SharedData._currentPlanDetails.demoCount + 1;
            }
            if(type == 'remix'){
                SharedData._currentPlanDetails.remixCount = SharedData._currentPlanDetails.remixCount + 1;
            }
            if(type == 'video'){
                SharedData._currentPlanDetails.videoCount = SharedData._currentPlanDetails.videoCount + 1;
            }


            $('#submitYourDemoModal').modal('hide');
            // toastr.success('Your demo track sent successfully.');
        }, function(r) {
            $scope.isSubmitting = false;
            $rootScope.showLoading = false;
        });
    }

    $scope.reset = function(){
        var submitDemo=angular.element('#submitYourDemoModal').scope();
        submitDemo.track='';
        $('#submitYourDemoModal input[type=checkbox]').attr('checked',false);
        submitDemo.termCheck = submitDemo.demoTermCheck = submitDemo.demoTermCheck1 = submitDemo.demoTermCheck2 = submitDemo.demoTermCheck3 = submitDemo.demoTermCheck4 = submitDemo.demoTermCheck5 = submitDemo.demoTermCheck6 = 0 ;
        if(submitDemo){
            // $("#submitDemoForm")[0].reset();
            submitDemo.demo={};
            if(submitDemo.submitDemoForm){
                submitDemo.submitDemoForm.$setPristine();
                submitDemo.submitDemoForm.$setUntouched();
            }
            if(submitDemo.DemoForm){
                submitDemo.DemoForm.$setPristine();
                submitDemo.DemoForm.$setUntouched();
            }
            if(submitDemo.submitVideoForm){
                submitDemo.submitVideoForm.$setPristine();
                submitDemo.submitVideoForm.$setUntouched();
            }
        }
    }

    setInterval(function(){
        if(SharedData._user)
            $scope.getNotifications();
    }, 60000);


    $scope.getNotifications = function(){
        ArtistApi.getNotification(null, function(r) {
            $scope.notifications = SharedData.notifications = r.data;
            $scope.notifications_count = SharedData.notifications_count = r.notifications_count;
        });
    }

    $scope.removeNotifications = function(notification){
        ArtistApi.removeNotification(null,{ 'id' : notification.id },function(r){
            $scope.notifications.splice($scope.notifications.indexOf(notification), 1);
        });
    }
    $scope.removeNotificationCount = function(){
        if($scope.notifications_count > 0){
            ArtistApi.removeNotificationsCount(null,null,function(r){
                $scope.notifications_count = SharedData.notifications_count = 0;
            });
        }
    }

    $scope.submitted = true;
    $scope.submitTrackDemo = function (track) {
        track.token = $window.globalObj._token;
        $scope.isSubmitting = true;
        $rootScope.showLoading = true;
        var data = ArtistApi.submitTrackDemos(null, track,  function(r){
            $scope.isSubmitting = false;
            $rootScope.showLoading = false;
            $scope._user.isExist = SharedData._user.isExist = '1';
            if($scope._user.isArtist =='0'){
                $scope._user.isArtist = SharedData._user.isArtist = '1';
            }
            $scope.submitted = false;
            $('#submitDemoModal').modal('hide');
            swal('Thank you for submitting your demo, we will be in touch with an answer very soon!');
        }, function(r) {
            $scope.isSubmitting = false;
            $rootScope.showLoading = false;
        });
    }

    $rootScope.getActivity = function(){
        ArtistApi.getActivities(null, function(r) {
            if($rootScope.artist && SharedData._user.id == $rootScope.artist.user_id){
                var activities = angular.element('#activity-feed').scope();
                if (r.data && typeof activities !== 'undefined') {
                    activities.$parent.activities = r.data;
                }
            }
        });
    }

    $rootScope.getFollowing = function(){
        ArtistApi.getFollowings(null, function(r) {
            if($rootScope.artist && SharedData._user.id == $rootScope.artist.user_id){
                var followings = angular.element('#following').scope();
                if (r.data && typeof followings !== 'undefined') {
                    followings.$parent.followers = r.data;
                }
            }
        });
    }


    $rootScope.showPopup = function(msg){
        swal(msg);
    }
    $rootScope.showPopup1 = function(msg){
        swal({
            title :"You have reached your limit for Streamline pages, please upgrade your subscription !",
            showCancelButton: true,
            confirmButtonColor: "green !important",confirmButtonText: "UPGRADE",
            cancelButtonText: "OK",
            closeOnConfirm: false,
            closeOnCancel: false },
            function(isConfirm){
               if (isConfirm) {
                  $window.location.href = $state.href('app.subscriptions');
               } else{
                $window.location.href = $state.href('app.artist.dashboard');
               }
        });
    }
    /*$rootScope.showPopup1 = function(subscription_id){
        swal({
                title :"You have reached your limit for Streamline pages, please upgrade your subscription !",
                html: true,
                showOKButton: true,
                okOnConfirm: true,
                showLoaderOnConfirm: false
            }, function(){
                $rootScope.showLoading = true;
                SubscriptionApi.undoSubscription({subscription_id : subscription_id}, function(data) {
                    $rootScope.showLoading = false;
                    $window.location.href = $state.href('app.subscriptions');
                });
        })
    }*/

    $rootScope.formatDate = function(date){
        if(date &&  date != '0000-00-00') {
          return moment(date).toDate();;
        }
    };
    $scope.undoDowngrade = function(subscription_id) {
        swal({
                title :"By clicking 'OK' you will revert back to your current plan!",
                html: true,
                showCancelButton: true,
                closeOnConfirm: true,
                showLoaderOnConfirm: false
            }, function(){
                $rootScope.showLoading = true;
                SubscriptionApi.undoSubscription({subscription_id : subscription_id}, function(data) {
                    $rootScope.showLoading = false;
                    $window.location.href = $state.href('app.subscriptions');
                });
        })
    };

    $scope.cancelSubscriptions = function(subscription_id) {
        swal({
                title :'Are you sure you want to cancel your subscription?',
                text: "You are currently price locked, if you cancel now you will lose out on your current deal!<br>(prices will increase over time as the platform grows)",
                // type: "",
                html: true,
                showCancelButton: true,
                closeOnConfirm: true,
                showLoaderOnConfirm: false
            }, function(){
                $rootScope.showLoading = true;
                SubscriptionApi.cancelSubscription({subscription_id : subscription_id}, function(data) {
                    $scope._currentPlanDetails.plan.isCancel = 1;
                // toastr.success('',data.success);
                    $rootScope.showLoading = false;
                    $window.location.href = $state.href('app.subscriptions');
                });
        })
    };

    $scope.resumeSubscriptions = function() {
        swal({
                title :'Are you sure you want to resume your subscription?',
                // text: "You are currently price locked, if you cancel now you will lose out on your current deal.<br>(prices will increase over time as the platform grows)",
                // type: "",
                html: true,
                showCancelButton: true,
                closeOnConfirm: true,
                showLoaderOnConfirm: false
            }, function(){
                $rootScope.showLoading = true;
                SubscriptionApi.resumeSubscription(null, function(data) {
                // $scope._currentPlanDetails.plan.isCancel = 1;
                // toastr.success('',data.success);
                $rootScope.showLoading = false;
                $window.location.href = $state.href('app.subscriptions');
                });
        })
    };
});