angular.module('app.config')

.factory('AuthInteceptor', function($q, $rootScope, $injector,SharedData, $window, $location) {

    return {
        'response': function(r) {
            if (r.status == 401) {
                $window.globalObj.user_type = null;
                $rootScope.user = null;
                SharedData._user = null;
                $q.reject(r);
                return $location.path('/');
            }
            return r;
        },
        'responseError': function(rejection){
            if (rejection.status == 401) {
                $window.globalObj.user_type = null;
                $rootScope.user = null;
                SharedData._user = null;
                $window.location.href = "/";
            }
            return $q.reject(rejection);
        }
    };
})

.config(function($httpProvider, $provide, $controllerProvider, $injector) {
    $controllerProvider.allowGlobals();
    $httpProvider.interceptors.push('AuthInteceptor');
});