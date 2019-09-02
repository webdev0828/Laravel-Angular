angular.module('app.directives')
.directive("dashboardOwl", function() { 
    return {
        restrict: 'E',
        transclude: false,
        link: function (scope) {
            scope.initCarousel = function(element) {
                $(element).owlCarousel({
                    items : 4,
                    itemsDesktop : [1000,4],
                    itemsDesktopSmall : [900,4],
                    itemsTablet: [600,3],
                    itemsMobile : false
                });
            };
        }
    };
})
.directive('owlDashboardItem', [function() {
    return {
        restrict: 'A',
        transclude: false,
        link: function(scope, element) {
            if(scope.$last) {
                scope.initCarousel(element.parent());
            }
        }
    };
}]);
