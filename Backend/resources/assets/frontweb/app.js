'use strict';
angular.module('app',[
    'ngAnimate',
    'ui.bootstrap',
    'angularMoment',
    'ngLoadingSpinner',
    // 'oc.lazyLoad',
    'infinite-scroll',
    'angularjs-dropdown-multiselect',
    'app.config',
    'app.controllers',
    'app.routes',
    'app.directives',
    'app.services',
    'app.filters',
    'checklist-model',
    'puigcerber.countryPicker',
    'ngSanitize',
    'chart.js',
    'ui.select',
    'counter',
    'hm.readmore',
    'dibari.angular-ellipsis'
])
.constant('API_URL', 'frontapi')
.config(function($compileProvider){
  $compileProvider.aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|file|javascript):/);
})
.run(function($rootScope, $state,  $http, $window, $rootScope ,$timeout,SharedData) {
    $rootScope.globalObj = $window.globalObj;
    $rootScope.currentDate = new Date();
    $rootScope.baseUrl = $window.globalObj.baseUrl;
    $rootScope.teamthumbUrl = $window.globalObj.baseUrl + '/timthumb.php';
    $rootScope.noPlayerStates = ['app.single_track', 'app.single_campaign',
        'app.single_video', /*'app.recommended_tracks', 'app.recommended_campaigns'*/];

    $rootScope.$on('$stateChangeStart', function (event, toState, toParams, fromState, fromParams) {

        var toPage = toState.page;
        var fromPage = fromState.page;
        if(!(toPage && fromPage && fromPage == toPage)) {
            $('.animsition').removeClass('visible');
            $('.animsition-loading').show();
            $rootScope.stateloading = true;

            // $('.parallax-slider').hide();
            // $('.parallax-mirror img').each(function(){
            //     if($('.parallax-window[value="'+this.src+'"]').length == 0){
            //         $(this).closest('.parallax-mirror').remove();
            //     }
            // });
        }
        $rootScope.pagebackground = '';

        if($window.globalObj.user_type) {

                if($window.globalObj.user_type == 'artist' && toState.page == 'user-personal') {
                    event.preventDefault();
                    $state.go('index');
                } else if($window.globalObj.user_type == 'stm_user' && toState.page == 'artist-personal') {
                    event.preventDefault();
                    $state.go('index');
                } else {
                }
        }
        else {
            var noAuthStates = ["index", 'facebook-redirect', 'resetpassword',
                'app.single_track', 'app.single_campaign', 'app.single_video', 'app.single_streamline',
                'app.subscriptions', 'app.recommended_tracks', 'app.recommended_campaigns',
                'app.page.terms', 'app.page.privacy', 'signin', 'signup', 'forgotpwd',
                'app.page.dashboard', 'app.page.discover', 'app.page.music_videos',
                'app.page.remix_competitions', 'app.artist.tracks', 'app.page.stm_news',
                'app.artist.favourites', 'app.artist.followings', 'app.artist.fans',
                'app.page.stm_news_single'
            ];

            if(noAuthStates.indexOf(toState.name) == -1 ){ //&& toState.name.match(/page./g) == null
                event.preventDefault();
                $window.location.href = "/";
            }
        }
    });

    $rootScope.$on('$stateNotFound ', function (event, toState) {
        event.preventDefault();
        $state.go('index');
    });

    $rootScope.$on('$stateChangeSuccess', function (event, toState, toParams, from, fromParams) {
        $rootScope.pageStatee = angular.copy(toState);
        $rootScope._favTrackIds = SharedData._favTrackIds;
        $rootScope._favCampaignIds = SharedData._favCampaignIds;
        $rootScope._favRemixIds = SharedData._favRemixIds;

        $rootScope._playlistCampaignIds = SharedData._playlistCampaignIds;
        $rootScope._playlistTrackIds = SharedData._playlistTrackIds;
        $rootScope._playlistRemixIds = SharedData._playlistRemixIds;

        $rootScope.commomnImage = SharedData._defaults;
        $rootScope.lastState = from.name;
        $rootScope.lastParam = fromParams;
        // $window.scrollTo(0, 0);
        // if(toState.name == 'app.artist.tracks'){
        //     new WOW().init();
        // }
        if($rootScope.httpReferrer){
            $rootScope.httpReferrer = 'https://sorethumbmedia.co.uk';
        } else {
            $rootScope.httpReferrer = document.referrer+'/';
        }
        // console.log($rootScope.httpReferrer);
    });

    $rootScope.$on('$viewContentLoaded', function (event, toState) {
        if($rootScope.stateloading ) {
            $timeout(function() {
                $('.animsition-loading').hide();
                // new WOW().init();
                $timeout(function() {
                    $('.animsition').addClass('visible');
                    // $('.parallax-mirror').css('opacity', '1');
                }, 100);
            },500);
            $rootScope.stateloading = false;
            // $rootScope.showWelcomePopup();
            // $('.parallax-slider').show();
        }
        jqueryViewReady();
        $( window ).resize();
    });

    $rootScope.cropimage = function(url , w , h) {
        if(!url) {
            // url = $scope._defaults ? $scope._defaults.default_track_image : '';
            return;
        }

        var baseUrl = $rootScope.baseUrl;

        if(url.search(baseUrl) == -1 && url.search('http://') == -1 && url.search('https://') == -1) {
            url = baseUrl + '/' + url;
        }

        var cropUrl = $rootScope.teamthumbUrl + '?src='+url+'&a=t';


        if(w) {
            cropUrl += '&w='+w;
        }

        if(h) {
            cropUrl += '&h='+h;
        }

        cropUrl += '&q=100';

        return cropUrl;
    }


    $rootScope.cropimageTemplate = function(url , w , h) {
        if(!url) {
            url = $scope._defaults ? $scope._defaults.default_track_image : '';
        }

        var baseUrl = $rootScope.baseUrl;

        if(url.search(baseUrl) == -1 && url.search('http://') == -1 && url.search('https://') == -1) {
            url = baseUrl + '/' + url;
        }

        var cropUrl = $rootScope.teamthumbUrl + '?src='+url+'&a=t';

        if(w) {
            cropUrl += '&w='+w;
        }

        if(h) {
            cropUrl += '&h='+h;
        }

        cropUrl += '&q=100';

        return cropUrl;
    }

    $rootScope.cropimageTemplateBanner = function(url , w , h) {
        if(!url) {
            url = $scope._defaults ? $scope._defaults.default_track_image : '';
        }

        var baseUrl = $rootScope.baseUrl;

        if(url.search(baseUrl) == -1 && url.search('http://') == -1 && url.search('https://') == -1) {
            url = baseUrl + '/' + url;
        }

        var cropUrl = $rootScope.teamthumbUrl + '?src='+url;

        if(w) {
            cropUrl += '&w='+w;
        }

        if(h) {
            cropUrl += '&h='+h;
        }

        cropUrl += '&q=100';

        return cropUrl;
    }



});



