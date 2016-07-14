/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .controller('loginCtrl', ['$scope', '$http', '$state', '$stateParams', 'AuthenticationService', '$rootScope','$sessionStorage', function($scope, $http, $state, $stateParams, AuthenticationService, $rootScope, $sessionStorage){
        
        $scope.email = "";
        $scope.pass = "";
        $scope.error=false;
        $scope.help = "initial";


        AuthenticationService.getUserData().then( function (response){
                $scope.user = response.data[0];
            });

        // $scope.logged = AuthenticationService.isLogged();
        $rootScope.logged = AuthenticationService.isLogged();

        $scope.loginUser = function (redirectState) {
            var userData = {
                email: $scope.email,
                senha: $scope.pass
            };

            var promise = AuthenticationService.loginUser(userData).then( function(response){
                if(response.data === "ERROR"){
                    $scope.error=true;
                    $scope.errorMessage = "Senha ou Email incorretos";
                }else{
                    $sessionStorage.token = response.data;
                    $rootScope.logged = true;
                    AuthenticationService.getUserData().then( function (userResponse){
                        $scope.user = userResponse.data[0];
                    });
                    if(redirectState === null || redirectState ==="" || redirectState  === undefined){
                        $state.go('dashboard');
                    }else{
                        $state.go($rootScope.redirectState.state, {id: $rootScope.redirectState.id, slug: $rootScope.redirectState.slug});
                    }
                    location.reload();
                }
            });
        };
        
        $scope.logoutUser = function(){
            AuthenticationService.logoutUser();
            $rootScope.logged = false;
            $state.go('home');
            location.reload();
        };

        // AuthenticationService.getUserData().then( function (response){ //TODO: Encapsular
        //     // console.log(response.data[0]);
        //     $scope.user = response.data[0];
        // });
        
        // $scope.$on('reloadLoginData', function(event, args) {
        //     AuthenticationService.getUserData().then( function (response){
        //         $scope.user = response.data[0];
        //     });
        // });
    
}]);