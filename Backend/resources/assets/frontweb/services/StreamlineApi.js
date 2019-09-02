angular.module('app.services')
    .factory('StreamlineApi', function($resource) {
        return $resource('frontapi/streamlines/:id', {}, {
            'getSingleStreamline': {method : 'GET', url : 'frontapi/single-streamline/:id'},
            'shares' : {method:'POST',url:'frontapi/streamline-shares', isArray : false},
        });
    });