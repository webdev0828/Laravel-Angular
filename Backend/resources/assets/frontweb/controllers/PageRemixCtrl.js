angular.module('app.controllers')
.controller('PageRemixCtrl', function($scope, $http, PageApi, SharedData, TrackApi, PlayerService) {
	$('body').attr('class','page');
	

    $scope.tracks =  [];
	$scope.genersModel = [];
	$scope.moodsModel = [];
	$scope.filterModel =[];
	$scope.dataFilter ='DESC';

	$scope.page = 0;
    $scope.lastpage = 1;
    $scope.loading = false;
    $scope.limit = 10 ;
	
	
	
	$scope.sortingData = function(value){
		$scope.dataFilter = value;
		getTracks();
	};

	// $scope.trackPage = function(value){
	// 	$scope.pageName = value;
	// 	getTracks();
	// }
	
	// Artist.set();
	$scope.genersData = angular.copy(SharedData._genres);
	$scope.genersData.unshift({'name':'All', 'id':''});

	$scope.moodsData = angular.copy(SharedData._moods);
	$scope.moodsData.unshift({'name':'All', 'id':''});
	
	$scope.filterData = SharedData.commonFilter;


	$scope.genersSettings = {
							    closeOnSelect : true,
                                enableSearch: false,
                                selectionLimit: 1,
                                smartButtonMaxItems: 1,
							    // scrollableHeight: '400px',
							    // scrollable: true,
							    smartButtonTextConverter: function(itemText, originalItem) {
							    	return itemText;
		    					}
							};
	$scope.genersCustomTexts = { buttonDefaultText: 'Genre'};

	$scope.moodsSettings = {
							    closeOnSelect : true,
                                enableSearch: false,
                                selectionLimit: 1,
                                smartButtonMaxItems: 1,
							    smartButtonTextConverter: function(itemText, originalItem) {
							    	return itemText;
		    					}
							};
	$scope.moodsCustomTexts = { buttonDefaultText: 'Vibe'};

	$scope.filterSettings = {
							    smartButtonMaxItems: 1,
							    enableSearch: false,
							    selectionLimit: 1,
							    closeOnSelect : true,
							    // closeOnDeselect :false,
							    // scrollableHeight: '400px',
							    // scrollable: true,
							    smartButtonTextConverter: function(itemText, originalItem) {
							    	return itemText;
		    					}
							};
	$scope.filterCustomTexts = { buttonDefaultText: 'Sort By'};

	$scope.genersEvents = {
		onItemSelect: function(evt) {
			$scope.selected = null;
			$scope.sub_genre = null;
			getTracks(); 
		},
		// onItemDeselect: function(evt) { 
		// 	$scope.selected = null;
		// 	$scope.sub_genre = null;
		// 	getTracks(); 
		// }
	};

	$scope.moodsEvents = {
		onItemSelect: function(evt) {
			$scope.selected = null;
			$scope.sub_genre = null;
			getTracks(); 
		},
		// onItemDeselect: function(evt) { 
		// 	$scope.selected = null;
		// 	$scope.sub_genre = null;
		// 	getTracks(); 
		// }
	};
	$scope.filterEvents = { 
		onItemSelect: function(evt) {
			$scope.selected = null;
			$scope.sub_genre = null;
			$scope.sortingData($scope.filterModel.id);
		}
	};

    $scope.select= function(index, value) {
    	$scope.sub_genre = value.id;
		getTracks();
    	$scope.selected = index; 
    };


	function getTracks(){
		$scope.loading = true;
		$scope.temp = [] ;
		$scope.subGenres = [] ;

		var geners = $scope.genersModel.id ? $scope.genersModel.id : [];
		var moods = $scope.moodsModel.id ? $scope.moodsModel.id : [];

		for(var i=0;i< $scope.genersData.length;i++)
        {
        	if($scope.genersData[i] && $scope.genersData[i].sub_genres && $scope.genersData[i].sub_genres.length > 0){
        		$scope.temp.push($scope.genersData[i].sub_genres);
        		
        	}
        }
        
        for(var i = 0; i < $scope.temp.length; i++){
        	if($scope.temp[i][0].parent_id == geners){ 
        		$scope.subGenres.push($scope.temp[i]);
        	}
        }
        
        $scope.subGenres= $.map($scope.subGenres, function(el) { return el });

		var generData = { order : $scope.dataFilter, geners : geners, moods : moods, sub_genre: $scope.sub_genre};
		
		TrackApi.post({type:"remix", limit : $scope.limit}, generData, function(r) {
			$scope.tracks = r.data;
			SharedData._tracks = $scope.tracks;
			
			PlayerService.tracks = $scope.tracks;
			$scope.page = r.current_page;
            $scope.lastpage =r.last_page;
            $scope.loading = false;
		});
	};

	$scope.loadMoreTracks = function (){

		var geners = [];
		var moods = [];
		$scope.temp = [] ;
		$scope.subGenres = [] ;
		var geners = $scope.genersModel.id ? $scope.genersModel.id : [];
		var moods = $scope.moodsModel.id ? $scope.moodsModel.id : [];


		for(var i=0;i< $scope.genersData.length;i++)
        {
        	if($scope.genersData[i] && $scope.genersData[i].sub_genres && $scope.genersData[i].sub_genres.length > 0){
        		$scope.temp.push($scope.genersData[i].sub_genres);
        	}
        }

        for(var i = 0; i < $scope.temp.length; i++){
        	if($scope.temp[i][0].parent_id == geners){ 
        		$scope.subGenres.push($scope.temp[i]);
        	}
        }

        $scope.subGenres= $.map($scope.subGenres, function(el) { return el });

		var generData = { order : $scope.dataFilter, geners : geners, moods : moods, sub_genre: $scope.sub_genre};
		if(!$scope.loading && ($scope.page !=  $scope.lastpage) && ($scope.page <=  $scope.lastpage)){
			$scope.loading = true;
			TrackApi.getMoreTracks({type:"remix", page: $scope.page+1 , limit : $scope.limit}, generData, function(r) {
				for(i in r.data) {
	                var v = r.data[i];
	                $scope.tracks.push(v);
					SharedData._tracks = $scope.tracks;
	            }
				$scope.page = r.current_page;
				$scope.lastpage =r.last_page;
				$scope.loading = false;
			});	
		}
	}
	// $scope.$watch('currentPage', function(){
	// 	if( $scope.currentPage ) {
	//     	getTracks();
	// 	}
	// });



	// $scope.playTrack =function(track){
	// 	PlayerService.play(track);
	// }

});