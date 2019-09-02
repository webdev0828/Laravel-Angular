angular.module('appService', [])
	.factory('Artist', function ($http) {	
		return {
			login: function(userdata){
		        return $http({
		            method: 'POST',
		            url: 'api/user/login',
		            data: userdata
		        });
			},
			save: function(profileData){
				return $http({
		            method : 'POST',
		            url : 'api/user/register',
		            data : profileData,
		        });
			},
			forgotPass: function(email){
				return $http({
		            method: 'POST',
		            url: 'password/email',
		            data: {email: email}
		        });
			},
			getGenre: function(){
				return $http({
			        method: 'GET',
			        url: 'genre',
			    });
			},
			getUser: function(){
				return $http({
		            method: 'GET',
		            url: 'edit/user',
		        });
			},
			update: function(fd){
				return $http({
		          	method: 'POST',
		          	url: 'api/user/profile',
		          	data: fd,
	          		transformRequest : angular.identity,
		          	headers : {'Content-Type': undefined} 
		      	});
			},
			getAllArtists: function(){
				return $http({
			        method: 'GET',
			        url: 'allartists',
			    });
			},
		};
	});