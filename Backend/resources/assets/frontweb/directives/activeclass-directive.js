angular.module('app.directives')
.directive('activeClass', function() {
    return function(scope, element) {
      element.find('a.link').on('click', function(e) {
        var link = $(e.currentTarget);
        link
          .parent('li')
          .addClass('active')
          .siblings('li')
          .removeClass('active');
        e.preventDefault();
      });
    };
  });