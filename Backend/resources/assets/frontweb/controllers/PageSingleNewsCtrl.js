angular.module('app.controllers')
.controller('PageSingleNewsCtrl', function($scope, $http, $state, NewsApi) {

$('body').attr('class','page');

	// $scope.news = NewsApi.get();
	$scope.newsError = false;
	var id = $state.params.slug;
	var news = NewsApi.get({'id':id}, function(r) {
		$scope.newsError = false;
		$scope.news = r.data;
		$scope.relatedNews = r.relatedNews;
		// $scope.tags = $scope.news.tags;
		// $scope.arrTags = new Array();
		// $scope.arrTags = $scope.tags.split(',');	
	},function(error){
		$scope.newsError = true;
	});

});