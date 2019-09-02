angular.module('app.controllers')
.controller('PageVideosCtrl', function($scope, $http, PageApi, VideoApi, SharedData, $state) {
	$('body').attr('class','page');
    window.scrollTo(0, 0);
    $scope.videos =  [];
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
        getVideos();
    };

    $scope.setGenre = function(value){
        $scope.genersModel.id = value;
        getVideos();
    }

    $scope.setSubGenre = function(value){
        $scope.sub_genre = value.id;
        $scope.genersModel.id = value.parent_id;
        getVideos();
    }

    $scope.setMood = function(value){
        $scope.moodsModel.id = value;
        getVideos();
    }


    $scope.videoPage = function(value){
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
            getVideos();
            return false;
        }
        if(mood){
            angular.forEach(SharedData._moods,function(r){
                if(r.slug == mood){
                    $scope.moodsModel.id = r.id;
                }
            });
        }
        getVideos();    
    }


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

    // $scope.genersEvents = {
    //     onItemSelect: function(evt) {
    //         $scope.selected = null;
    //         $scope.sub_genre = null;
    //         getVideos(); 
    //     },
    // };


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
            getVideos(); 
        }
    };



    $scope.moodsEvents = {
        onInitDone: function(evt) {
            
            var mood = $state.params.mood;
            if(mood){
                angular.forEach(SharedData._moods,function(r){
                    if(r.slug == mood){
                        $scope.moodsModel.id = r.id;
                    }
                });
            }
        },
        onItemSelect: function(evt) {
            $scope.selected = null;
            $scope.sub_genre = null;
            getVideos(); 
        },
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
        getVideos();
    };

    function getVideos(){
        $scope.loading = true;
        $scope.temp = [] ;
        // $scope.subGenres = [] ;
        var geners = $scope.genersModel.id ? $scope.genersModel.id : [];
        var moods = $scope.moodsModel.id ? $scope.moodsModel.id : [];

        var generData = { order : $scope.dataFilter, geners : geners, moods : moods, sub_genre: $scope.sub_genre};
        VideoApi.post({page:$scope.currentPage}, generData, function(r) {
            $scope.videos = r.data;
            $scope.spotlightVideo = r.spotlights;
            $scope.subGenres = r.subGenres;
            $scope.page = r.current_page;
            $scope.lastpage =r.last_page;
            $scope.loading = false;

            if($scope.sub_genre){
                var index = $scope.subGenres.map(function(el) {return el.id;}).indexOf($scope.sub_genre);
                $scope.selected = index;
            }
            //Spotlight-video and music-video match for fovourite count.
            if($scope.spotlightVideo){
                angular.forEach($scope.videos,function(musicVideo, i){
                    if(musicVideo.id == $scope.spotlightVideo['id']){
                        $scope.spotlightVideo = $scope.videos[i];
                    }
                });
            }
            

        });
    };

    $scope.loadMoreVideos = function (){
        // $scope.loading = true;
        $scope.temp = [] ;
        // $scope.subGenres = [] ;

        var geners = $scope.genersModel.id ? $scope.genersModel.id : [];
        var moods = $scope.moodsModel.id ? $scope.moodsModel.id : [];

        var generData = { order : $scope.dataFilter, geners : geners, moods : moods, sub_genre: $scope.sub_genre};

        if(!$scope.loading && ($scope.page !=  $scope.lastpage) && ($scope.page <=  $scope.lastpage) ){
            $scope.loading = true;
            VideoApi.getMoreVideos({page: $scope.page+1 , limit : $scope.limit}, generData, function(r) {
                for(i in r.data) {
                    var v = r.data[i];
                    $scope.videos.push(v);
                }
                $scope.page = r.current_page;
                $scope.lastpage =r.last_page;
                $scope.loading = false;
            }); 
        }
    }
});