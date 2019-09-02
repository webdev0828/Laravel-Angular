var app = angular.module('artistApp', ['ngMessages','ui.bootstrap','userValidation','checklist-model','appService', 'mainCtrl', 'AuthController'], function($interpolateProvider) {
	$interpolateProvider.startSymbol('<%');
	$interpolateProvider.endSymbol('%>');
});
