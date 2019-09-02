angular.module('app.directives')
.directive('audioFile', function($parse,$rootScope) {
    return {
        restrict: 'A',
        require:'?ngModel',
        link: function(scope, element, attrs) {
            var model = $parse(attrs.ngModel);
            var modelSetter = model.assign;

        	var audioElement = document.createElement('audio');

           	element.bind('change', function(e){
            	var file = e.currentTarget.files[0];
                if(file){
                    if(file.type != "audio/mp3" && file.type != "audio/mpeg"){
                        scope.$apply(function(){
                            scope[attrs.audioFileType] = true;
                        });
                     }else{
                        scope.$apply(function(){
                            scope[attrs.audioFileType] = false;
                        });
                     }
                            
    				var objectUrl = URL.createObjectURL(file);
        			audioElement.setAttribute('src', objectUrl); 
    				$(audioElement).on("canplaythrough", function(e){
    				    var seconds = audioElement.duration;
    				 	scope.$apply(function(){
    	                    // modelSetter(scope, element[0].files[0]);
    	                    scope[attrs.audioFileDuration] = seconds;
    	                });
    				    URL.revokeObjectURL(objectUrl);
    				});

                }else{
                    scope.$apply(function(){
                        scope[attrs.audioFileType] = false;
                    });
                }
            });
        }
    };
});