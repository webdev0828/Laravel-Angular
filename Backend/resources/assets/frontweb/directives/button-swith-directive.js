angular.module('app.directives')
.directive('buttonSwitch', function() {
            return {
                restrict: 'A',
                require: '?ngModel',
                link: function(scope, element, attrs, ngModel) {
                    // $(element).bootstrapSwitch('state', $(element).is(":checked"), false);
                    
                    // $(element).bootstrapSwitch('disabled',true);

                    $(element).on('switchChange.bootstrapSwitch', function(event, state){
                         
                        if (state) {
                          ngModel.$setViewValue(state);

                        } else {
                          // var ModelValue = false;
                          ngModel.$setViewValue(state);
                          // console.log('false',state);
                          // console.log(ngModel.$setViewValue(state));
                        }
                        // scope.$apply(function() {
                        //     ngModel.$setViewValue(state);
                        //   });
                        event.preventDefault(); 

                    });

                    scope.$watch(attrs.ngModel, function(newValue, oldValue) { 
                    if (newValue == 'true') {
                          $(element).bootstrapSwitch('state', newValue, true);

                          ngModel.$setViewValue(newValue);
                    } else if(newValue == 'false') {
                          $(element).bootstrapSwitch('state', !newValue, true);
                          // ngModel.$setViewValue(newValue);

                    }
               });

              scope.$watch(attrs.ngDisabled, function(newValue, oldValue) { 
                  $(element).bootstrapSwitch('disabled',newValue);
              });

              scope.$watch(attrs.ngChecked, function(newValue, oldValue) { 
                  $(element).bootstrapSwitch('state', newValue);
              });


            }
          }
        });

angular.module('app.directives')
.directive('btnSwitch', function() {
  return {
    restrict: 'A',
    require: '?ngModel',
    link: function(scope, element, attrs, ngModel) {

      $(element).on('switchChange.bootstrapSwitch', function(event, state){
          
          if(attrs.buttonSwitchChange) {
            $(element).bootstrapSwitch('state', !state, true);
            var response = scope.$eval(attrs.buttonSwitchChange);  

            $(element).bootstrapSwitch('state', response, true);
            ngModel.$setViewValue(response);
            return;
          }

          if (state) {
            ngModel.$setViewValue(state);

          } else {
            // var ModelValue = false;
            ngModel.$setViewValue(state);
            // console.log('false',state);
            // console.log(ngModel.$setViewValue(state));
          }
          event.preventDefault();  
      });

      scope.$watch(attrs.ngModel, function(newValue, oldValue) { 
          if (newValue == 'true') { 
              // $(element).bootstrapSwitch('state', newValue, true);
              ngModel.$setViewValue(newValue);
          } else if(newValue == 'false') {  
              // $(element).bootstrapSwitch('state', !newValue, true);
              ngModel.$setViewValue(newValue);
          }
      });

      scope.$watch(attrs.ngDisabled, function(newValue, oldValue) { 
          $(element).bootstrapSwitch('disabled',newValue);
      });

      scope.$watch(attrs.ngChecked, function(newValue, oldValue) { 
          $(element).bootstrapSwitch('state', newValue);
      });
    }
  }
});

angular.module('app.directives')
    .directive('buttonAlter', function() {
        return {
            restrict: 'A',
            require: '?ngModel',
            link: function(scope, element, attrs, ngModel) {
                $(element).on('switchChange.bootstrapSwitch', function(event, state){
                    ngModel.$setViewValue(state);
                    event.preventDefault();
                });

                scope.$watch(attrs.ngModel, function(newValue, oldValue) {
                    $(element).bootstrapSwitch('state', newValue, true);
                    ngModel.$setViewValue(newValue);
                });

                scope.$watch(attrs.ngDisabled, function(newValue, oldValue) {
                    $(element).bootstrapSwitch('disabled',newValue);
                });

                scope.$watch(attrs.ngChecked, function(newValue, oldValue) {
                    $(element).bootstrapSwitch('state', newValue);
                });
            }
        }
    });
