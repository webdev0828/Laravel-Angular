angular.module('artistApp', ['ngMessages','ui.bootstrap','userValidation','checklist-model','appService', 'mainCtrl'], function($interpolateProvider) {
	$interpolateProvider.startSymbol('<%');
	$interpolateProvider.endSymbol('%>');
});