angular.module('app.directives')
.directive('imageError', function ($q) { 
    return {
        restrict: 'A',
        link: function (scope, element, attrs) {
            $(element).bind('error', function(){
                attrs.$set('src', attrs.imageError);
            });
            attrs.$observe('ngSrc', function(value) {
              if (!value && attrs.imageError) {
                attrs.$set('src', attrs.imageError);       
              }
            });
        }
    };
});