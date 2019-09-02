angular.module('app.controllers')
.controller('PageCtrl', function($scope, $http, NewsApi, API_URL ,PageApi , SharedData) { 
	$('body').attr('class','page');

	
	// var news = NewsApi.get(null, function(r) {
	// 	$scope.allNews = r.data;
	// });

   //  $scope.tracks =  [];
   //  $scope.genersModel = [];
   //  $scope.dateFilter ='ASC';
    
   //  $scope.sortingData = function(value){
   //      $scope.dateFilter = value;
   //      getTracks();
   //  };

   //  $scope.trackPage = function(value){
   //      $scope.pageName = value;
   //      getTracks();
   //  }
   //  // Artist.set();
   //  $scope.genersData = SharedData._genres;


   //  $scope.genersSettings = {
   //                              smartButtonMaxItems: 3,
   //                              enableSearch: true,
   //                              searchFilter:{},
   //                              // scrollableHeight: '400px',
   //                              // scrollable: true,
   //                              smartButtonTextConverter: function(itemText, originalItem) {
   //                                  return itemText;
   //                              }
   //                          };
   //  $scope.genersCustomTexts = { buttonDefaultText: 'Gener'};

   //  $scope.genersEvents = {
   //      onItemSelect: function(evt) {
   //          getTracks(); 
   //      },
   //      onItemDeselect: function(evt) { 
   //          getTracks(); 
   //      }
   //  };

   // $scope.genersData = SharedData._genres;
   //  $scope.getFollowersTracks = function (){
   //      $scope.error = null;
   //      PageApi.followersTracks(null,function(r){
   //          $scope.tracks =r.data;
   //      },function(r){
   //          $scope.error = r;
   //      })
   //  }

	
})
