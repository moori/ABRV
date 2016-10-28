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
          var data = {action: "LoadTransactions"};
          $scope.loading = true;
          $http.post("php/transacoes.php", data).success(function (response) {
              $scope.transacoes = response;
              $scope.loading = false;
              console.log(response);
              angular.forEach($scope.transacoes, function (transacao) {
                  transacao.ID = parseFloat(transacao.ID);
              });
          });
        };
        $scope.loadList();

        $scope.openTransactionPage = function(transCode){
          console.log("Get URL");
          console.log(transCode);
            var data = {action: "GetTransactionURL", transCode: transCode};
            $scope.loading = true;
            $http.post("php/transacoes.php", data).success(function (resp) {
                $scope.loading = false;
                console.log(resp);
                window.open(resp);
            });
        };

}]);
