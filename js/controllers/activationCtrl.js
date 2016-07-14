/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .controller('activationCtrl', ['$scope', '$http', '$stateParams','AuthenticationService', '$state', '$rootScope', '$sessionStorage', function($scope, $http, $stateParams, AuthenticationService, $state, $rootScope, $sessionStorage){
        
        $scope.loading = true;
        $scope.errorMSG = false;
        $scope.sucesso = false;

        var data = {code: $stateParams.authCode};

        $scope.activateAcount = function(){
            $http.post("php/activate.php", data).success(function (response) {
                if(response == "ERROR"){
                    $scope.loading = false;
                    $scope.errorMSG = true;
                    //Notificar admins
                }else{
                    $scope.loading = false;
                    $scope.sucesso = true;
                    $explode = response.split("&");
                    $email = $explode[0];
                    $hash = $explode[1];

                    
                    var data = {action: "LoginFromAuthentication", email: $email, hash: $hash};
                    // console.log(data);
                    AuthenticationService.LoginFromAuthentication(data).then(function (response) {
                        $scope.token = response.data;
                        if($scope.token == "ERROR"){
                            $scope.error=true;
                            $scope.errorMessage.loginIncorreto = "Erro";
                        }else{
                            $sessionStorage.token = $scope.token;
                            $rootScope.logged = true;
                            // var args = {};
                            // $scope.$emit('reloadLoginData', args);
                            $state.go("dashboard");
                            location.reload();
                        }
                    });
                }
                
            });
        };
        $scope.activateAcount();
        
        
        
    }]);