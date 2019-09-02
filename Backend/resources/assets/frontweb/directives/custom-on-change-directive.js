angular.module('app.directives')
.directive('customOnChange', function($rootScope) {
	return {
	    restrict: 'A',
	    scope : {
	    	ngModel : '=',
	    },
	    link: function (scope, element, attrs) {

			scope.$watch('ngModel',function(newVal, oldVal){
	    		if(newVal){
					if(attrs.customOnChange == 'cropImageModal'){
						element.val(null);
						$rootScope.cropImageModal(newVal, attrs.ngModel);
					}
					if(attrs.customOnChange == 'updateProfileImage'){
						$rootScope.updateProfileImage(newVal);
					}
					else if(attrs.customOnChange == 'updateUserProfileImage'){
						$rootScope.updateUserProfileImage(newVal);
					}
					else if(attrs.customOnChange == 'updateBannerImage'){
						$rootScope.updateBannerImage(newVal);
					}
			    }
	    	});
	    }
	};
});