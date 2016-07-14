/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .controller('anuidadesCtrl', ['$scope', '$http', '$state', 'AuthenticationService', '$rootScope', function($scope, $http, $state, AuthenticationService, $rootScope){

        AuthenticationService.checkToken();
        $scope.logged = $rootScope.logged;
        $scope.loading = false;
        $scope.refreshing = false;

        $scope.sortField = '-ID';
        $scope.reverse = false;

        $http.get('data/config.json').success(function(data) {
            $scope.anoVigenteID = data.anoVigenteID;
        });

        $scope.loadList = function(){
            $scope.refreshing = false;
            $scope.loading = true;
            var data = {
                anoVigenteID: $scope.anoVigenteID
            };
            $http.get("php/anuidades.php", data).then(function (response) {
                console.log(response.data);
                $scope.anuidades = response.data;
                $scope.loading = false;
            });
        };

        $scope.refresh = function(){
            $scope.refreshing = true;
            $http.get("php/updateNotification.php").then(function (response) {
                $scope.refreshing = false;
                $scope.loadList();
                // $scope.$apply();
            });
        };

        $scope.loadList();
    
}]);