angular.module('app.directives')
.directive('uploadfile', function () {
    return {
      restrict: 'C',
      link: function(scope, element) {

        element.bind('click', function(e) {
            angular.element(e.target).siblings('#upload').trigger('click');
        });
      }
    };
});