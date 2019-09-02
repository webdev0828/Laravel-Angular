angular.module('app.controllers')
.controller('PageCompetitionCtrl', function($scope, $rootScope, $http, CompetitionApi, PlayerService, SoundCloudApi ,SharedData) {
	$('body').attr('class','page');
	$scope.participated = 0;
	window.scrollTo(0, 0);
    $scope.page = 0;
    $scope.lastpage = 1;
    $scope.loading = false;
    $scope.limit = 4 ;

    $scope.initloading = true ;
	CompetitionApi.getCompetitionPageData(null,function(r){
		$scope.currentCompetition = r.data.currentCompetition;
		$scope.pastCompetition = r.data.pastCompetition;
		$scope.latestWinner = r.data.latestWinner;
		$scope.soundCloudTracks = r.soundCloudTracks;
		$scope.participated = r.participated;
        $scope.runnersUp = r.runnerUp.data;

        $scope.page = r.runnerUp.current_page;
        $scope.lastpage =r.runnerUp.last_page;

        PlayerService.tracks = $scope.runnersUp;
        $scope.initloading = false ;
	}, function(error) {
       $scope.initloading = false;
    });

    $scope.getSoundcloudRemixTracks = function() {
        $scope.loading = true;
        SoundCloudApi.getRemix(null, function(data) {
            $scope.loading = false;
            $scope.soundCloudRemixTracks = data.soundCloudRemix;
        },function(r){
            $scope.loading = false;
            if(r.data)
                toastr.error(r.data.message);
        });
    };

    $scope.loadMoreRunnerUp = function (){

        if(!$scope.initloading && !$scope.loading && ($scope.page !=  $scope.lastpage) && ($scope.page <=  $scope.lastpage)){
            $scope.loading = true;
            CompetitionApi.getMoreRunnerUp({page: $scope.page+1 , limit : $scope.limit}, null, function(r) {
                for(i in r.runnerUp.data) {
                    var v = r.runnerUp.data[i];
                    $scope.runnersUp.push(v);
                }
                $scope.page = r.runnerUp.current_page;
                $scope.lastpage =r.runnerUp.last_page;
                $scope.loading = false;
            }, function(error) {
               $scope.loading = false;
            }); 
        }
    }


	$scope.submitRemix = function (track,id) {

        $scope.isSubmitting = true;
        $rootScope.showLoading = true;
        var data = CompetitionApi.submitRemix(null, {'sc_id' : track.soundcloud_tracks, 'id': id}, function(r){
            $scope.isSubmitting = false;
            $rootScope.showLoading = false;
            $('#submitYourRemixModal').modal('hide');
            $scope.participated = 1;
            // $scope.runnersUp.push(r.data);
            $scope.notifications.push(r.notification);
            SharedData.notifications_count = SharedData.notifications_count + 1;
           // toastr.success('Remix track has been successfully submitted.');
        }, function(r) {
            $scope.isSubmitting = false;
            $rootScope.showLoading = false;
        });
    }

    $scope.reset = function(){
        var submitRemix = angular.element('#submitYourRemixModal').scope();
        submitRemix.track='';
        $('#submitYourRemixModal input[type=checkbox]').attr('checked',false);
        submitRemix.termCheck = 0 ;
        if(submitRemix){
            // $("#submitDemoForm")[0].reset();
            submitRemix.submitRemixForm.$setPristine();
            submitRemix.submitRemixForm.$setUntouched();
        }
    }

});