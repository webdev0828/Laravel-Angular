angular.module('app.directives')
.directive('windowScroll', function ($window) { 
  var $win = angular.element($window);
  return {
    restrict: 'A',
    link: function (scope, element, attrs) {
      var topClass = attrs.windowScroll, 
          offsetTop = attrs.scrollStart; 
      $win.on('scroll', function (e) {
        $(this).trigger('resize');
        if ($win.scrollTop() >= offsetTop) {
          element.addClass(topClass);
        } else {
          element.removeClass(topClass);
        }
      });
    }
  };
});