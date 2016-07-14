/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .controller('eventosCtrl', ['$scope', '$http', '$state','AuthenticationService', 'pagSeguroService' , '$window', '$rootScope', function($scope, $http, $state, AuthenticationService, pagSeguroService, $window, $rootScope){
        
        AuthenticationService.checkToken();
        $scope.logged = $rootScope.logged;

        $scope.loadEventos = function(){
            var data = {action: "LoadEventsList"};
            $http.post("php/evento.php", data).success(function(response){
                // console.log(response);
                $scope.eventos = response;
            });
        };
        $scope.loadEventos();

        $scope.subscribeNow = function(eventData){
            if ($scope.logged) {
                // console.log("logado");
            }else{
                // console.log(eventData);
            }

            $state.go("evento", {id: eventData.ID, slug: eventData.Description});
        };
        
}]);