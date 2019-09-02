angular.module('app.directives')
.directive("jplayer", function ($window, PlayerService,$rootScope,SharedData,$timeout) {
    return {
        restrict: "AE",
        scope: {
            audio: "=audio",
            addPlaylistlModal1:"=",
            checkuser   : "=",
            favtrack     : "=",
            favcampaign  : "=",
            favremix     : "=",
            imagedefault : "=",
            playlisttrack: "=",
            playlistcampaign: "=",
            playlistremix : "="
        },
        templateUrl: "frontweb//tpl//blocks//audio-player-template.html",
        
        controller: function($scope, $stateParams,$rootScope,SharedData) {
            var  jPlayers = angular.element(".player-fixed-bottom");
            
            $scope.track= {};
            $scope.PlayerShow = false;

            $scope.hidePlayer = function(){
                jPlayers.addClass("player-hide-show");
                $scope.PlayerShow = false;
                $scope.Playerhide = true;
            }

            $scope.showPlayer = function(){
                jPlayers.removeClass("player-hide-show");
                $scope.Playerhide = false;
                $scope.PlayerShow = true;
            }
            $scope.hidePlayer();
            // $scope._defaults = $rootScope.appScope._defaults;

            $scope.addPlaylistlModal1 = function(trackId, type) { 
                $rootScope.addPlaylistlModal1(trackId, type);
            };
         
            $scope.addInFavourites = function(track, type){
                $rootScope.addInFavourites(track, type); 
            }

            $scope.removePlaylistTrack = function(track, type){
                $rootScope.removePlaylistTrack(track, type); 
            }
            
            $scope.cropimage = $rootScope.appScope.cropimage;
        },
        link: function (scope, element, attrs) {
            scope.player = PlayerService; 
            scope.player.error = false;

            var timeDrag = false;
            var  select = angular.element;
            var prevVolume;
            var  jPlayers = select("#stm_player");            
            var updatePlayer = function(audioMp3) { 
                // var controls = jPlayers.option("enableRemoveControls"); // Get option
                // jPlayers.option("enableRemoveControls", true); // Set option
                jPlayers.jPlayer({
                    enableRemoveControls:true,
                    swfPath: '../js',
                    solution: 'html, flash',
                    supplied: "webmv, ogv, m4v, oga, mp3",
                    smoothPlayBar: true,
                    keyEnabled: false,
                    audioFullScreen: false,
                    reversePlayBar: true,
                    volume: prevVolume,
                    autoBlur: false,
                    remainingDuration: false,
                    toggleDuration: true,
                    cssSelectorAncestor: "#stm_container_1",
                    wmode: 'window',
                    cssSelector: {
                        play: ".stm-play",
                        pause: ".stm-pause",
                        stop: ".stm-stop",
                        seekBar: ".stm-seek-bar",
                        playBar: ".stm-play-bar",
                        mute: ".stm-mute",
                        unmute: ".stm-unmute",
                        volumeBar: ".stm-volume-bar",
                        volumeBarValue: ".stm-volume-bar-value",
                        volumeMax: ".stm-volume-max",
                        currentTime: "#starttime",
                        duration: "#end-time",
                        artist: ".stm-artist",
                        title: ".stm-title",
                        shuffle:".stm-shuffle",
                        shuffleOff:".stm-shuffle-off",
                        repeat: ".stm-repeat",
                        repeatOff: ".stm-repeat-off",
                    },
                    errorAlerts: false,
                    warningAlerts: false,
                    ready: function (e) {                        
                        if(audioMp3.isLocal){
                        jPlayers
                            .jPlayer("setMedia", {mp3: audioMp3.mp3_file});
                            $timeout(function() {
                                PlayerService.localStatus();
                                PlayerService.loadingTrack(1);
                            });
                        }else{
                        jPlayers
                            .jPlayer("setMedia", {mp3: audioMp3.mp3_file})
                            .jPlayer(attrs.autoplay === 'true' ? 'play' : 'stop');
                            //.jPlayer('play');
                            $timeout(function() {
                                PlayerService.loadingTrack(1);
                            });
                        }
                    },
                    play: function(e) {
                        jPlayers.jPlayer('pauseOthers');
                        scope.showPlayer();
                        $timeout(function() {
                            if(PlayerService.repeat){
                                PlayerService.playRepeat(audioMp3);
                            }else{
                                PlayerService.playStatus(audioMp3);
                            }
                        });
                    },
                    pause: function() { 
                        $timeout(function() { 
                            if(PlayerService.repeat){
                                PlayerService.pauseRepeat(audioMp3);
                            }else{
                                PlayerService.pause(audioMp3);
                            }
                        });
                    },
                    ended: function(event) { 
                        $timeout(function() { 
                            select(".stm-ball").css("left",event.jPlayer.status.currentPercentAbsolute + "%");
                            if(PlayerService.repeat){
                                jPlayers.jPlayer('play');
                            }else{ 
                                PlayerService.next();
                            }
                        });
                    },
                    loadeddata : function(event){ 
                        if(event.jPlayer.status.duration){
                            scope.player.error = false;
                            $timeout(function() { 
                            PlayerService.loadingTrack(0);
                            });
                        }
                    },
                    error : function(event){ 
                        if(event.jPlayer.error.type == 'e_url'){
                            scope.player.error = true;
                        }
                    },
                    volumechange : function(event){ 
                        if(event.jPlayer.options.volume){ 
                            prevVolume = event.jPlayer.options.volume;
                        }
                    },
                    timeupdate: function(event) {
                        // select(".stm-ball").css("display","none");
                        if(event.jPlayer.status.currentPercentAbsolute){ 
                            // select(".stm-ball").css("display","block");
                            select(".stm-ball").css("left",event.jPlayer.status.currentPercentAbsolute + "%");
                        }
                        // if (event.jPlayer.status.currentTime) {
                        //     localStorage.previousTime = JSON.stringify(event.jPlayer.status.currentTime);
                        // }
                    },
                });
            }

            /* Drag status */
            var updatebar =  function(x){
                var progress = select('.stm-progress');
                var maxduration = select("#stm_player").jPlayer.duration;
                var position = x - progress.offset().left; 
                var percentage = 100 * position / progress.width();

                //Check within range
                if (percentage > 100) {
                    percentage = 100;
                }
                if (percentage < 0) {
                    percentage = 0;
                }
                select("#stm_player").jPlayer("playHead", percentage);

                //Update progress bar currenttime
                select('.stm-ball').css('left', percentage+'%');
                select('.stm-play-bar').css('width', percentage + '%');
                select("#stm_player").jPlayer.currentTime = maxduration * percentage / 100;
            }

            element
            .on('mousedown', '.stm-play-bar', function (e) { 
                timeDrag = true;
                updatebar(e.pageX);
            })
            .mouseup(function (e) {if (timeDrag) {timeDrag = false;updatebar(e.pageX);}})
            .mousemove(function (e) {if (timeDrag) {updatebar(e.pageX);}})
            .on('click', '.stm-next', function () { 
                PlayerService.next();
            })
            .on('click', '.stm-previous', function () {
                PlayerService.previous();
            })
            .on('click', '.stm-shuffle', function (event) {
                PlayerService.shuffle();
            })
            .on('click', '.stm-repeat', function (event) {
                PlayerService.repeat = true;
            })
            .on('click', '.stm-repeat-off', function (event) {
                PlayerService.repeat = false;
            });            
            
            // $(document.documentElement).keydown(function(event) {
            //     if($("#stm_container_1").length)
            //     { 
            //         //$(event.target).is('body');
            //         if(event.which === 32 && event.target == document.body) { 
            //             if(jPlayers.data("jPlayer").status.paused) { 
            //                jPlayers.jPlayer("play");
            //                // event.preventDefault();
            //             } else {
            //                jPlayers.jPlayer("pause"); 
            //                // event.preventDefault();
            //             }
            //         }
            //     }
            // });

            scope.$watch('player.currentTrack', function(valueNew,valueOld){
                // if(valueNew && valueOld && valueNew == valueOld){
                //     // var time = localStorage.previousTime ? JSON.parse(localStorage.previousTime) : 0;
                //         jPlayers.jPlayer("destroy");
                //         updatePlayer(valueNew);
                // }
                // else if(valueNew && valueNew != valueOld){ 
                //     jPlayers.jPlayer("destroy");
                //     updatePlayer(valueNew);
                // }
                if(valueNew){
                    jPlayers.jPlayer("destroy");
                    updatePlayer(valueNew);
                } 
            });

            scope.$watch(function () {
               return sessionStorage.currentTrack;
            }, function (newVal, oldVal) {
               if(newVal){
                var data = JSON.parse(newVal);
                scope.player.currentTrack.play_count = data.play_count;
               }
            }, true);

            scope.$watch('player.isPaused', function(value){  
                if(value) { 
                    jPlayers.jPlayer('pause');
                }
                else { 
                    jPlayers.jPlayer('play');    
                }   
            });

            scope.$watch('checkuser', function(valueNew,valueOld){ 
                if(valueNew && valueNew != undefined){
                    scope.user = valueNew ? true : false;
                }
            });
            scope.$watch('favtrack', function(valueNew,valueOld){
                if(valueNew && valueNew != undefined){
                    scope._favTrackIds = valueNew;
                }
            });
            scope.$watch('favcampaign', function(valueNew,valueOld){
                if(valueNew && valueNew != undefined){
                    scope._favCampaignIds = valueNew;
                }
            });
            scope.$watch('favremix', function(valueNew,valueOld){
                if(valueNew && valueNew != undefined){
                    scope._favRemixIds = valueNew;
                }
            });
            scope.$watch('playlisttrack', function(valueNew,valueOld){
                if(valueNew && valueNew != undefined){
                    scope._playlistTrackIds = valueNew;
                }
            });
            scope.$watch('playlistcampaign', function(valueNew,valueOld){
                if(valueNew && valueNew != undefined){
                    scope._playlistCampaignIds = valueNew;
                }
            });
            scope.$watch('playlistremix', function(valueNew,valueOld){
                if(valueNew && valueNew != undefined){
                    scope._playlistRemixIds = valueNew;
                }
            });
            scope.$watch('imagedefault', function(valueNew,valueOld){
                if(valueNew && valueNew != undefined){
                    scope._defaults = valueNew;
                }
            });
        }
    };
});

