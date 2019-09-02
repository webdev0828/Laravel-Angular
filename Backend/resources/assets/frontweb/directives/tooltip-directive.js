angular.module('app.directives')
.directive('tooltipTitle', function() {
  return {
    restrict: 'A',
    link: function($scope, element, attr) { 
      $(element).tooltip(); 
    }
  }
});