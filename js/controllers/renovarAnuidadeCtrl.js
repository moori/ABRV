/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .controller('renovarAnuidadeCtrl', ['$scope', '$http','AuthenticationService', '$rootScope', 'pagSeguroService', function($scope, $http, AuthenticationService, $rootScope, pagSeguroService){


        AuthenticationService.checkToken();
        $scope.logged = $rootScope.logged;

        $scope.loading = false;
        $scope.loadingSales = false;
        $scope.showWait = false;

        AuthenticationService.getUserData().then( function (response){ //TODO: Encapsular
            // console.log(response.data[0]);
            $scope.user = response.data[0];
        });

        $http.get('admin/data/config.json').success(function(cfgData) {
            $scope.configData = cfgData;
        });
        
        
        $scope.request = function (){
            var data = $scope.user;
            
            data.eventID = $scope.configData.anoVigenteID;
            
            data.action = "RenovarAnuidade";
            // console.log(data);
            $scope.loading =true;
            pagSeguroService.Request(data).then(function (response) {
                // console.log(response.data);
                $scope.PagSeguroCode = response.data;
                console.log( $scope.PagSeguroCode);
                // localStorage.setItem("PagSeguroCode", $scope.PagSeguroCode); //Talvez não use o código.
                // console.log(localStorage);
//                $window.location.href = 'https://sandbox.pagseguro.uol.com.br/v2/checkout/payment.html?code=' + $scope.PagSeguroCode; //Para abrir na mesma página
                // $window.open('https://sandbox.pagseguro.uol.com.br/v2/checkout/payment.html?code=' + $scope.PagSeguroCode); //Pop-up


                // PagSeguroLightbox("https://sandbox.pagseguro.uol.com.br/v2/checkout/payment.html?code=" + $scope.PagSeguroCode);
                PagSeguroLightbox($scope.PagSeguroCode);
                $scope.loading = false;
                $scope.showWait = true;
            });
            
        };

        // $scope.loadSales = function(){
        //     var data = $scope.user;
        //     data.action = "LoadSales";
        //     $http.get('panel/admin/data/config.json').success(function(data) {
        //       $scope.configData = data;
        //     });
        //     data.eventID = $scope.configData.anoVigenteID;
        //     console.log(data);
        //     $scope.loadingSales = true;
        //     pagSeguroService.Request(data).then(function (response) {
        //         console.log(response.data);
        //         $scope.loadingSales = false;
        //     });
        // };

        
    }]);
    