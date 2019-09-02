angular.module('app.directives')
.directive("owlCarousel", function() { 
    return {
        restrict: 'E',
        transclude: false,
        link: function (scope) {
            scope.initCarousel = function(element) {
                $(element).owlCarousel({
                    items : 3,
                    itemsDesktop : [1000,3],
                    itemsDesktopSmall : [900,2],
                    itemsTablet: [600,2],
                    itemsMobile : false
                });
            };
        }
    };
})
.directive('owlCarouselItem', function() {
    return {
        restrict: 'A',
        transclude: false,
        link: function(scope, element) {
            if(scope.$last) {
                scope.initCarousel(element.parent());
            }
        }
    };
})
