/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .controller('associadosCtrl', ['$scope', '$http', '$state', 'AuthenticationService', '$rootScope', function($scope, $http, $state, AuthenticationService, $rootScope){

        AuthenticationService.checkToken();
        $scope.logged = $rootScope.logged;
        $scope.loading = false;



       $scope.sortField = 'ID';
       $scope.reverse = true;

        $scope.loadList = function(){
            $scope.loading = true;
            var  data = {action: "LoadList"};
            // data.action = "LoadList";
            $http.post("php/associados.php", data).then(function (response) {
                $scope.usuarios = response.data;
                $scope.loading = false;
                angular.forEach($scope.usuarios, function (usuario) {
                    usuario.ID = parseFloat(usuario.ID);
                });
                

            });
        };
        $scope.loadList();

        $scope.exportCSV = function(){
            var data = {action: "ExportUsers"};

            window.open("php/csvDownload.php?action=" + data.action);
        };
    
}]);