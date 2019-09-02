angular.module('app.controllers')
.controller('HomeCtrl', function($scope, $http , $window,$state, $stateParams, $timeout,API_URL,FAQApi, $rootScope, HomeApi, PlayerService , $filter) {

	$('body').attr('class','homepage');
	$scope.numRecords = 2;
    $scope.page = 1;
    $scope.totalPages = 1;
    $scope.allFAQ = [];



	$scope.$on('$viewContentLoaded', function (event, toState) {
        if($stateParams.resetCode) {
        	$timeout(function() {$('#resetPasswordModal').modal('show');}, 1000);
		}
		if($state.current.name=="facebook-redirect"){
			$timeout(function() {$('#selectUserType').modal('show');}, 1000);
		}
    });

	$scope.getFAQ = function () {
		// $scope.allFAQ = [];
		// FAQApi.get(null, function(r) {
		// 	 $scope.allFAQ = r.data;
		// });
	}

	// $scope.contactUs = function (user) {
	// 	 $scope.isSubmitting = true;
 //  		var fd = angular.copy(user);
	// 	fd._token = $window.globalObj._token;
	//     return $http.post(API_URL +'/contactUs/send-mail', fd)
	//     .success(function(r) {
	//     	 $scope.isSubmitting = false;
	//     	$('#contactForm')[0].reset();
	//     	toastr.success('Email sent successfully. We will contact to you soon.')
	//     })
	//     .error(function(r){
	//     	 $scope.isSubmitting = false;
	//     	toastr.error(r.message)
	//     });
	// }

	$rootScope.playTrack =function(track){
        PlayerService.play(track);
    }

    $rootScope.pauseTrack =function(track){
        PlayerService.pause(track);
    }

    $scope.getDefaultPageData = function() {
		HomeApi.HomePageData(null, function(r) {
			$scope.newsBlog = r.news;
			$scope.spotlightVideo = r.spotlightVideo;
			$scope.spotlighTracks = r.spotlighTracks;
			$rootScope.featuredTracks = r.spotlighTracks;
			$scope.artistCount = r.artistCount;
			$scope.userCount = r.userCount;
            $scope.globalPlays = r.globalPlays;
            $scope.tracksDownloaded = r.tracksDownloaded;
            $scope.tweets = r.tweets;
            $scope.allFAQ = r.faqs;

            $scope.allTweets = [];
			while ($scope.tweets.length) {
				// $scope.tweets.text = $filter('linky')($scope.tweets.text);
			    $scope.allTweets.push($scope.tweets.splice(0, 2))
			}

            count = $scope.tweets ? $scope.tweets.length : 0;
            if(count){
            	$scope.totalPages = Math.ceil(count/2);
            }
            PlayerService.tracks = $scope.spotlighTracks;
		});
	};

	$scope.next = function(){
		$scope.page = $scope.page + 1;
    };

    $scope.back = function(){
        $scope.page = $scope.page - 1;
    };

    // $scope.changeFormat = function(date){
    //     if(date && date != '0000-00-00') {
    //         var dateOut = moment(new Date(date));
    //         return dateOut;
    //     }
    // };

});