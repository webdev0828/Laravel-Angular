angular.module('app.config')

.factory('ResponseInteceptor', function($q){
        return {
            'response': function(r) {
                if(angular.isObject(r.data)){
                    customLib.typeCastJson(r.data);
                }
                // do something on success
                return r;
            }
        };
})

.config(['$httpProvider', function($httpProvider) {
    $httpProvider.interceptors.push('ResponseInteceptor');
}]);