angular.module('app.controllers')
.controller('PageDiscoverCtrl', function($scope, $http, PageApi , SharedData , TrackApi, PlayerService, $state) {
	$('body').attr('class','page');
	window.scrollTo(0, 0);
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

	// Call after selecting genre from track player
	$scope.setGenre = function(value){
		$scope.genersModel.id = value;
		getTracks();
	}

	$scope.setSubGenre = function(value){
		$scope.sub_genre = value.id;
		$scope.genersModel.id = value.parent_id;
		getTracks();
	}

	$scope.setMood = function(value){
		$scope.moodsModel.id = value;
		getTracks();
	}
	$scope.trackPage = function(value){
		$scope.pageName = value;
		var genre = $state.params.genre;
		var mood = $state.params.mood;
		if(genre){
			angular.forEach(SharedData._genresAll,function(r){
        		if(r.slug == genre){
	    			if(r.parent_id){
	    				$scope.genersModel.id = r.parent_id;
	    				$scope.sub_genre = r.id;
	    				$scope.selected = null;
	    			}
	    			else{
	    				$scope.genersModel.id = r.id;
	    				$scope.sub_genre = null;
	    				$scope.selected = null;
	    			}
	        	}
			});
			getTracks();
			return false;
		}
		if(mood){
			angular.forEach(SharedData._moods,function(r){
        		if(r.slug == mood){
	    			$scope.moodsModel.id = r.id;
	        	}
			});
		}

		getTracks();
	}
	

	$scope.genersData = angular.copy(SharedData._genres);
	$scope.genersData.unshift({'name':'All', 'id':''});

	$scope.moodsData = angular.copy(SharedData._moods);
	$scope.moodsData.unshift({'name':'All', 'id':''});
	
	$scope.filterData = SharedData.commonFilter;

	$scope.genersSettings = {
							    smartButtonMaxItems: 1,
							    enableSearch: false,
							    selectionLimit: 1,
							    closeOnSelect : true,
							    smartButtonTextConverter: function(itemText, originalItem) {
							    	return itemText;
		    					}
							};
	$scope.genersCustomTexts = { buttonDefaultText: 'Genre'};
	$scope.moodsCustomTexts = { buttonDefaultText: 'Vibe'};
	$scope.filterCustomTexts = { buttonDefaultText: 'Sort By'};

	$scope.genersEvents = {

		onInitDone: function(evt) {
			
			var genre = $state.params.genre;
			if(genre){
				angular.forEach(SharedData._genresAll,function(r){
	        		if(r.slug == genre){
		    			if(r.parent_id){
		    				// var index = $scope.subGenres.map(function(el) {return el.id;}).indexOf(r.id);
		    				$scope.genersModel.id = r.parent_id;
		    				$scope.sub_genre = r.id;
		    				$scope.selected = null;
		    			}
		    			else{
		    				$scope.genersModel.id = r.id;
		    				$scope.sub_genre = null;
		    				$scope.selected = null;
		    			}
		        	}
				});
			}
		},
		onItemSelect: function(evt) { 
			$scope.selected = null;
			$scope.sub_genre = null;
			getTracks(); 
		}
	};

	$scope.moodsEvents = {
		onItemSelect: function(evt) {
			$scope.selected = null;
			$scope.sub_genre = null;
			getTracks(); 
		}
	};

	$scope.filterEvents = { 
		onItemSelect: function(evt) {
			$scope.selected = null;
			$scope.sub_genre = null;
			$scope.sortingData($scope.filterModel.id); 
		}
	};

	// $scope.selected = 0;
    $scope.select= function(index, value) {
    	$scope.sub_genre = value ? value.id : null;
        $scope.selected = index ? index : null;
		getTracks();
    };


	function getTracks(){
		$scope.loading = true;
		$scope.temp = [] ;
		// $scope.subGenres = [] ;
		var geners = $scope.genersModel.id ? $scope.genersModel.id : [];
		var moods = $scope.moodsModel.id ? $scope.moodsModel.id : [];
		
		var generData = { order : $scope.dataFilter, geners : geners, moods : moods, sub_genre: $scope.sub_genre};
		
		TrackApi.post({type:'discover',limit : $scope.limit}, generData, function(r) {
			$scope.tracks = r.data;
			$scope.spotlightTracks = r.spotlights;
			SharedData._tracks = $scope.tracks;
			$scope.subGenres = r.subGenres;
			if($scope.sub_genre){
				var index = $scope.subGenres.map(function(el) {return el.id;}).indexOf($scope.sub_genre);
				$scope.selected = index;
			}
			var genre = $state.params.genre;
			if(genre){
				angular.forEach(SharedData._genresAll,function(r){
	        		if(r.slug == genre && r.id == $scope.sub_genre){
		    			if(r.parent_id){
		    				var index = $scope.subGenres.map(function(el) {return el.id;}).indexOf(r.id);
		    				$scope.selected = index;
		    			}
		    		}
		    	});
		    }
		    var mood = $state.params.mood;
		    if(mood){
				angular.forEach(SharedData._moods,function(r){
					if(r.slug == mood){
		    			$scope.moodsModel.id = r.id;
		        	}
				});
			}

			$scope.currentPage = r.current_page;
			$scope.totalTracks = r.total;
			PlayerService.tracks = $scope.tracks;

			$scope.page = r.current_page;
			$scope.lastpage =r.last_page;
			$scope.loading = false;

			// Spotlight-track and discover-track match for fovourite count.
			if($scope.spotlightTracks){
				angular.forEach($scope.tracks,function(discoverTrack, i){
					angular.forEach($scope.spotlightTracks,function(spotlightTrack, j){
	        			if(discoverTrack.id == spotlightTrack.id){
							$scope.spotlightTracks[j] = $scope.tracks[i];
	        			}
	        		});
	            });
			}
		},function(error){
			$scope.loading = false;
		});
	};

	$scope.loadMoreTracks = function (){

		var geners = [];
		var moods = [];
		$scope.temp = [] ;
		// $scope.subGenres = [] ;

		
		var geners = $scope.genersModel.id ? $scope.genersModel.id : [];
		var moods = $scope.moodsModel.id ? $scope.moodsModel.id : [];
	
		var generData = { order : $scope.dataFilter, geners : geners, moods : moods, sub_genre: $scope.sub_genre};
		if(!$scope.loading && ($scope.page !=  $scope.lastpage) && ($scope.page <=  $scope.lastpage)){
			$scope.loading = true;
			TrackApi.getMoreTracks({type:"discover",page: $scope.page+1 , limit : $scope.limit}, generData, function(r) {
				for(i in r.data) {
	                var v = r.data[i];
	                $scope.tracks.push(v);
	                SharedData._tracks = $scope.tracks;
	                // PlayerService.tracks = $scope.tracks;
	            }
				$scope.page = r.current_page;
				$scope.lastpage =r.last_page;
				$scope.loading = false;
			}, function(error) {
		       $scope.loading = false;
		    });	
		}
	}
});