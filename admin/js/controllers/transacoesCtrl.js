/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .controller('transacoesCtrl', ['$scope', '$http', '$state', 'AuthenticationService', 'pagSeguroService', '$rootScope', function($scope, $http, $state, AuthenticationService, pagSeguroService, $rootScope){

        AuthenticationService.checkToken();
        $scope.logged = $rootScope.logged;
        // pagSeguroService.updateNotifications();
        $scope.loading = false;


        $scope.sortField = 'ID';
        $scope.reverse = true;

        $scope.loadList = function(){
            $scope.loading = true;
            $http.get("php/transacoes.php").then(function (response) {
                $scope.transacoes = response.data;
                $scope.loading = false;
                angular.forEach($scope.transacoes, function (transacao) {
                    transacao.ID = parseFloat(transacao.ID);
                });
            });
        };
        $scope.loadList();
    
}]);