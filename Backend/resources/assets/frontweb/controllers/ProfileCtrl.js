angular.module('app.controllers')

.controller('ProfileCtrl', function($scope, $http , ArtistApi , ProfileApi ,$rootScope , SharedData ,$state , $window , $stateParams, $uibModal ,Country, $q) {
	
    $scope.checkUrl = /^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/;
    $scope.countries = Country;

    $scope.allGenres = [];
    ProfileApi.get(null, function(r) {
         $scope.genres = r.data.genres;
         $scope.labelgenres = r.data.labelGenres;
            if(r.data.genres.length > 0){
                if(r.data.genres[0])
                    $scope.allGenresModel1.id = r.data.genres[0].id;
                if(r.data.genres[1])
                    $scope.allGenresModel2.id = r.data.genres[1].id; 
                if(r.data.genres[2])
                    $scope.allGenresModel3.id = r.data.genres[2].id;
            }
            if(r.data.labelGenres.length > 0)
                $scope.allGenresModel4.id =  r.data.labelGenres[0].id
            $scope.heightResize();
    });

    $scope.setCity = function(city) {
        $scope.artist.city = city;
    }

    // $scope.youtubeChannelExist = true;
    // $scope.checkYoutubeChannel = function(channel){
    //     // "https://www.googleapis.com/youtube/v3/channels?part=contentDetails&forUsername=SandyChannel&key=AIzaSyDXkIvSFaRbTqD9iKMiNpVPtGhuuEuwP10"
    //     // var win = window.open ("https://www.googleapis.com/youtube/v3/channels?part=contentDetails&forUsername=SandyChannel&key=AIzaSyDXkIvSFaRbTqD9iKMiNpVPtGhuuEuwP10");
    //     var deferred = $q.defer();

    //     $.get(
    //     "https://www.googleapis.com/youtube/v3/channels",{    
    //         part : 'contentDetails',
    //         forUsername:channel,
    //         key: $window.globalObj.google_key},
    //         function(data){
    //             if(data.items.length > 0){
    //                 $scope.youtubeChannelExist = true;
    //                 $( ".row" ).click();
    //             }
    //             else{
    //                 $scope.youtubeChannelExist = false;
    //                 $( ".row" ).click();
    //             }

    //             deferred.resolve($scope.youtubeChannelExist);
    //         }
    //     );

    //     return deferred.promise;

    // }

    $scope.allGenresModel1 = [], $scope.allGenresModel2 = [], $scope.allGenresModel3 = [],$scope.allGenresModel4 = [];
    // $scope.allGenresData1 = angular.copy(SharedData._genresAll);
    // $scope.allGenresData4 = angular.copy(SharedData._genresAll);
    $scope.allGenresData1 = angular.copy(SharedData._genres);
    $scope.allGenresData4 = angular.copy(SharedData._genres);
    
    $scope.generError = false;
    $scope.allGenresSettings = {
                                smartButtonMaxItems: 1,
                                enableSearch: false,
                                selectionLimit: 1,
                                closeOnSelect : true,
                                scrollable: true,
                                smartButtonTextConverter: function(itemText, originalItem) {
                                    return itemText;
                                }
                            };
   
    $scope.allGenresText1 = { buttonDefaultText:'Select' };
    $scope.allGenresText2 = { buttonDefaultText:'Select' };
    $scope.allGenresText3 = { buttonDefaultText: 'Select'};
    $scope.allGenresText4 = { buttonDefaultText: 'Select'};


    $scope.changeCountry = function(country){ 
        angular.forEach($scope.countries, function(entry){
            if(entry.value == country){ 
                $scope.selectedCountry = entry.key;
            }
        });
    }

    $scope.genreEvents = {
        onItemSelect: function(evt) {
            $scope.heightResize();
        }
    };

    
    // $scope.$watch(function() {
    //     var yourProfileOffset = $('#resizeElement').offset();
    //     var yourProfileHeight = $('#resizeElement').height();
    //     var yourProfileBottom = yourProfileOffset.top + yourProfileHeight;
    //     return yourProfileBottom; 
    //  }, function(newValue,oldValue){
    //         if(newValue != oldValue ){ 
    //             yourProfileBottom = newValue;

                
                
    //         }
    //     });
    
    $scope.heightResize = function(){
            
    }

    $scope.validateGenere = function(){
        var flag = 1;
        if($scope.allGenresModel1.id && $scope.allGenresModel2.id ){    
            if($scope.allGenresModel1.id == $scope.allGenresModel2.id ){
                $scope.generError = true;
                flag = 0;
                return false;
            }else{
                flag = 1;
            }
        }
        if($scope.allGenresModel2.id && $scope.allGenresModel3.id ){ 
            if($scope.allGenresModel2.id == $scope.allGenresModel3.id ){
                $scope.generError = true;
                flag = 0;
                return false;
            }else{
                flag = 1;
            }
        }
        if($scope.allGenresModel1.id && $scope.allGenresModel3.id ){ 
            if($scope.allGenresModel1.id == $scope.allGenresModel3.id ){
                $scope.generError = true;
                flag = 0;
                return false;
             }else{
                flag = 1;
            }
        }
        if(flag == 0){
            return false;
        }else{
            return true;
        }   
    }

	$scope.update = function(artist) {
        // if($scope.artist.youtube_channel) {
        //     var ytChannelExist = true;
        //     $scope.checkYoutubeChannel($scope.artist.youtube_channel).then(function(t){
        //         ytChannelExist = t;
        //         if(ytChannelExist){
        //             $scope.updateArtist(artist);
        //         } else {
        //             return false;
        //         }
        //     });
        // } else {
            $scope.updateArtist(artist);
        // }
    }

    $scope.updateArtist = function(artist) {
        $rootScope.showLoading = true;
        $scope.artist.genresIds = [] ;
        $scope.artist.labelgenresIds = [] ;
        $scope.artist.genresIds.push($scope.allGenresModel1 ? $scope.allGenresModel1.id : null);
        $scope.artist.genresIds.push($scope.allGenresModel2 ? $scope.allGenresModel2.id : null);
        $scope.artist.genresIds.push($scope.allGenresModel3 ? $scope.allGenresModel3.id : null);
        $scope.artist.labelgenresIds.push($scope.allGenresModel4 ? $scope.allGenresModel4.id : null);

        $scope.artist.genresIDs = [];
        $scope.artist.labelgenresID = [];
        
        $scope.isSubmitting = true;
        var fd = new FormData();
        angular.forEach(artist, function(item, key){
            item = item ? item : '';
            fd.append(key, item);
        });

        fd.append('_token', $window.globalObj._token);
        $scope.error = null;

        ArtistApi.updateArtist(null,fd,function(r){
            $rootScope.showLoading = false;
            $scope.isSubmitting = false;
            $scope.artist = $scope.$parent.artist = r.data;
            $scope.artist.city_temp = $scope.artist.city;
            SharedData._user.name = $scope.artist.name;
            SharedData._user.first_name = $scope.artist.first_name;
            SharedData._user.last_name = $scope.artist.last_name;

            if($scope.artist.genres){
                $scope.artist.genresIds = $scope.$parent.artist.genresIds = customLib.getColumn($scope.artist.genres, 'name');
            }
            if($scope.artist.labelGenres){
               $scope.artist.labelgenresIds = $scope.$parent.artist.labelgenresIds = customLib.getColumn($scope.artist.labelGenres, 'name');
            } 
            // $state.go('app.artist.dashboard');
            $window.location.href = "/dashboard";
            // toastr.success('Your profile updated successfully.');
        }, function(r){
            $rootScope.showLoading = false;
            $scope.isSubmitting = false;
            $scope.error = r.data;
        });
    }


    $scope.changePassword = function(old_password,new_password,password_confirmation){
        $rootScope.showLoading = true;
        $scope.error = '';
        $scope.incorrect = false;
        $scope.isSubmitting = true;
        ArtistApi.changePassword(null,{email:SharedData._user.email,password:old_password,new_password:new_password,password_confirmation:password_confirmation},function(r){
            $rootScope.showLoading = false;
            swal("Your password has been changed successfully!");
            // toastr.success("Password updated successfully.")
            $scope.error = $scope.password_confirmation = $scope.new_password = $scope.old_password = '';
            $scope.incorrect = false;
            $scope.isSubmitting = false;
            var changePassForm=angular.element('.ChangePasswordForm').scope();
            if(changePassForm){
                $(".ChangePasswordForm")[0].reset();
                changePassForm.ChangePasswordForm.$setPristine();
                changePassForm.ChangePasswordForm.$setUntouched();
            }
        },function(r){
            $rootScope.showLoading = false;
            $scope.isSubmitting = false;
            if(r.data){
                $scope.error = r.data;
            }
            if(r.data.status == 'incorrect'){
                $scope.incorrect = true;
            }
        })
    }
    // $scope.updateProfileImage = function(){
    //     var fd = new FormData();
    //     fd.append('file', $scope.artist.profile);
    //     fd.append('_token', $window.globalObj._token);
    //     ArtistApi.updateImage(null,fd,function(r){
    //         toastr.success('Profile image updated successfully.');
    //         // getArtistProfile();
    //         $scope.artist.avatar = $rootScope.user.avatar = r.data.avatar ;
    //     }, function(r){
    //         // $scope.error = r.message;
    //         toastr.error('Something went wrong.Please try again.');
    //     });
    // }

    $rootScope.updateBannerImage = function(avatarFile){
        var fd = new FormData();
        fd.append('file', avatarFile);
        fd.append('_token', $window.globalObj._token);
        $rootScope.showLoading = true;
        ArtistApi.bannerUpdate(null,fd,function(r){
            // toastr.success('Banner image updated successfully.');
            $scope.artist.cover = r.data.cover;
            $rootScope.showLoading = false;
        }, function(r){
            // $scope.error = r.message;
            // toastr.error($scope.error);
            toastr.error('Something went wrong.Please try again.');
            $rootScope.showLoading = false;
        });
    }
    
    $scope.selectBanner = function() {

        $uibModal.open({
            templateUrl: 'frontweb/tpl/artist/modals/update-banner-modal.html',
            controller: function($scope, $rootScope  ,$uibModalInstance, SharedData, parentScope, ArtistApi, $timeout){
                $scope.banners = SharedData._banners;
                
                $scope.cancel = function () {
                    $uibModalInstance.dismiss('cancel');
                };

                $scope.update = function (details) {
                    var fd = {file : angular.copy(details)};
                    $scope.isSubmitting = true;
                    $rootScope.showLoading = true;
                    ArtistApi.bannerSelect(null,fd,function(r){
                        // toastr.success('Banner image updated successfully.');
                        $rootScope.showLoading = false;
                        $uibModalInstance.dismiss('cancel');
                        parentScope.artist.cover = r.data;
                        /*setTimeout(function() {
                            // toastr.success('Banner image updated successfully.');
                            $uibModalInstance.dismiss();
                            $scope.isSubmitting = false;
                            $rootScope.showLoading = false;
                        },10);
                        $scope.$parent.artist.cover = r.data;*/

                        //$rootScope.$broadcast('parallox-image-change', {url: r.data});
                    }, function(r){
                        // $scope.error = r.message;
                        // toastr.error($scope.error);
                        toastr.error('Something went wrong.Please try again.');
                        $scope.isSubmitting = false;
                        $rootScope.showLoading = false;
                    });
                };
            },
            resolve: {
                SharedData: function () {
                    return SharedData;
                },
                parentScope: function() {
                    return $scope;
                },
                ArtistApi : function(){
                    return ArtistApi;
                }

            }
        });
        return false;
    }

    // $scope.changePermissions = function(type,value) { 
    //     ArtistApi.changePermission(null,{type:type,status: type =='email' ? $scope.artist.isEmail : $scope.artist.isNotification},function(r){
    //         type == 'email' ? $scope._user.isEmail = r.data : $scope._user.isNotification = r.data;
    //     });
    // }
});