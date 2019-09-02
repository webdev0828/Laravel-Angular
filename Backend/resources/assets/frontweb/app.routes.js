'use strict';

angular.module('app.routes', ['ui.router'])

.config(function ($stateProvider, $urlRouterProvider, $locationProvider, $anchorScrollProvider, $uiViewScrollProvider){

    $locationProvider.html5Mode(true);

	// $urlRouterProvider.otherwise('/');

	$urlRouterProvider.otherwise( function($injector, $location) {
        var $state = $injector.get("$state");
        $state.go("index");
    });

    var appPath = 'frontweb';

    $stateProvider
      	.state('index', {
          	url: '/',
          	templateUrl: appPath+'/tpl/default.html',
          	controller: 'HomeCtrl',
            resolve: { // Any property in resolve should return a promise and is executed before the view is loaded
                LoadData: function($q, SharedData, GlobalApi, $rootScope){
                    var data = {};
                    var deferred = $q.defer();

                    GlobalApi.getSTMdata(function(r){
                        SharedData._user =  r.user;
                        SharedData._plans =  r.plans;
                        SharedData._playlists =   r.playlists;
                        SharedData._favTrackIds =   r.favouriteTrackIds;
                        SharedData._favCampaignIds =   r.favouriteCampaignIds;
                        SharedData._favVideoIds =   r.favouriteVideoIds;
                        SharedData._favRemixIds =   r.favouriteRemixIds;

                        SharedData._playlistCampaignIds =   r.playlistCampaignIds;
                        SharedData._playlistTrackIds =   r.playlistTrackIds;
                        SharedData._playlistVideoIds =   r.playlistVideoIds;
                        SharedData._playlistRemixIds =   r.playlistRemixIds;

                        SharedData._genres =   r.genres;
                        SharedData._genresAll =   r.allGenres;
                        SharedData._moods =   r.moods;
                        SharedData._followingTo = r.followingTo;
                        SharedData._stripeKey = r.stripeKey;
                        SharedData._oauthProviders = r.oauthProviders;
                        SharedData._oauthProviderUsers = r.oauthProviderUsers;
                        SharedData._oauthConfig = r.oauthConfig;
                        SharedData._oauthProviderTokens = r.oauthProviderTokens;
                        SharedData._defaults = r.defaults;
                        SharedData._banners = r.banners;
                        SharedData._scFollowings = r.scfollowings;
                        SharedData.notifications = r.notifications;
                        SharedData.notifications_count = r.notifications_count;
                        // SharedData._demoTrackIds = r.demoTrackIds;

                        $rootScope.user = r.user;
                        SharedData._currentPlanDetails = r.currentPlan;

                        if($rootScope.user && window.globalObj.app_env == 'production') {
                            // hideMessenger();
                            var currentRole = $rootScope.user.user_type;
                            if(currentRole =="artist"){
                                currentRole ="ARTIST";
                            }
                             if(currentRole =="stm_user"){
                                currentRole ="USER";
                            }


                            window.intercomSettings = {
                                app_id: window.globalObj.intercom_id,
                                name: $rootScope.user.name, // Full name
                                email: $rootScope.user.email, // Email address
                                 type: currentRole,
                                created_at: moment($rootScope.user.created_at, 'YYYY-MM-DD HH:mm:ss').unix(), // Signup date as a Unix timestamp
                                hide_default_launcher: true
                            };

                            (function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/q6vjz6oa';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);l();}}})();
                        }
                        deferred.resolve(true);
                    });

                   return deferred.promise;
                }
            }
      	})
        .state('signin', {
            url: '/login',
            category: 'auth',
            templateUrl: appPath+'/tpl/pages/signin.html',
            controller: 'AuthCtrl'
        })
        .state('signup', {
            url: '/register',
            category: 'auth',
            templateUrl: appPath+'/tpl/pages/signup.html',
            controller: 'AuthCtrl'
        })
        .state('forgotpwd', {
            url: '/forgot-password',
            category: 'auth',
            templateUrl: appPath+'/tpl/pages/forgot-password.html',
            controller: 'AuthCtrl'
        })
        .state('resetpassword', {
          	url: '/reset-password/{resetCode}',
          	templateUrl: appPath+'/tpl/default.html',
          	controller: 'HomeCtrl'
      	})

      	.state('facebook-redirect', {
          	url: '/facebook-redirect',
          	templateUrl: appPath+'/tpl/default.html',
          	controller: 'HomeCtrl'
      	})

        .state('sorethumb', {
            url: '/sorethumb',
            templateUrl: 'sorethumb/index.html'
        })

  	 	.state('app', {
            abstract:true,
            url: '',
            views: {
                '@':{
                    templateUrl: appPath+'/tpl/app.html',
                    controller: 'AppCtrl'
                }
            },
            resolve: { // Any property in resolve should return a promise and is executed before the view is loaded
                LoadData: function($q, SharedData, GlobalApi, $rootScope){
                    var data = {};
                    var deferred = $q.defer();

                    GlobalApi.getSTMdata(function(r){
                        SharedData._user =  r.user;
                        SharedData._plans =  r.plans;
                        SharedData._playlists =   r.playlists;
                        SharedData._favTrackIds =   r.favouriteTrackIds;
                        SharedData._favCampaignIds =   r.favouriteCampaignIds;
                        SharedData._favVideoIds =   r.favouriteVideoIds;
                        SharedData._favRemixIds =   r.favouriteRemixIds;

                        SharedData._playlistCampaignIds =   r.playlistCampaignIds;
                        SharedData._playlistTrackIds =   r.playlistTrackIds;
                        SharedData._playlistVideoIds =   r.playlistVideoIds;
                        SharedData._playlistRemixIds =   r.playlistRemixIds;

                        SharedData._genres =   r.genres;
                        SharedData._genresAll =   r.allGenres;
                        SharedData._moods =   r.moods;
                        SharedData._followingTo = r.followingTo;
                        SharedData._stripeKey = r.stripeKey;
                        SharedData._oauthProviders = r.oauthProviders;
                        SharedData._oauthProviderUsers = r.oauthProviderUsers;
                        SharedData._oauthConfig = r.oauthConfig;
                        SharedData._oauthProviderTokens = r.oauthProviderTokens;
                        SharedData._defaults = r.defaults;
                        SharedData._banners = r.banners;
                        SharedData._scFollowings = r.scfollowings;
                        // SharedData._demoTrackIds = r.demoTrackIds;
                        SharedData.notifications = r.notifications;
                        SharedData.notifications_count = r.notifications_count;

                        $rootScope.user = r.user;

                        if($rootScope.user && window.globalObj.app_env == 'production') {
                            var currentRole = $rootScope.user.user_type;
                            if(currentRole =="artist"){
                                currentRole ="ARTIST";
                            }
                            if(currentRole =="stm_user"){
                                 currentRole ="USER";
                            }
                            window.intercomSettings = {
                                app_id: window.globalObj.intercom_id,
                                name: $rootScope.user.name, // Full name
                                email: $rootScope.user.email, // Email address
                                type:currentRole,
                                created_at: moment($rootScope.user.created_at, 'YYYY-MM-DD HH:mm:ss').unix() // Signup date as a Unix timestamp
                            };

                            (function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/q6vjz6oa';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);l();}}})();
                        }
                        SharedData._currentPlanDetails = r.currentPlan;
                        deferred.resolve(true);
                    });

                   return deferred.promise;
                }
            }
        })
  	 	/* USER ROUTES */
        .state('app.user', {
            url: '/user',
            templateUrl: appPath+'/tpl/user/layout.html',
            controller: 'UserCtrl'
        })

        .state('app.user.profile', {
            cache: false,
            url: '/profile',
            page : 'user-personal',
            templateUrl: appPath+'/tpl/user/pages/profile.html'
        })

        .state('app.user.favourites', {
        	url: '/{slug}/favourites',
        	page : 'user-public',
			templateUrl: appPath+'/tpl/user/pages/favourites.html',
			controller: 'FavouriteCtrl'
		})

		.state('app.user.playlists', {
        	url: '/{slug}/playlists',
        	page : 'user-public',
			templateUrl: appPath+'/tpl/user/pages/playlists.html',
			controller: 'PlaylistCtrl'
		})

		.state('app.user.activity', {
        	url: '/{slug}/activity',
        	page : 'user-public',
			templateUrl: appPath+'/tpl/user/pages/activities.html',
			controller: ''
		})

		.state('app.user.followings', {
        	url: '/{slug}/followings',
        	page : 'user-public',
			templateUrl: appPath+'/tpl/user/pages/followings.html',
			controller: 'FollowCtrl'
		})

		/* ARTIST USER ROUTES */
		.state('app.artist', {
			url: '',
			templateUrl: appPath+'/tpl/artist/layout.html',
            controller: 'ArtistCtrl'
        })

        .state('app.artist.dashboard', {
        	url: '/dashboard',  // dashboard
        	page : 'artist-public',
			templateUrl: appPath+'/tpl/artist/pages/dashboard.html',
			controller: 'DashboardCtrl'
		})

		.state('app.artist.profile', {
        	url: '/profile?donate', // profile
        	page : 'artist-public',
			templateUrl: appPath+'/tpl/artist/pages/profile.html',
			controller: 'ProfileCtrl'
		})

		.state('app.artist.plans', {
        	url: '/plans',
        	page : 'artist-public',
			templateUrl: appPath+'/tpl/artist/pages/plans.html',
			controller: 'ArtistCtrl'
		})

		.state('app.artist.everything', {
        	url: '/{slug}/everything',
        	page : 'artist-public',
			templateUrl: appPath+'/tpl/artist/pages/everything.html',
			controller: 'EverythingCtrl'
		})

		.state('app.artist.tracks', {
        	url: '/{slug}/tracks',
        	page : 'artist-public',
			templateUrl: appPath+'/tpl/artist/pages/tracks.html',
			controller: 'DiscoverTrackCtrl'
		})

		.state('app.artist.videos', {
        	url: '/{slug}/videos',
        	page : 'artist-public',
			templateUrl: appPath+'/tpl/artist/pages/videos.html',
			controller: 'VideoTrackCtrl'
		})

		.state('app.artist.favourites', {
        	url: '/{slug}/favourites',
        	page : 'artist-public',
			templateUrl: appPath+'/tpl/artist/pages/favourites.html',
			controller: 'FavouriteCtrl'
		})

        .state('app.artist.followings', {
            url: '/{slug}/followings',
            page : 'artist-public',
            templateUrl: appPath+'/tpl/artist/pages/followings.html',
            controller: 'FollowCtrl'
        })

        .state('app.artist.fans', {
            url: '/{slug}/fans',
            page : 'artist-public',
            templateUrl: appPath+'/tpl/artist/pages/fans.html',
            controller: 'FollowCtrl'
        })

		.state('app.artist.playlists', {
        	url: '/{slug}/playlists',
        	page : 'artist-public',
			templateUrl: appPath+'/tpl/artist/pages/playlists.html',
			controller: 'PlaylistCtrl'
		})

		.state('app.artist.remix', {
        	url: '/{slug}/remix',
        	page : 'artist-public',
			templateUrl: appPath+'/tpl/artist/pages/remix.html',
			controller: 'RemixCtrl'
		})

		.state('app.artist.activity', {
        	url: '/{slug}/activity',
        	page : 'artist-public',
			templateUrl: appPath+'/tpl/artist/pages/activities.html',
			controller: ''
		})

		.state('app.page', {
			abstract:true,
            url: '',
            templateUrl: appPath+'/tpl/pages/layout.html'
        })

		.state('app.page.activities', {
        	url: '/activities',
			templateUrl: appPath+'/tpl/pages/activities.html',
			controller: 'PageActivityCtrl'
		})

		.state('app.page.dashboard', {
        	url: '/home',
			templateUrl: appPath+'/tpl/pages/dashboard.html',
			controller: 'PageCtrl'
		})

		.state('app.page.discover', {
        	url: '/discover?genre?mood',
			templateUrl: appPath+'/tpl/pages/discover.html',
			controller: 'PageDiscoverCtrl'
		})

		.state('app.page.music_videos', {
        	url: '/music-videos',
			templateUrl: appPath+'/tpl/pages/music-videos.html',
			controller: 'PageVideosCtrl'
		})

		.state('app.page.remix_competitions', {
        	url: '/remix-competitions',
			templateUrl: appPath+'/tpl/pages/remix-competitions.html',
			controller: 'PageCompetitionCtrl'
		})

        .state('app.page.past_remix_competition', {
            url: '/remix-competition/{slug}',
            templateUrl: appPath+'/tpl/pages/past-remix-competition.html',
            controller: 'PagePastCompetitionCtrl'
        })

		.state('app.page.remix', {
        	url: '/remix',
			templateUrl: appPath+'/tpl/pages/remix.html',
			controller: 'PageRemixCtrl'
		})

		// .state('app.recommended_tracks', {
  //       	url: '/recommended/{type}/{trackId}',
		// 	templateUrl: appPath+'/tpl/pages/recommended-tracks.html',
		// 	controller: 'RecommendedTrackCtrl'
		// })

        .state('app.recommended_campaigns', {
            url: '/recommended/{type}/{trackId}',
            templateUrl: appPath+'/tpl/pages/recommended-campaigns.html',
            controller: 'RecommendedCampaignCtrl'
        })

		.state('app.single_video', {
        	url: '/video/{trackId}',
			templateUrl: appPath+'/tpl/pages/single-campaign.html',
			controller: 'SingleVideoCtrl'
		})

		.state('app.single_track', {
        	url: '/track/{trackId}',
			templateUrl: appPath+'/tpl/pages/single-campaign.html',
			controller: 'SingleCampaignCtrl'
        })

        .state('app.single_campaign', {
            url: '/campaign/{trackId}',
            templateUrl: appPath+'/tpl/pages/single-campaign.html',
            controller: 'SingleCampaignCtrl'
        })

        .state('app.single_streamline', {
            url: '/streamline/{streamlineId}',
            templateUrl: appPath+'/tpl/pages/single-streamline.html',
            controller: 'SingleStreamlineCtrl'
        })

		.state('app.page.stm_news', {
        	url: '/news?q',
			templateUrl: appPath+'/tpl/pages/stm-news.html',
			controller: 'PageNewsCtrl'
		})

		.state('app.page.stm_news_single', {
        	url: '/news/{slug}',
			templateUrl: appPath+'/tpl/pages/stm-news-single.html',
			controller: 'PageSingleNewsCtrl'
		})

		.state('app.subscriptions', {
        	url: '/subscriptions',
			templateUrl: appPath+'/tpl/pages/subscriptions.html',
			controller: 'SubscriptionCtrl'
		})
        .state('app.page.privacy', {
            url: '/privacy',
            templateUrl: appPath+'/tpl/pages/privacy.html',
            controller: 'PageSingleNewsCtrl'
        })

        .state('app.page.terms', {
            url: '/terms',
            templateUrl: appPath+'/tpl/pages/term-and-condition.html',
            controller: 'PageSingleNewsCtrl'
        });

});
