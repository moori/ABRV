/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .controller('resetPasswordCtrl', ['$scope', '$http', '$state','AuthenticationService', '$rootScope','$sessionStorage', function($scope, $http, $state, AuthenticationService, $rootScope, $sessionStorage){
        
        $scope.email = "";
        $scope.error=false;
        $scope.passReseted = false;
        $scope.loading = false;

        $scope.resetPassword = function () {
            $scope.loading = true;
            var userData = {
                email: $scope.email
            };

            var promise = AuthenticationService.resetPassword(userData).then( function(response){
                $scope.loading = false;
                if(response.data === "ERROR"){
                    $scope.error=true;
                    $scope.errorMessage = "Email não cadastrado.";
                }else{
                    $scope.passReseted = true;
                }
            });
        };
    
}]);