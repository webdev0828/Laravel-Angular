angular.module('app.directives')
.directive("countNumber", function() {
    return {
        restrict: 'AE',
        scope:{
        	ngBind : "=",
        },
        link: function (scope,element,attr) {
           scope.$watch('ngBind', function (newVal,oldVal) {
                 if(newVal){  
                    $(element).counterUp({
                       delay: 10,
                        time: 1000, 
                    });
                 }
             });
     }
	}
});