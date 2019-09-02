angular.module('app.controllers')

.controller('UserCtrl', function($scope, $uibModal ,$http, $rootScope ,$window , $state, SharedData, UserProfileApi ,ArtistApi ,Country) {
    $('body').attr('class','admin');

    // var slug = SharedData._user.slug;
    // var slug = '';
    $scope.countries = Country;

    $scope.changeCountry = function(country){ 
        angular.forEach($scope.countries, function(entry){ 
            if(entry.value == country){
                $scope.selectedCountry = entry.key;
            }
        });
    }
    $scope.setCity = function(city) {
        $scope.artist.city = city;
    }

    $scope.userprofile = {};
    $scope.allGenres = SharedData._genres;
    $scope.loading = true;
    $scope.page = 0;
    $scope.lastpage = 1;
    // $scope.loading = false;
    $scope.offset = 10 ;

    $scope.$on('$stateChangeSuccess', function(event, toState, toParams, fromState, fromParams){
        var slug = $state.params.slug;
        if(!$state.params.slug){
            slug = SharedData._user.slug;
        }
        if(fromParams.slug != toParams.slug){
            $scope.getUserProfile(slug);
        }

    });

    $scope.getSlug= function (slug){
        if(slug == SharedData._user.slug && !$state.params.slug){
            $scope.getUserProfile(slug);
        }
    }

    $scope.getUserProfile = function(slug){
        UserProfileApi.get({slug:slug}, function(r){
            var parallaxImage = angular.element('.parallax-mirror img');
            parallaxImage.hide();
            $scope.userprofile = $rootScope.artist = r.data; 
            $scope.userprofile.oldBio = $scope.userprofile.bio;
            $scope.activities = r.data.activities.data;
            $scope.userprofile.city_temp = $scope.userprofile.city;
            $scope.selectedCountry = $scope.userprofile.country_code;
            $scope.page = r.data.activities.current_page;
            $scope.lastpage =r.data.activities.last_page;
            $scope.loading = false;
            $rootScope.appScope.favouriteCount = r.favouriteCount;
            $rootScope.appScope.followingCount = r.followingCount;
            $scope.followers = r.data.followers;
            $scope.userprofile.genresIds = customLib.getColumn($scope.userprofile.genres, 'id');
            $scope.allGenres = SharedData._genres;
            parallaxImage.show();
        }, function(r) {
           $scope.error = r;
           $scope.loading = false;
        });
    }

    $scope.loadMoreActivities = function (){

        if(!$scope.loading && ($scope.page !=  $scope.lastpage) && ($scope.page <=  $scope.lastpage)){
            $scope.loading = true;
            UserProfileApi.getMoreActivities({page: $scope.page+1 , limit : $scope.offset}, null, function(r) {
                for(i in r.data.activities.data) {
                    var v = r.data.activities.data[i];
                    $scope.activities.push(v);
                }
                $scope.page = r.data.activities.current_page;
                $scope.lastpage =r.data.activities.last_page;
                $scope.loading = false;
            }, function(error) {
               $scope.loading = false;
            }); 
        }
    }

    $scope.editReset = function(){
        var editBioForm=angular.element('#artist-bio').scope();
        editBioForm.editing  =true ;
        focus('email');
    }

    $scope.updateBio=function(userprofile){
    	
    	$scope.isSubmitting = true;
        UserProfileApi.bioUpdate(null,{ 'bio' : userprofile.bio},function(r){
            $scope.isSubmitting = false;
            $scope.userprofile.oldBio = r.data.bio;
            // toastr.success('Your information updated successfully.');
        }, function(r){
            $scope.isSubmitting = false;
            $scope.error = r;
        });
    }

    $scope.checkUrl = /^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/;

	$scope.update = function(artist) {
		$rootScope.showLoading = true;
        $scope.isSubmitting = true;
        var fd = new FormData();
        angular.forEach(artist, function(item, key){
            item = item ? item : '';
            fd.append(key, item);
        });

        $scope.error = null;

        UserProfileApi.updateArtist(null,fd,function(r){
            $rootScope.showLoading = false;
            $scope.isSubmitting = false;
            $scope.userprofile = $scope.$parent.userprofile = r.data;
            SharedData._user.name = $scope.userprofile.name;
            $scope.userprofile.city_temp = $scope.userprofile.city;
            if($scope.userprofile.genres){
                $scope.userprofile.genresIds = $scope.$parent.userprofile.genresIds = customLib.getColumn($scope.userprofile.genres, 'id');
            }
            // toastr.success('Your profile updated successfully.');
            // $state.go('app.page.dashboard');
            $window.location.href = "/home";
        }, function(r){
            $rootScope.showLoading = false;
            $scope.isSubmitting = false;
            $scope.error = r.data;
        });
    }

    $scope.cropImgObj = null;
    $scope.croppedImage = null;
    $scope.imageType = "";
    $rootScope.cropImageModal = function(avatarFile, imageType) {
        $('.crop-wrap').empty();

        $("#cropImageModal").one('shown.bs.modal', function() {
            $scope.imageType = imageType.split(".")[1];
            var reader = new FileReader();
            reader.onload = function (e) {
                if ($scope.imageType == "profile") {
                    $scope.cropImgObj = new Croppie($('.crop-wrap')[0], {
                        viewport: {
                            width: 568,
                            height: 568,
                            type: 'circle'
                        },
                        boundary: {
                            width: 568,
                            height: 568
                        },
                        mouseWheelZoom: false,
                        update: function() {
                            $scope.cropImgObj.result('base64').then(function(img) {
                                $scope.$apply(function() {
                                    $scope.croppedImage = img;
                                });
                            });
                        }
                    });
                } else if ($scope.imageType == "banner") {
                    $scope.cropImgObj = new Croppie($('.crop-wrap')[0], {
                        viewport: {
                            width: 568,
                            height: 140,
                            type: 'square'
                        },
                        boundary: {
                            width: 568,
                            height: 140
                        },
                        mouseWheelZoom: false,
                        update: function() {
                            $scope.cropImgObj.result('base64').then(function(img) {
                                $scope.$apply(function() {
                                    $scope.croppedImage = img;
                                });
                            });
                        }
                    });
                }

                $scope.cropImgObj.bind({
                    url: e.target.result
                });
            }
            reader.readAsDataURL(avatarFile);
        }).modal('show');
    }

    $rootScope.updateCroppedImage = function() {
        if ($scope.imageType == 'profile') {
            $rootScope.updateUserProfileCroppedImage();
        } else if ($scope.imageType == 'banner') {
            $rootScope.updateBannerCroppedImage();
        }
    }

    $rootScope.updateUserProfileCroppedImage = function(){
        var fd = new FormData();
        fd.append('file', $scope.croppedImage);
        $rootScope.showLoading = true;
        UserProfileApi.updateImage(null,fd,function(r){
            // toastr.success('Profile image updated successfully.');
            $scope.userprofile.avatar = SharedData._user.avatar = r.data.avatar ;
            $rootScope.showLoading = false;
            $("#cropImageModal").modal('hide');
        }, function(r){
            toastr.error('Something went wrong.Please try again.');
            $rootScope.showLoading = false;
        });
    }
    
    $rootScope.updateUserProfileImage = function(value){
    	
        var fd = new FormData();
        fd.append('file', value);
        $rootScope.showLoading = true;
        UserProfileApi.updateImage(null,fd,function(r){
            // toastr.success('Profile image updated successfully.');
            $scope.userprofile.avatar = SharedData._user.avatar = r.data.avatar ;
            $rootScope.showLoading = false;
        }, function(r){
            toastr.error('Something went wrong.Please try again.');
            $rootScope.showLoading = false;
        });
    }

    $rootScope.updateBannerCroppedImage = function(){
        var fd = new FormData();
        fd.append('file', $scope.croppedImage);
        $rootScope.showLoading = true;
        UserProfileApi.bannerUpdate(null,fd,function(r){
            // toastr.success('Banner image updated successfully.');
            $scope.userprofile.cover = r.data.cover;
            $rootScope.showLoading = false;
            $("#cropImageModal").modal('hide');
        }, function(r){
            toastr.error('Something went wrong.Please try again.');
            $rootScope.showLoading = false;
        });
    }

    $rootScope.updateBannerImage = function(value){
    	var fd = new FormData();
        fd.append('file', value);
        $rootScope.showLoading = true;
        UserProfileApi.bannerUpdate(null,fd,function(r){
            // toastr.success('Banner image updated successfully.');
            $scope.userprofile.cover = r.data.cover;
            $rootScope.showLoading = false;
        }, function(r){
            toastr.error('Something went wrong.Please try again.');
            $rootScope.showLoading = false;
        });
    }

    // $scope.deleteAccount = function(){

    //     if(confirm('Are you sure, do you want to delete your account permanently?')) {
    //         UserProfileApi.delete(function(r){
    //             $window.location.href = "/";
    //         }, function(r){
    //             toastr.error('Something went wrong.Please try again.');
    //         });
    //     }
    // }

    $scope.selectBanner = function() {

        $uibModal.open({
            templateUrl: 'frontweb/tpl/artist/modals/update-banner-modal.html',
            controller: function($scope, $rootScope  ,$uibModalInstance, SharedData, parentScope, UserProfileApi, $timeout){
                $scope.banners = SharedData._banners;
                
                $scope.cancel = function () {
                    $uibModalInstance.dismiss('cancel');
                };

                $scope.update = function (details) {
                    var fd = {file : details};
                    $rootScope.showLoading = true;
                    UserProfileApi.bannerSelect(null,fd,function(r){
                        parentScope.userprofile.cover = r.data;    
                        // toastr.success('Banner image updated successfully.');
                        $rootScope.showLoading = false;
                        $uibModalInstance.dismiss('cancel');
                    }, function(r){
                        // $scope.error = r.message;
                        // toastr.error($scope.error);
                        $rootScope.showLoading = false;
                        toastr.error('Something went wrong.Please try again.');
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
                UserProfileApi : function(){
                    return UserProfileApi;
                }

            }
        });
        return false;
    }

    $scope.changePassword = function(pass){
        $rootScope.showLoading = true;
        $scope.error = '';
        $scope.incorrect = false;
        $scope.isSubmitting = true;
        ArtistApi.changePassword(null,{email:SharedData._user.email,password:pass.old_password,new_password:pass.new_password,password_confirmation:pass.password_confirmation},function(r){
            $rootScope.showLoading = false;
            // toastr.success("Password updated successfully.")
            swal("Your password has been changed successfully!");
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

    $scope.userImagePreview = function(path, name){
        $rootScope.artistProfileImage = path;
        $rootScope.artistName = name;
        $('#imagePreview').modal();

    }
});