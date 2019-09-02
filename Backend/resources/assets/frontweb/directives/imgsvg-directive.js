angular.module('app.directives')
.directive('svg', function($http) {
    return {
        restrict: 'C',
        link: function(scope, element) { 
            var t = element,
                i = t.attr("class"),
                n = t.attr("src");
            if(n) {
                jQuery.get(n, function(n) {
                    var s = jQuery(n).find("svg"); 
                    "undefined" != typeof i && (s = s.attr("class", i + " replaced-svg")), 
                    s = s.removeAttr("xmlns:a"), 
                    angular.element('svg title').remove(),
                    t.replaceWith(s)
                }, "xml");
            }            
        }
    };
});