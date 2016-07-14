/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .controller('faleConoscoCtrl', ['$scope', '$http','AuthenticationService', '$rootScope', function($scope, $http, AuthenticationService, $rootScope){

	$scope.comment = {
		nome: "",
		email: "",
		telefone: "",
		assunto: "",
		mensagem: "",
    };

    $scope.loading=false;
    $scope.sent = false;

	// console.log("Logged: " + AuthenticationService.isLogged() );
    if(AuthenticationService.isLogged()){
		AuthenticationService.getUserData().then( function (response){
			$scope.user = response.data[0];
			$scope.comment.nome = $scope.user.Nome;
			$scope.comment.email = $scope.user.Email;
			$scope.comment.telefone = "(" + $scope.user.Tel_DDD+ ") " + $scope.user.Telefone;
		});
    }

    $scope.sendComment = function(){
		$scope.loading = true;
		var data = $scope.comment;
		$http.post("php/faleConosco.php", data).then(function(response){
			$scope.sent = true;
			$scope.sentTime = response.data;
			$scope.loading=false;
		});
			
    };
    
}]);