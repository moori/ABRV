/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .controller('eventoCtrl', ['$scope', '$http', '$state', '$stateParams', 'AuthenticationService', '$rootScope', function($scope, $http, $state, $stateParams, AuthenticationService, $rootScope){

        AuthenticationService.checkToken();
        $scope.logged = $rootScope.logged;
        $scope.loading = false;
        $scope.downloading = false;
        $scope.eventID = $stateParams.id;
        $scope.forcePagamento = 1;

        // $scope.sortField = '-ID';
        $scope.sortField = '-id';
        $scope.reverse = false;


        $scope.loadSubscriptions = function(){
            $scope.loading = true;

            var data = {action: "LoadUserSubscriptions", eventID: $scope.eventID};

            $http.post("php/evento.php", data).success(function (response) {
                // console.log(response);
                $scope.subs = response;
                angular.forEach($scope.subs, function (sub) {
                    sub.id = parseFloat(sub.id);
                });
                $scope.loading = false;
            });
        };
        $scope.loadSubscriptions();


        $scope.loadEvento = function(){
            var data = {action: "LoadEvento", id: $scope.eventID};
            $http.post("php/evento.php", data).success(function (response){
                console.log(response);
                $scope.evento = response;
            });
        };
        $scope.loadEvento();

        $scope.loadUserList = function(){
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
        $scope.loadUserList();

        $scope.exportCSV = function(){
            var data = {action: "ExportSubscriptions", eventID: $scope.eventID};

            window.open("php/csvDownload.php?action=" + data.action + "&eventID=" + data.eventID);
        };

        $scope.confirmSubs = function(userID){
            var r = confirm("Tem certeza que deseja inscrever este usuário?");
            if (r === true) {
                console.log("Force Sub " + userID);

                $scope.loading = true;
                var data = {action: "ForceApproval", userID: userID, eventID: $scope.eventID };
                $http.post("php/evento.php", data).success(function (response) {
                    $scope.loading = false;
                });
                $scope.loadSubscriptions();
                location.reload();
            }
        };

        $scope.confirmCancel = function(subID){
            var r = confirm("Tem certeza que deseja cancelar a inscrição deste usuário?");
            if (r === true) {
                $scope.loading = true;
                // var data = {action: "ForceCancel", userID: userID, eventID: $scope.eventID };
                var data = {action: "ForceCancel", subID: subID };
                $http.post("php/evento.php", data).success(function (response) {
                    $scope.loading = false;
                });
                $scope.loadSubscriptions();
                location.reload();
            }
        };

        $scope.forceSubInclude = function(userID, valor){
            $scope.loading = true;
            console.log(userID + " pagando " + valor);
            var data = {action: "ForceSubscription", userID: userID, eventID: $scope.eventID, valor: valor};
            $http.post("php/evento.php", data).success(function (response) {
                $scope.loading = false;
            });
            $scope.loadSubscriptions();
            location.reload();
        };
    
}]);