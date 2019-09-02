angular.module('app.filters')
.filter('dateFormat', function($filter)
{
 return function(input)
 {
  if(input == null){ return ""; } 
 
  var _date = $filter('date')(new Date(input), 'dd MMM  yyyy');
 
  return _date.toUpperCase();

 };
});