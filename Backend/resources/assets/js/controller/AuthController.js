angular.module('mainCtrl', [])



.controller('AuthController', function($scope, $http, $rootScope, Artist) {
    $scope.name = "vachanesh";

    Artist.getGenre()
        .success(function(data) {
            $scope.roles = data;
            console.log(data);
            // $scope.profile.options = data.genre;
        })
        .error(function(data) {
            // console.log('false');
        });

    $scope.user = {
      roles: []
    };

    $scope.profile = {
        genre: []
    };  
    $scope.check = function(value) {
      // console.log(this);
      if($scope.user.roles.indexOf(value) !== -1) {
        var idx = $scope.user.roles.indexOf(value);
        $scope.user.roles.splice(idx, 1);
        
      }
      else
      {
        if($scope.user.roles.length <= 2){
          $scope.user.roles.push(value);
        }        
        else{
          $scope.genre_error = true;
          $(this).prop('checked', false);
        }
          
      }
      
      $scope.profile.genre = $scope.user.roles; 
      console.log($scope.profile.genre); 
    };


    // function to handle submitting the form
    $scope.submitLogin = function() {
        var userdata = {
            email: $scope.email,
            password: $scope.password
        };

        Artist.login(userdata)
            .success(function(data) {
                console.log(data);
                // $scope.user = data;
                $rootScope.currentUser = data;
                // $rootScope.user = data;
                window.location.href = 'home';
            })
            .error(function() {
                console.log('false');
            });
    };
    $scope.submitProfile = function() {
        $scope.profile.genre = $scope.profile.genre.toString();
        $scope.profile.user_type = "artist";

        Artist.save($scope.profile)
            .success(function(response) {
                console.log(response);
                $scope.errors = response;
                window.location.href = 'login';
            })
            .error(function(response) {
                if (response) {
                    $scope.messages = response;
                }
                console.log('error');
            });
    };
     

    $scope.forgotPassword = function() {
        Artist.forgotPass($scope.email)
            .success(function() {
                window.location.href = 'login';
            })
            .error(function() {
                console.log('false');
            });
    };
});