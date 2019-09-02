angular.module('app.services')

.factory('SubscriptionApi', function($resource) { 
	return $resource('frontapi/subscription', {}, {
	  	'subscribeCustomer' : { method : 'POST',url:'frontapi/subscribe-customer'},
	  	'updatePaymentDetails': { method : 'POST',url:'frontapi/update-payment-details'},
	  	'cancelSubscription': { method : 'POST',url:'frontapi/cancel-subscription'},
	  	'resumeSubscription': { method : 'POST',url:'frontapi/resume-subscription'},
	  	'undoSubscription': { method : 'POST',url:'frontapi/undo-subscription'},
	});
});
