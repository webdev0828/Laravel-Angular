angular.module('app.directives')
.directive("showcounter", function() {
    return {
        restrict: 'A',
        link: function (scope,element) {
        	var getId = document.getElementById("artists-say");
            new Waypoint({
                element: getId,
                handler: function() {
                    $(".counter").counter({ 
                        autoStart: !0,
                        duration: 5e3,
                        countFrom: 0,
                        countTo: 0,
                        runOnce: !0,
                        placeholder: "",
                        easing: "easeOutCubic"
                    })
                }
            });
        }
    }
});