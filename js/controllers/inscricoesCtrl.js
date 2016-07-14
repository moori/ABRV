/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .controller('inscricoesCtrl', ['$scope', '$http','AuthenticationService', '$rootScope', function($scope, $http, AuthenticationService, $rootScope){

    AuthenticationService.checkToken();
    $scope.logged = $rootScope.logged;
    
    AuthenticationService.getUserData().then( function (response){
		$scope.user = response.data[0];

		$scope.loadSubscriptions();
	});

    $scope.loadSubscriptions = function(){
		var data = {action: "LoadUserSubscriptions"};
		data.userID = $scope.user.ID;
		$http.post("php/inscricoes.php", data).success(function(response){
			console.log(response);
			$scope.subscriptions = response;
		});
	};

}]);