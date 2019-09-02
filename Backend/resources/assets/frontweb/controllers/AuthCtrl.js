angular.module('app.controllers')
.controller('AuthCtrl', function($scope, $http, AuthService, $rootScope, $state, $stateParams, $timeout , $window ,SoundCloudApi,FacebookService, SharedData,$uibModal,ArtistApi) {

 	if($state.current.category && $state.current.category == 'auth')
		$('body').attr('class','landing-page-modals signinpage');

 	$scope.user = {};

 	$scope.signup = function (userData) {
		$rootScope.showLoading = true;
		$scope.errors='';
		$scope.error ='';
  		userData.token = $window.globalObj._token;
  		$scope.isSubmitting = true;
  		AuthService.register(null, userData,  function(r){
  			$rootScope.showLoading = false;
			$scope.isSubmitting = false;
  			$scope.errors='';
			$scope.error ='';

			$('#joinUsUserModal').modal('hide');
			if(r.provider == 'facebook' || r.provider == 'soundcloud'){
				if(r.data.user_type=="artist"){
					if(r.data.last_login_at ) {
						$window.location.href = $state.href('app.page.dashboard');
						// $state.go('app.artist.dashboard');
					}
					else {
						$window.location.href = $state.href('app.artist.profile');
						// $state.go('app.artist.profile');
					}
				}
				else if(r.data.user_type=="stm_user"){

					if(r.data.last_login_at){
						$window.location.href = $state.href('app.page.dashboard');
						// $state.go('app.page.dashboard');
					}
					else {
						$window.location.href = $state.href('app.user.profile');
					}
				}
			}
			else
				// toastr.success('Your account has been created, please check email to activate your account.');
				swal("Thank you for signing up with Sore Thumb Media!","We have sent an activation link to your email address. Click the link in order to gain access to your Sore Thumb Media account!");
				if($state.current.category && $state.current.category == 'auth')
					$state.go('signin');
	 	}, function(r) {
	 		$rootScope.showLoading = false;
	 		$scope.isSubmitting = false;
			$scope.errors = r.data;
	    	$scope.error = r.data.error ? r.data.error : '';
	    	if(r.data.message){
	    		toastr.error(r.data.message);
	    	}
		});
	}
	$scope.signupFb = function (userData) {
  		userData.token = $window.globalObj._token;
  		$scope.isSubmitting = true;
  		AuthService.Fbregister(null, userData,  function(r){
  			$('#selectUserType').modal('hide');
			$scope.isSubmitting = false;
			if(r.data.user_type=="artist"){
				if(r.data.last_login_at ) {
					$window.location.href = $state.href('app.page.dashboard');
					// $state.go('app.artist.dashboard');
				}
				else {
					$window.location.href = $state.href('app.artist.profile');
					// $state.go('app.artist.profile');
				}
			}
			else if(r.data.user_type=="stm_user"){

				if(r.data.last_login_at){
					$window.location.href = $state.href('app.page.dashboard');
					// $state.go('app.page.dashboard');
				}
				else {
					$window.location.href = $state.href('app.user.profile');
				}
			}

	 	}, function(r) {

	 		$scope.isSubmitting = false;
			$scope.errors = r.data;
	    	$scope.error = r.data.error ? r.data.error : '';
	    	if(r.data.message){
	    		toastr.error(r.data.message);
	    	}
		});
	}

	$scope.signin = function (userData) {
		$scope.isSubmitting = true;
		$rootScope.showLoading = true;
		var user = AuthService.login(null, userData,  function(r){
			$rootScope.showLoading = false;
			$scope.isSubmitting = false;
			$scope.errors='';
			$scope.error ='';
			$('#signInModal').modal('hide');
			//$window.location.reload();
			$window.location.replace($state.href('app.page.dashboard'));

				// if(user.user_type=="artist"){
				// 	if(user.last_login_at ) {
				// 		$window.location.href = $state.href('app.page.dashboard');
				// 		// $state.go('app.artist.dashboard');
				// 	}
				// 	else {
				// 		$window.location.href = $state.href('app.artist.profile');
				// 		// $state.go('app.artist.profile');
				// 	}
				// }
				// else if(user.user_type=="stm_user"){

				// 	if(user.last_login_at){
				// 		$window.location.href = $state.href('app.page.dashboard');
				// 		// $state.go('app.page.dashboard');
				// 	}
				// 	else {
				// 		$window.location.href = $state.href('app.user.profile');
				// 	}
				// }

		}, function(r) {
			$rootScope.showLoading = false;
			$scope.isSubmitting = false;
			$scope.errors = r.data;
	    	$scope.error = r.data.error ? r.data.error : '';
		});
	}

	$scope.forgotPassword = function (formData) {
		$scope.isSubmitting = true;
		$rootScope.showLoading = true;
		AuthService.forgotPassword(null, formData,  function(r){
			$scope.isSubmitting = false;
			$rootScope.showLoading = false;
			$scope.errors='';
			$scope.error ='';
			$('#forgotPasswordModal').modal('hide');
	    	// toastr.success('Password reset link has been sent to your email address.');
	    	swal("Password reset link has been sent to your email address.");
	    	if($state.current.category && $state.current.category == 'auth')
					$state.go('signin');
		}, function(r) {
			$rootScope.showLoading = false;
			$scope.isSubmitting = false;
			$scope.errors = r.data;
	    	$scope.error = r.data.error ? r.data.error : '';
		});
	}

	$scope.resetPassword = function (formData) {
		$scope.isSubmitting = true;
		$rootScope.showLoading = true;
		formData.reset_code = $stateParams.resetCode;
		AuthService.resetPassword(null, formData,  function(r){
			$scope.isSubmitting = false;
			$rootScope.showLoading = false;
			$scope.errors='';
			$scope.error ='';
			$('#resetPasswordModal').modal('hide');
			$('#resetPasswordForm')[0].reset();
			// toastr.success('Your password has been reset successfully.');
			swal("Your password has been reset successfully.");
	    	// r.redirect ? $state.go(r.redirect) : $state.go('index');
	    	// $state.go('index');
	    	if(r.redirect){
	    		$state.go('app.subscriptions');
	    	}
	    	else{
	    		$state.go('index');
	    	}
		}, function(r) {
			$rootScope.showLoading = false;
			$scope.isSubmitting = false;
			$scope.errors = r.data;
	    	$scope.error = r.data.error ? r.data.error : '';
	    });

	}

	$scope.logout = function() {
        return $http.get('frontapi/auth/logout')
        .then(function(r) {
        	sessionStorage.removeItem('currentTrack');
            $window.location.href = "/";
            // $state.go('index');
        });
    }

    FacebookService.initialize($window.globalObj.facebook);
    $scope.facebookConnect = function () {
    	$rootScope.showLoading = true;
      //   FB.getLoginStatus(function(response){
      //       if(response.status == 'connected'){
      //       	id = response.authResponse.userID;
      //       	AuthService.checkFbUser(null, {'id':id},  function(r){
      //       		if(r.data == 'Notexist'){
      //       			FB.api('/me', {fields: 'name,id,email,cover,first_name,last_name,picture'}, function(response) {
		    //         		$rootScope.showLoading = false;
						// 	$scope.user.name = response.name;
						// 	$scope.user.first_name = response.first_name;
						// 	$scope.user.last_name = response.last_name;
						// 	// $scope.user.cover= response.cover;
						// 	$scope.user.email = response.email;
						// 	$scope.user.provider = 'facebook';
						// 	$scope.user.provider_user_id = response.id;
						// 	$scope.$apply();
						// });
      //       		}
      //       		else{
      //       			if(r.data.user_type=="artist"){
						// 	if(r.data.last_login_at ) {
						// 		$window.location.href = $state.href('app.page.dashboard');
						// 		// $state.go('app.artist.dashboard');
						// 	}
						// 	else {
						// 		$window.location.href = $state.href('app.artist.profile');
						// 		// $state.go('app.artist.profile');
						// 	}
						// }
						// else if(r.data.user_type=="stm_user"){

						// 	if(r.data.last_login_at){
						// 		$window.location.href = $state.href('app.page.dashboard');
						// 		// $state.go('app.page.dashboard');
						// 	}
						// 	else {
						// 		$window.location.href = $state.href('app.user.profile');
						// 	}
						// }
      //       		}

      //       	});
      //       }
      //       else{
            	$rootScope.showLoading = false;
                FB.login(function(response){
                	$rootScope.showLoading = true;
                    if(response.authResponse && response.authResponse == null){
                    	$rootScope.showLoading = false;
                        return false;
                    }
                    else if(response.authResponse && response.status == 'connected'){
                    	$rootScope.showLoading = true;
                    	id = response.authResponse.userID;
		            	AuthService.checkFbUser(null, {'id':id},  function(r){
		            		if(r.data == 'Notexist'){
		            			FB.api('/me', {fields: 'name,id,email,cover'}, function(response) {
				            		$rootScope.showLoading = false;
									$scope.user.name = response.name;
									$scope.user.first_name = response.first_name;
									$scope.user.last_name = response.last_name;
									$scope.user.cover= response.cover
									$scope.user.email = response.email;
									$scope.user.provider = 'facebook';
									$scope.user.provider_user_id = response.id;
									$scope.$apply();
								});
		            		}
		            		else{
								if(r.data.user_type=="artist"){
									if(r.data.last_login_at ) {
										$window.location.href = $state.href('app.page.dashboard');
										// $state.go('app.artist.dashboard');
									}
									else {
										$window.location.href = $state.href('app.artist.profile');
										// $state.go('app.artist.profile');
									}
								}
								else if(r.data.user_type=="stm_user"){

									if(r.data.last_login_at){
										$window.location.href = $state.href('app.page.dashboard');
										// $state.go('app.page.dashboard');
									}
									else {
										$window.location.href = $state.href('app.user.profile');
									}
								}
		            		}

		            	});
                    }
                    else{
                    	$rootScope.showLoading = false;
                    }
                }, {scope: 'email, publish_actions'});
            // }
        // });
    }

    $scope.facebookSignin = function () {
    	$rootScope.showLoading = true;

            	$rootScope.showLoading = false;
                FB.login(function(response){
                	$rootScope.showLoading = true;
                    if(response.authResponse && response.authResponse == null){
                    	$rootScope.showLoading = false;
                        return false;
                    }
                    else if(response.authResponse && response.status == 'connected'){
                    	$rootScope.showLoading = true;
                    	id = response.authResponse.userID;
		            	AuthService.checkFbUser(null, {'id':id},  function(r){
		            		if(r.data == 'Notexist'){
		            			$rootScope.showLoading = false;
		            			toastr.error("Please signup with facebook");
		            		}
		            		if(r.data.user_type=="artist"){
		            			if(r.data.last_login_at ) {
		            				$window.location.href = $state.href('app.page.dashboard');
		            				// $state.go('app.artist.dashboard');
		            			}
		            			else {
		            				$window.location.href = $state.href('app.artist.profile');
		            			}
		            		}
		            		else if(r.data.user_type=="stm_user"){
		            			if(r.data.last_login_at){
		            				$window.location.href = $state.href('app.page.dashboard');
		            			}
		            			else {
		            				$window.location.href = $state.href('app.user.profile');
		            			}
		            		}
		            	});
                    }
                    else{
                    	$rootScope.showLoading = false;
                    }
                }, {scope: 'email, publish_actions'});
        //     }
        // });
    }





    $scope.reset = function(){
    	$rootScope.showLoading = false;
    	var signupForm=angular.element('.signup').scope();
    	var signinForm=angular.element('.signin').scope();
    	var forgotForm=angular.element('.forgotPassword').scope();
    	var contactForm=angular.element('#contactUsModal').scope();
    	var submitTrackDemoForm=angular.element('#submitDemoModal').scope();
    	if(submitTrackDemoForm){
    		$('#submitDemoModal input[type=checkbox]').attr('checked',false);
    		submitTrackDemoForm.user={};
    		submitTrackDemoForm.connect = false;
    		submitTrackDemoForm.soundCloudTracks={};
    		submitTrackDemoForm.signupForm.$setPristine();
	    	submitTrackDemoForm.signupForm.$setUntouched();
    	}
    	if(contactForm){
    		$("#contactForm")[0].reset();
    		contactForm.contact={};
    		if($rootScope.user){
    			contactForm.contact.name = $rootScope.user.name;
    			contactForm.contact.email = $rootScope.user.email;
    		}
    		contactForm.contactForm.$setPristine();
	    	contactForm.contactForm.$setUntouched();
    	}
    	if(signupForm){
    		$("#signupForm")[0].reset();
    		signupForm.soundCloudTracks = [];
    		signupForm.connect = false;
    		signupForm.user={};
    		signupForm.signupForm.$setPristine();
	    	signupForm.signupForm.$setUntouched();
    	}
    	if(signinForm){
			$("#signinForm")[0].reset();
			signinForm.user={};
    		signinForm.signinForm.$setPristine();
	    	signinForm.signinForm.$setUntouched();
    	}
    	if(forgotForm){
    		$("#forgotPasswordForm")[0].reset();
    		// forgotForm.user={};
    		forgotForm.forgotPasswordForm.$setPristine();
	    	forgotForm.forgotPasswordForm.$setUntouched();
    	}
	  }

	  $scope.setArtistAsDefault = function() {
	  	// setTimeout(function() {
	  		var signupForm=angular.element('.signup').scope();
    		signupForm.signupForm.$setPristine();
	    	signupForm.signupForm.$setUntouched();
	    	signupForm.user.user_type = 'artist';
	  	// }, 10);
	  }
  	$window.soundcloudApp = {
        connectCallback : null
    };
    var w = 500, h = 400;
    var left = (screen.width/2)-(w/2);
    var top = (screen.height/2)-(h/2);
    $scope.connectSc = function (){
  	  $scope.loading = true;
  	  $window.soundcloudApp.connectCallback = function(status, token, user){

  		  if(status == 'success'){
  		  	// user = user;
  		  	if(user) {
	  		  	$scope.user.access_token = token;

	  		  	$scope.connect = true;
	  		  	SoundCloudApi.getScTracks({token:token,id:user.id}, function(r) {
	  		  		$scope.loading = false;
	  		  		$scope.soundCloudTracks = r.soundCloudTracks;
		          },function(r){
		              $scope.loading = false;
		              toastr.error(r.data.message);
		          });
  		  	}
  		  }
      };


      var win = window.open ($rootScope.baseUrl+'/sc/login',"Soundcloud","menubar=1,resizable=1,width="+w+",height="+h+",top="+top+',left='+left);
      setWindowTitle(win, 'Soundcloud');
    }

	$scope.soundcloudConnect = function () {
		$rootScope.showLoading = true;
		$window.soundcloudApp.connectCallback = function(status, token, user){
			/*$window.location.href = $state.href('app.page.dashboard');*/
			if(status == 'success'){
				if(user) {
					$scope.user.access_token = token;
					$scope.connect = true;

					AuthService.checkSCUser(null, {'id':user.id}, function(r) {
						if (r.data == 'Notexist') {
							$rootScope.showLoading = false;

							$scope.user.name = user.full_name;
							$scope.user.first_name = user.first_name;
							$scope.user.last_name = user.last_name;
							//$scope.user.cover= user.cover;
							//$scope.user.email = user.email;
							$scope.user.provider = 'soundcloud';
							$scope.user.provider_user_id = user.id;

							$scope.$apply();
						} else {
							if(r.data.user_type=="artist"){
								if(r.data.last_login_at ) {
									$window.location.href = $state.href('app.page.dashboard');
								} else {
									$window.location.href = $state.href('app.artist.profile');
								}
							} else if(r.data.user_type=="stm_user"){

								if(r.data.last_login_at) {
									$window.location.href = $state.href('app.page.dashboard');
								} else {
									$window.location.href = $state.href('app.user.profile');
								}
							}
						}
					});
				} else {
					$rootScope.showLoading = false;
				}
			}
		};

		var win = window.open ($rootScope.baseUrl+'/sc/login',"Soundcloud","menubar=1,resizable=1,width="+w+",height="+h+",top="+top+',left='+left);
		setWindowTitle(win, 'Soundcloud');
	}

	$scope.soundcloudSignin = function() {
		$rootScope.showLoading = true;
		$window.soundcloudApp.connectCallback = function(status, token, user){
			if(status == 'success'){
				if(user) {
					AuthService.checkSCUser(null, {'id':user.id}, function(r) {
						if (r.data == 'Notexist') {
							$rootScope.showLoading = false;
							toastr.error("Please signup with Soundcloud");
						} else {
							if(r.data.user_type=="artist"){
								if(r.data.last_login_at ) {
									$window.location.href = $state.href('app.page.dashboard');
								} else {
									$window.location.href = $state.href('app.artist.profile');
								}
							} else if(r.data.user_type=="stm_user"){

								if(r.data.last_login_at) {
									$window.location.href = $state.href('app.page.dashboard');
								} else {
									$window.location.href = $state.href('app.user.profile');
								}
							}
						}
					});
				}
			} else {
				$rootScope.showLoading = false;
			}
		};

		var win = window.open ($rootScope.baseUrl+'/sc/login',"Soundcloud","menubar=1,resizable=1,width="+w+",height="+h+",top="+top+',left='+left);
		setWindowTitle(win, 'Soundcloud');
	}
});