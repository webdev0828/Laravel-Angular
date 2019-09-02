(function () {
    angular.module('app.directives')
    .directive('countdown', [
        'Util',
        '$interval',
        '$rootScope',
        function (Util, $interval,$rootScope) {
            return {
                restrict: 'A',
                scope: { date: '@' },
                link: function (scope, element) {
                    var future;
                    future = new Date(scope.date);
                    $interval(function () {
                        var diff;
                        diff = Math.floor((future.getTime() - new Date().getTime()) / 1000);
                        if(diff > 0){
                            return element.text(Util.dhms(diff));
                        }
                        else{
                            $rootScope.canSubmit = true;
                        }
                        // return element.text(Util.dhms(diff));
                    }, 1000);
                }
            };
        }
    ]).factory('Util', [function () {
            return {
                dhms: function (t) {
                    var days, hours, minutes, seconds;
                    days = Math.floor(t / 86400);
                    t -= days * 86400;
                    hours = Math.floor(t / 3600) % 24;
                    t -= hours * 3600;
                    minutes = Math.floor(t / 60) % 60;
                    t -= minutes * 60;
                    seconds = t % 60;
                    return [
                        days + 'D',
                        hours + 'H',
                        minutes + 'M',
                        seconds + 'S'
                    ].join(' : ');
                }
            };
        }]);
}.call(this));