/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .controller('cancelarCtrl', ['$scope', '$http', '$state','AuthenticationService', '$rootScope', function($scope, $location, $http, AuthenticationService, $rootScope){
		AuthenticationService.checkToken();
		$scope.logged = $rootScope.logged;
    
    
}]);