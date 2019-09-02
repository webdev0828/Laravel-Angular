angular.module('mainCtrl', [])


.controller('ProfileController', function($scope, $http, $location, $rootScope, Artist) {
        $scope.tabs = [{heading: 'Dashboard', content: 'dashboard', active: true},
                       {heading: 'Profile', content: 'profile', active: false}];        

        $scope.setTabContent = function(path) {
            $scope.tabContentUrl = path;
            $location.path(path);
        }

        Artist.getGenre()
          .success(function(data) {
              $scope.roles = data;
              console.log(data);
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
      $rootScope.updateUser = function(){
          localStorage.setItem('user', JSON.stringify($rootScope.currentUser));
      } 

      $scope.getProfile = function() {
          Artist.getUser()
              .success(function(data) {
                  $scope.user.roles = data.genre;
                  $scope.profile = data;
                  $rootScope.currentUser = data;
              })
              .error(function(data) {});
      };

      $scope.messages = {};
      // function to handle submitting the form
      $scope.updateProfile = function() {

          var fd = new FormData();
          angular.forEach($scope.profile, function(item, key) {
              fd.append(key, item);
          });

          Artist.update(fd)
              .success(function(data) {
                  console.log(data);
                  // window.location.href = 'home';
                  $rootScope.currentUser = data;
                  console.log('true');
                  $rootScope.updateUser();
                  // window.location.href = location.href;
              })
              .error(function(data) {
                  if (data) {
                      // Showing errors.
                      $scope.messages = data;
                  }
              });
      };
      $scope.getArtists = function(){
        Artist.getAllArtists()
          .success(function(data) {
              $scope.artists = data;

          })
          .error(function(data) {
              console.log('somthing wrong');
          });
      };
    })
    .directive('fileModel', ['$parse', function($parse) {
        return {
            restrict: 'A',
            link: function(scope, element, attrs) {
                var model = $parse(attrs.fileModel);
                var modelSetter = model.assign;

                element.bind('change', function() {
                    scope.$apply(function() {
                        modelSetter(scope, element[0].files[0]);
                    });
                });
            }
        };
    }]);