/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .controller('dashboardCtrl', ['$scope', '$location', '$http', 'AuthenticationService', '$rootScope', 'pagSeguroService', function($scope, $location, $http, AuthenticationService, $rootScope, pagSeguroService){

        AuthenticationService.checkToken();
        $scope.logged = $rootScope.logged;

        $scope.loading = false;

        AuthenticationService.getUserData().then( function (response){ //TODO: Encapsular
            // console.log(response.data);
            $scope.user = response.data[0];
            $scope.user.especialidades = $scope.user.Especialidade.split(",");

            for(var i = 0; i < $scope.user.especialidades.length ; i++){
                if($scope.user.especialidades[i] === "" || $scope.user.especialidades[i] === " "){
                    $scope.user.especialidades.splice(i,1);
                }
            }

           

        });
        

        $scope.loadEventos = function(){
            var data = {action: "LoadEventsList"};
            $http.post("php/eventos.php", data).success(function(response){
                console.log(response);
                $scope.eventos = response;
            });
        };
        $scope.loadEventos();
        
    }]);
    