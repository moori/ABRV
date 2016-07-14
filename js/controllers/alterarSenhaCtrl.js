/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .controller('alterarSenhaCtrl', ['$scope', '$http', '$state','AuthenticationService', '$rootScope', function($scope, $location, $http, AuthenticationService, $rootScope){

    AuthenticationService.checkToken();
	$scope.logged = $rootScope.logged;
	$scope.updated = false;
	$scope.loading = false;
	$scope.senhaInfo = {
		senha: "",
		novaSenhaConf: "",
		novaSenha: ""
	};

	$scope.errorMessage ={
            senhaIncorreta: "",
            senhaDiferente: ""
        };


	$scope.validatePass = function () {
        if($scope.senhaInfo.novaSenha != $scope.senhaInfo.novaSenhaConf){
            $scope.error=true;
            $scope.errorMessage.senhaDiferente = "As senhas não conferem";
        }else{
            $scope.errorMessage.senhaDiferente ="";
        }
    };

    $scope.changePass = function(){
		$scope.loading = true;
		AuthenticationService.changePass($scope.senhaInfo).then(function(response){
			if(response.data == "ERROR"){
				$scope.errorMessage.senhaIncorreta = "Senha Incorreta";
			}else{
				$scope.updated = true;
			}
			$scope.loading = false;
		});
    };


    
}]);