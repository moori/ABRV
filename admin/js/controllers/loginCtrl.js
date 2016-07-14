/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .controller('loginCtrl', ['$scope', '$http', '$state','AuthenticationService', '$rootScope', '$sessionStorage', function($scope, $http, $state, AuthenticationService, $rootScope, $sessionStorage){
        
        $scope.email = "";
        $scope.pass = "";
        $scope.error=false;

        $rootScope.logged = AuthenticationService.isLogged();
    
        $scope.loginUser = function () {
            var userData = {
                email: $scope.email,
                senha: $scope.pass
            };

            AuthenticationService.loginUser(userData).then(function (response) {
                $scope.token = response.data;
                if($scope.token == "ERROR"){
                    $scope.error=true;
                    $scope.errorMessage = "Senha ou Email incorretos";
                    // console.log("Senha ou Email incorretos");
                }else{
                    $sessionStorage.adminToken = $scope.token;
                    $state.go("dashboard");
                }
            });

        };
        
        $scope.logoutUser = function(){
            AuthenticationService.logoutUser();
            $scope.logged = $sessionStorage.adminToken==='undefined' ? false : true;
            $state.go($state.current, {}, {reload: true});
        };


        
    
}]);