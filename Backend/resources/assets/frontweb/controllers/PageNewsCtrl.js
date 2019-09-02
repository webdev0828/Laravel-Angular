angular.module('app.controllers')
.controller('PageNewsCtrl', function($scope, $http, NewsApi, $state) {

$('body').attr('class','page');
	window.scrollTo(0, 0);
	// $scope.news = NewsApi.get();
	$scope.page = 0;
	$scope.lastpage = 1;
	$scope.loading = false;
	$scope.limit = 9 ;
	// var news = NewsApi.get(null, function(r) {
		
	// 	$scope.allNews = r.data.news;
	// 	$scope.topNews = $scope.allNews.shift();

	// 	$scope.page = r.data.current_page;
	// 	$scope.lastpage =r.data.last_page;
	// 	// $scope.arrString = new Array();
 //        // $scope.tagsString = $scope.topNews.tags.split(',');
 //        $scope.tagLimit = 3;
	// });

	$scope.getNews= function() {
		$scope.loading = true;
		var tag = $state.params.q;
		$scope.viewAll = $state.params.q;
		
		var	params = tag ? {tag : tag } : null;
		
		var news = NewsApi.get(params, function(r) {
			$scope.allNews = r.data.news;
			$scope.topNews = r.latestNews;
			$scope.loading = false;
			$scope.page = r.data.current_page;
			$scope.lastpage =r.data.last_page;

			// $scope.arrString = new Array();
	        // $scope.tagsString = $scope.topNews.tags.split(',');
	        $scope.tagLimit = 3;
		},function(error){
			$scope.loading = false;
		});
	}


	$scope.loadMoreNews = function (){
		
		if(!$scope.loading && ($scope.page !=  $scope.lastpage) && ($scope.page <=  $scope.lastpage)){
			$scope.loading = true;
			var tag = $state.params.q;
		
			var	params = {page: $scope.page+1 , limit : $scope.limit};

			if(tag) {
				params.tag = tag;
			}

			NewsApi.getMoreNews(params, null, function(r) {
				for(i in r.data.news) {
	                var v = r.data.news[i];
	                $scope.allNews.push(v);
					// $scope.topNews = $scope.allNews.shift();
	            }
				$scope.page = r.current_page;
				$scope.lastpage =r.last_page;
				$scope.loading = false;
			},function(error){
				$scope.loading = false;
			});	
		}
	}

});