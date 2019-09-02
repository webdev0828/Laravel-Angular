angular.module('app.directives')
.directive('contenteditable', function($document,$sce,$rootScope) {
  return {
      restrict: 'A',
      scope:{
        ngModel : '=',
      },
      require: '?ngModel',
      link: function(scope, element, attr, ngModel) {
        scope.$watch('ngModel', function(valNew,valOld){ 
          if(!valNew) $('#readmore').hide();
          if(valNew){
            var curHeight = $(element).height(); 
            if(curHeight >= 202){  
              $rootScope.hidden = true;
              $('#readmore').show();
            }else if(curHeight > 202 && !curHeight < 202){ 
              $rootScope.hidden = false;
              $('#readmore').show();
            }else if(curHeight < 202){
              $('#readmore').hide();
            }
          }
        })
        var read;
        if (!ngModel) {
          return;
        }
        ngModel.$render = function() {
          return element.html(ngModel.$viewValue);
        };
        element.bind('paste', function(e) {
            e.preventDefault();
            var text = (e.originalEvent || e).clipboardData.getData('text/plain');            
            window.document.execCommand('insertText', false, text);
            scope.$apply(read);
        });

        element.bind('blur', function(){
          scope.$apply(read);
        });
        element.bind('keydown', function(e){
          if (e.keyCode === 13) {
            document.execCommand('insertHTML', false, '<br><br>');
            return false;
          }
          scope.$apply(read);
        })

        return read = function() {          
          ngModel.$setViewValue(element.html());
        };
      }
    };
});