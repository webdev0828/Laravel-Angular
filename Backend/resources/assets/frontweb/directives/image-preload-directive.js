angular.module('app.directives')
.directive('imagePreload', function($timeout,SharedData) {
  return {
    restrict: 'A',
    scope: {
      ngSrc: '@'
    },
    link: function(scope, element, attrs) {
      var loaderDiv ='<img src="'+SharedData.loadingImage+'" alt="Loading" class="images-loader-profile">';
      element.on('load',function(){
        if(this.complete){
          var findLoader = angular.element('.images-loader-profile');
          if(findLoader.length){
            findLoader.remove();
          }
        }
      });

      element.on('error', function() {
        if(this.complete){
          var findLoader = angular.element('.images-loader-profile');
          if(findLoader.length){
            findLoader.remove();
          }
        }
      });

      scope.$watch('ngSrc', function(newVal) { 
        if(newVal){ 
          if(!element.next().hasClass("images-loader-profile")){
            element.after(loaderDiv);
          }
        }
      });
    }
  };
});