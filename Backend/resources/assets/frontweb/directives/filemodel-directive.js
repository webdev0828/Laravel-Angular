angular.module('app.directives')
.directive('fileModel', function($parse) {
    return {
        restrict: 'A',
        require:'?ngModel',
        link: function(scope, element, attrs) { 
            var model = $parse(attrs.ngModel);
            var modelSetter = model.assign;

            scope[attrs.errorHeightWidth] = false;
            scope[attrs.errorImageSize] = false;
            scope.imageSizeValidation = false;
            // scope.imageSizeLimit = false;
            
            element.bind('change', function(){ 
                if(attrs.ngModel){
                    if(attrs.minHeight && attrs.minWidth){  
                        var fr = new FileReader;
                        fr.onload = function() { 
                            var img = new Image;
                            img.src=fr.result;
                            img.onload = function() { 
                                var flag = false;
                                if(img.width >= attrs.minWidth && img.height >= attrs.minHeight){
                                        modelSetter(scope, element[0].files[0]); 
                                        scope[attrs.errorHeightWidth] = false;
                                        scope.imageSizeValidation = false;
                                        scope.$apply();
                                        flag = true;
                                }else{  
                                    scope.$apply(function(){
                                        if(attrs.errorHeightWidth == "bannerImageUpdateError")
                                            toastr.error("Please select image from 1900x680px and more.");
                                        scope[attrs.errorHeightWidth] = true;
                                        // scope.imageSizeValidation = true;
                                        scope[attrs.errorImageSize] = false;
                                        // scope.imageSizeLimit = false;

                                    });
                                }

                                // var imageSize = Math.round(element[0].files[0].size/1024);
                                // if(flag){
                                //     if(!(imageSize >= attrs.maxSize )){ 
                                //         scope.$apply(function(){
                                //             scope[attrs.errorImageSize] = false;
                                //             scope.imageSizeLimit = false;
                                //             modelSetter(scope, element[0].files[0]);
                                //         });
                                //     }else{ 
                                //         scope[attrs.errorImageSize] = true;
                                //         scope.imageSizeLimit = true;
                                //         scope.$apply();
                                //     }
                                // }
                            };
                        };
                        fr.readAsDataURL(this.files[0]);
                    }
                    else{ 
                        scope.$apply(function(){
                            modelSetter(scope, element[0].files[0]);
                        });
                    }
                }
            });
        }
    };
});