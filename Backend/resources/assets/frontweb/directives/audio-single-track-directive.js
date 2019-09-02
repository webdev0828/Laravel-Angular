angular.module('app.directives')
.directive("jplayerSingle", function ($window, PlayerService,$rootScope,SharedData,$timeout) {
    return {
        restrict: "AE",
        scope: {
            audio: "=audio",
        },
        templateUrl: "frontweb//tpl//blocks//audio-single-template.html",
       
        link: function (scope, element, attrs) {
            scope.playerSingle = PlayerService;
            var  select = angular.element;
            var timeDrag = false;
            var  jPlayer = select("#stm_single_player");
            var updatePlayer = function(audioMp3) {
                jPlayer.jPlayer({
                    swfPath: '../js',
                    solution: 'html, flash',
                    supplied: "webmv, ogv, m4v, oga, mp3",
                    smoothPlayBar: true,
                    reversePlayBar: true,
                    keyEnabled: false,
                    audioFullScreen: false,
                    volume: 0.6,
                    autoBlur: false,
                    remainingDuration: false,
                    toggleDuration: true,
                    cssSelectorAncestor: "#stm_container",
                    wmode: 'window',
                    cssSelector: {
                        play        : ".stm-play",
                        pause       : ".stm-pause",
                        seekBar     : ".stm-seek-bar",
                        playBar     : ".stm-play-bar",
                        currentTime : "#starttime",
                        duration    : "#end-time"
                    },
                    errorAlerts: false,
                    warningAlerts: false,
                    ready: function () {
                    jPlayer
                        .jPlayer("setMedia", {
                                                mp3: audioMp3.mp3_file
                                            });
                        jPlayer.jPlayer("stop")
                        // audioMp3.isLocal ? jPlayer.jPlayer("stop"):jPlayer.jPlayer("play");
                        $rootScope.trackPlay = true;
                        audioMp3.isLocal = true;
                        sessionStorage.currentTrack = JSON.stringify(audioMp3);
                        $timeout(function() { 
                        PlayerService.loadingTrack(1);
                        });
                    },
                    play: function() { 
                        // jPlayer.jPlayer("play",time);                    
                        jPlayer.jPlayer('pauseOthers');
                        $timeout(function() {  
                            PlayerService.playStatus(audioMp3);
                        });
                    },
                    pause: function() {  
                        $timeout(function() { 
                        PlayerService.pause();
                        });
                    },
                    ended: function(event) { 
                        select(".stm-ball").css("left",event.jPlayer.status.currentPercentAbsolute + "%");
                    },
                    loadeddata : function(event){ 
                        if(event.jPlayer.status.duration){
                            scope.playerSingle.error = false;
                            $timeout(function() { 
                            PlayerService.loadingTrack(0);
                            });
                        }
                    },
                    error : function(event){ 
                        if(event.jPlayer.error.type == 'e_url'){
                            scope.playerSingle.error = true;
                        }
                    },
                    timeupdate: function(event) {
                        if(event.jPlayer.status.currentPercentAbsolute){
                            select(".stm-ball").css("left",event.jPlayer.status.currentPercentAbsolute + "%");    
                        }
                    }
                });
            }

            /* Drag status */
            var updatebar =  function(x){
                var progress = select('.stm-progress');
                var maxduration = select("#stm_single_player").jPlayer.duration;
                var position = x - progress.offset().left; 
                var percentage = 100 * position / progress.width();

                //Check within range
                if (percentage > 100) {
                    percentage = 100;
                }
                if (percentage < 0) {
                    percentage = 0;
                }
                select("#stm_single_player").jPlayer("playHead", percentage);

                //Update progress bar currenttime
                select('.stm-ball').css('left', percentage+'%');
                select('.stm-play-bar').css('width', percentage + '%');
                select("#stm_single_player").jPlayer.currentTime = maxduration * percentage / 100;
            } 

            element
            .on('mousedown', '.stm-play-bar', function (e) { 
                timeDrag = true;
                updatebar(e.pageX);
            })
            .mouseup(function (e) {if (timeDrag) {timeDrag = false;updatebar(e.pageX);}})
            .mousemove(function (e) {if (timeDrag) {updatebar(e.pageX);}});         

            scope.$watch('playerSingle.currentTrack', function(valueNew,valueOld){ 
                if(valueNew){
                    jPlayer.jPlayer("destroy");
                    updatePlayer(valueNew);
                    PlayerService.played();
                }                 
            });
            
            scope.$watch('playerSingle.isPaused', function(value){ 
                if(value) { 
                    jPlayer.jPlayer('pause');
                }
                else {
                    jPlayer.jPlayer('play');
                }   
            });
        }
    };
});