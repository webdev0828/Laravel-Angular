angular.module('app.services')
.service("PlayerService", function (PlayHistoryApi, $rootScope, $timeout,$state) { 
    
    this.currentTrack = null;
    this.tracks = [];
    this.shuffledTracks = [];
    this.isShuffle = false;
    this.isRepeat = false;
    this.hasNext = false;
    this.hasPrevious = false;
    this.isPaused = true;
    this.repeat   = false ;
    $rootScope.trackPlay = true;
    sessionStorage.play_count_increases = 0;
    this.pause = function(track){ 
        $rootScope.trackPlay = true;        
        this.isPaused = true;
        $rootScope._currentTrack = undefined;
        $rootScope.loadingTrack = false;
    }

    this.pauseRepeat = function(track){ 
        $rootScope._currentTrack = undefined;
        $rootScope.loadingTrack = false;
    }

    this.playStatus = function(track){
        if(sessionStorage.play_count_increases == 0){
            PlayHistoryApi.post({id:this.currentTrack.id,slug:this.currentTrack.artist_slug,type:this.currentTrack.track_type}, null, function(r) {
                if(r.data){
                track.play_count = r.data.play_count;
                sessionStorage.play_count_increases = 1;
                sessionStorage.currentTrack = JSON.stringify(track);
                }
            });
        }
        $rootScope.trackPlay = false;        
        $rootScope._currentTrack = track;
        this.isPaused = false;
    }

    this.playRepeat = function(track){ 
        $rootScope._currentTrack = track;
    }

    this.localStatus = function(track){ 
        $rootScope._currentTrack = track;
    }
    
    this.loadingTrack = function(value){ 
        $rootScope.loadingTrack = value;        
    }
    this.setTrack = function(value){ 
        $rootScope._currentTrack = undefined;        
    }

    var played = [];
    this.played = function(){ 
        played = [];
    };
  

    this.play = function(track, type,play) { 
       if(this.currentTrack && track && track.id == this.currentTrack.id) {  
            this.isPaused = false; 
            $rootScope._currentTrack = track;
            if(play ) {
                if ($.inArray($rootScope._currentTrack.id, played) == -1 && sessionStorage.play_count_increases ==0){
                    sessionStorage.play_count_increases = 1;
                    PlayHistoryApi.post({id:this.currentTrack.id,slug:this.currentTrack.artist_slug,type:this.currentTrack.track_type}, null, function(r) {
                        if(r.data){
                        played.push($rootScope._currentTrack.id);
                        track.play_count = r.data.play_count;
                        sessionStorage.play_count_increases = 1;
                        sessionStorage.currentTrack = JSON.stringify(track);
                        }
                    });
                }
            } 
            return;
        }
        else{  
            this.repeat   = false ;
        }

        if(track){
            var tracksInfo = {
                id              : track.id,
                track_name      : track.track_name,
                artist_name     : track.artist_name,
                artist_slug     : track.artist_slug,
                mp3_file        : track.mp3_file,
                cover_image     : track.cover_image,
                slug            : track.slug,
                type            : track.type ,//discover
                track_type      : type ? type : track.track_type,//track
                isLocal         : track.isLocal ? true :false,
                play_count      : track.play_count,
                favourite_count : track.favourite_count,
                campaign_id     : track.campaign_id ? track.campaign_id : null,
                trackPlayStatus : this.isPaused ? false : true , 
            };

            this.currentTrack = tracksInfo;        
            sessionStorage.currentTrack = JSON.stringify(this.currentTrack);
            $rootScope._currentTrack = track;
            this.updateControls();
            PlayHistoryApi.getCount({id:this.currentTrack.id,type:this.currentTrack.track_type}, null, function(r) {
                this.currentTrack = tracksInfo.play_count = r.play_count;
            });
            
            if(play && !this.currentTrack.isLocal){
                PlayHistoryApi.post({id:this.currentTrack.id,slug:this.currentTrack.artist_slug,type:this.currentTrack.track_type}, null, function(r) {
                    if(r.data){
                        track.play_count = r.data.play_count;
                        // sessionStorage.play_count_increases = 1;
                        // this.currentTrack = tracksInfo.play_count = r.data.play_count;
                         sessionStorage.currentTrack = JSON.stringify(track);
                    }
                });
            }
        }
    }

    this.updateControls = function() { 
        // checkt current , next , previeous indices
        var trackList = this.isShuffle ? this.shuffledTracks : this.tracks;
        var currentIndex = customLib.getIndexByName(trackList, this.currentTrack, 'id', 'type');
        
        if(trackList){
            this.hasNext = trackList[currentIndex + 1] ? true : false;
            this.hasPrevious = trackList[currentIndex - 1] ? true : false;
        }
        

    }

    this.next = function() {
        var trackList = this.isShuffle ? this.shuffledTracks : this.tracks; 
        var currentIndex = customLib.getIndexByName(trackList, this.currentTrack, 'id', 'type');
        var track_type = '';
        if(currentIndex != -1 &&  trackList[currentIndex + 1]) {
            this.play(trackList[currentIndex + 1], track_type,'play');
        }
    }

    this.previous = function() {
        var trackList = this.isShuffle ? this.shuffledTracks : this.tracks;
        var currentIndex = customLib.getIndexByName(trackList, this.currentTrack, 'id', 'type');
        var track_type = '';
        if(currentIndex != -1 && trackList[currentIndex - 1]) {
            this.play(trackList[currentIndex - 1], track_type,'play');
        }
    }

    this.shuffle = function() {
        if(this.isShuffle) {
            this.isShuffle = false;
            this.shuffledTracks = [];
        } else {
            this.isShuffle = true;
            this.shuffledTracks = customLib.shuffleArray(angular.copy(this.tracks));
        }
        this.updateControls();
    }

    // if($state.current.name != 'app.single_campaign' && $state.current.name != 'app.single_track'){
    //     alert($state.current.name);
    // }

    if(sessionStorage.currentTrack){ 
        var track = JSON.parse(sessionStorage.currentTrack);
        track.isLocal = true;
        var index = customLib.getIndexByName($rootScope.featuredTracks, track, 'id', 'type');
        this.play(track, track.track_type,'play');

        var me = this;
        $timeout(function(){
            var trackList = me.isShuffle ? me.shuffledTracks : me.tracks;
            var currentIndex = customLib.getIndexByName(trackList, me.currentTrack, 'id', 'type');
            if(trackList){
                me.hasNext = trackList[currentIndex + 1] ? true : false;
                me.hasPrevious = trackList[currentIndex - 1] ? true : false;
            }           

        },1000);
    }
});