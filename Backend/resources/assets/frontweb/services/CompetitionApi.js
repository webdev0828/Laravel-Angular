angular.module('app.services')

.factory('CompetitionApi', function($resource, $http) {
	return $resource('frontapi/', null, {
	  'getCompetitionPageData'	:    {method:'GET', url:'frontapi/get-compititions-page-data'},
	  'submitRemix'				:    {method:'POST', url:'frontapi/add-compitition-remix-track'},
	  'getPastCompetitionData'	: 	 {method:'POST', url:'frontapi/get-past-compitition'},
	  'getMoreRunnerUp'	: 	 {method:'POST', url:'frontapi/get-more-runnerUp'}  
	}); 
});
