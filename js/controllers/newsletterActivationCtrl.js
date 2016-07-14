/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .controller('newsletterActivationCtrl', ['$scope', '$http', '$stateParams','AuthenticationService', '$state', '$rootScope', '$sessionStorage', function($scope, $http, $stateParams, AuthenticationService, $state, $rootScope, $sessionStorage){
        
        $scope.loading = true;
        $scope.errorMSG = false;
        $scope.sucesso = false;

        var data = {action: "AuthenticateNewsletter", code: $stateParams.authCode};

        $scope.activateNewsletter = function(){
            $http.post("php/newsletter.php", data).success(function (response) {
                if(response == "ERROR"){
                    $scope.loading = false;
                    $scope.errorMSG = true;
                    //Notificar admins
                }else{
                    $scope.loading = false;
                    $scope.sucesso = true;
                }
                
            });
        };
        $scope.activateNewsletter();
        
        
        
    }]);