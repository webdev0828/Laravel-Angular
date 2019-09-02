angular.module('app.controllers')
.controller('PagePastCompetitionCtrl', function($scope, $http, CompetitionApi, PlayerService, $state) {
	$('body').attr('class','page');

    var slug = $state.params.slug;
	
	CompetitionApi.getPastCompetitionData({'slug' : slug },function(r){
        $scope.winner = r.winner;
        $scope.tracks = r.tracks;
        $scope.pastCompetition = r.pastCompetition;
        PlayerService.tracks = $scope.tracks;
	});

});