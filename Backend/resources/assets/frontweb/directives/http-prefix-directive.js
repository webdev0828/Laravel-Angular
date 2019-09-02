angular.module('app.directives')
.directive('httpPrefix', function() { 
    return {
        restrict: 'A',
        require: '?ngModel',
        link: function(scope, element, attrs, controller) {
            function ensureHttpPrefix(value) {
                if(value && !/^(https?):\/\//i.test(value)
                   && 'http://'.indexOf(value) !== 0 && 'https://'.indexOf(value) !== 0 ) {
                   
                    if (value.substr(0, 6) == 'http:/' || value.substr(0, 5) == 'http:' || value.substr(0, 4) == 'http')
                    {  
                        var pref = 'http://' + value.substr(7);
                        controller.$setViewValue(pref);
                        controller.$render();
                        return pref;
                    }
                    else{
                        var pref = 'http://' + value;
                        controller.$setViewValue(pref);
                        controller.$render();
                        return pref;
                    }
                } else{ 

                    return value;
                }     
            }
            controller.$formatters.push(ensureHttpPrefix);
            controller.$parsers.splice(0, 0, ensureHttpPrefix);
        }
    };
})