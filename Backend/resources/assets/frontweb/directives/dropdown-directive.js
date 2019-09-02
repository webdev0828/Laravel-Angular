angular.module('app.directives')
.directive('dropdowntoggle', function() { 
  return {
    restrict: 'C',
    link: function($scope, element, attr) { 
      $(element).dropdown(); 
    }
  }
});