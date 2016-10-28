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
                console.log(response);
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

        $scope.generateCerts = function(){

            $scope.loading = true;

            var data = {
                action: "GenerateCertificate",
                subs: $scope.subs,
                event_ID: $scope.eventID,
                template: "cert_tpl_01.php",
                nome: "nome",
                curso: "curso"
            };
            console.log(data);
            $http.post("php/certificados.php", data).success(function (response) {
                $scope.loading = false;
                location.reload();
            });
        };

        $scope.publishAll = function(){
            var data = {action: "PublishAll", eventID: $scope.eventID};
            console.log(data);
            $scope.loading = true;
            $http.post("php/certificados.php", data).success(function (response) {
                $scope.loading = false;
                console.log(response);
                if(response == "SUCCESS"){
                    location.reload();
                }
            });
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
                // location.reload();
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

        $scope.showCodeXML = function(ps_code){
            var data = {action: "ShowCodeXML", code: ps_code};
            $http.post("php/showCodeXML.php", data);
        };

        $scope.previewCert = function(certPath){
            window.open("../" + certPath);
        };

        $scope.toogleAvailability = function(userID, valor){
            valor = valor ? 1 : 0;
            var data = {action: "ToogleAvailability", userID: userID, eventID: $scope.eventID, valor: valor};
            console.log(data);
            $scope.loading = true;
            $http.post("php/certificados.php", data).success(function (response) {
                $scope.loading = false;
                console.log(response);
            });
        };

        $scope.checkTransaction = function(subCode){
            console.log("Check");
            var data = {action: "CheckSubscription", subCode: subCode};
            $scope.loading = true;
            $http.post("php/evento.php", data).success(function (response) {
                $scope.loading = false;
                console.log(response);
            });
        };

        $scope.openTransactionPage = function(subCode){
          console.log("Get URL");
            var data = {action: "GetTransactionURL", subCode: subCode};
            $scope.loading = true;
            $http.post("php/evento.php", data).success(function (response) {
                $scope.loading = false;
                console.log(response);
                window.open(response);
            });
        };

        $scope.updateTransactionByCode = function(subCode){
          console.log("updateTransactionByCode");
            var data = {action: "UpdateTransactionByCode", subCode: subCode};
            $scope.loading = true;
            $http.post("php/evento.php", data).success(function (response) {
                $scope.loading = false;
                console.log("OK");
                location.reload();
            });
        };

        $scope.refreshTransactions = function(){
            console.log("Refresh");
            // var data = {action: "RefreshTransactions", subs: $scope.subs};
            // $scope.loading = true;
            // $http.post("php/evento.php", data).success(function (response) {
            //     $scope.loading = false;
            //     console.log(response);
            // });
            $scope.loading = true;
            $http.post("php/cron_UpdateTransactions.php", data).success(function (response) {
                $scope.loading = false;
                console.log(response);
            });
        };

}]);
