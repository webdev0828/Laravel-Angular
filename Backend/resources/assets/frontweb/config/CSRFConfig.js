angular.module('app.config')
.factory('CSRFInteceptor', function($window){
    return {
        // optional method
        'request': function(config) {
            // do something on success
            // !!! adjust the config object
            // it will be added to every made request
            if(config.method=="POST"){
                if(config.data !=null)
                config.data._token = $window.globalObj._token; 
                // config.headers['x-csrf-token'] = $window.globalObj._token;
            }
            return config;
        }
    };
})

.config(['$httpProvider', function($httpProvider) {
    $httpProvider.interceptors.push('CSRFInteceptor');
}]);