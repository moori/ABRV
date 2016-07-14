/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .controller('eventosCtrl', ['$scope', '$http', '$state','AuthenticationService', 'pagSeguroService' , '$window', '$rootScope', function($scope, $http, $state, AuthenticationService, pagSeguroService, $window, $rootScope){
        
        // AuthenticationService.checkToken();
        $scope.logged = $rootScope.logged;

//         AuthenticationService.getUserData().then( function (response){ //TODO: Encapsular
//             // console.log(response.data[0]);
//             $scope.user = response.data[0];
//         });
        
//         $scope.request = function (){
//             var data = $scope.user;
//             data.action = "Request";
//             console.log(data);
//             pagSeguroService.Request(data).then(function (response) {
//                 console.log(response.data);
//                 $scope.PagSeguroCode = response.data;
// //                $window.location.href = 'https://sandbox.pagseguro.uol.com.br/v2/checkout/payment.html?code=' + $scope.PagSeguroCode; //Para abrir na mesma página
//                 // $window.open('https://sandbox.pagseguro.uol.com.br/v2/checkout/payment.html?code=' + $scope.PagSeguroCode); //Pop-up


//                 // PagSeguroLightbox("https://sandbox.pagseguro.uol.com.br/v2/checkout/payment.html?code=" + $scope.PagSeguroCode);
//                 PagSeguroLightbox($scope.PagSeguroCode);
//             });
            
//         };

        // TODO Banco eventos.php
		$scope.loadEventos = function(){
			var data = {action: "LoadEventsList"};
			$http.post("php/eventos.php", data).success(function(response){
				console.log(response);
				$scope.eventos = response;
			});
		};
		$scope.loadEventos();

		$scope.subscribeNow = function(eventData){
			if ($scope.logged) {
				console.log("logado");
			}else{
				console.log(eventData);
			}

			$state.go("evento", {id: eventData.ID, slug: eventData.Description});
		};
        
}]);