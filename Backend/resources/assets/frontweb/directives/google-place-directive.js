angular.module('app.directives')
.directive('googleplace', function() {
    return {
        require: 'ngModel',
        scope:{
            countryCode: "=countryCode",
            setCity: '=method'
        },
        link: function(scope, element, attrs, model) {
            var options = {
                types: ['(cities)'],
                componentRestrictions: {
                    country: scope.countryCode,
                }
            };
            scope.gPlace = new google.maps.places.Autocomplete(element[0], options);

            var autocompleteLsr = google.maps.event.addListener(scope.gPlace, 'place_changed', function() {
                scope.$apply(function() {
                    if(typeof  attrs.method !='undefined'){
                        scope.setCity(element.val());
                    }
                    model.$setViewValue(element.val());                
                });
            });

            scope.$watch('countryCode', function(newValue, oldValue) {
                if(newValue != oldValue){
                    var options_new = {
                        types: ['(cities)'],
                        componentRestrictions: {
                            country: scope.countryCode, 
                        }
                    };
                    google.maps.event.removeListener(autocompleteLsr);
                    $(".pac-container").remove();

                    scope.gPlace = new google.maps.places.Autocomplete(element[0], options_new);
                    google.maps.event.addListener(scope.gPlace, 'place_changed', function() {
                        scope.$apply(function() {
                            if(typeof  attrs.method !='undefined'){
                                scope.setCity(element.val());
                            }
                            // scope.$parent.$parent.$parent.$parent.artist.city = element.val();
                            model.$setViewValue(element.val());
                        });
                    });
                }
            });
        }
    };
});