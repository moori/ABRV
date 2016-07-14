/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .controller('eventoCtrl', ['$scope', '$http', '$state', '$stateParams','AuthenticationService', 'pagSeguroService' , '$window', '$rootScope', function($scope, $http, $state, $stateParams, AuthenticationService, pagSeguroService, $window, $rootScope){
        
        //AuthenticationService.checkToken();
        $rootScope.logged = AuthenticationService.isLogged();
        $scope.logged = $rootScope.logged;
        $scope.eventID = $stateParams.id;
        $scope.slug = $stateParams.slug;
        $rootScope.redirectState = {state: "evento", id: $scope.eventID, slug: $scope.slug};
        // $scope.grad = "";


		AuthenticationService.getUserData().then( function (response){
			$scope.user = response.data[0];
			$scope.user.coirma = response.data[0].Coirma;
			$scope.user.TipoFavorecido = $scope.user.Tipo;
			if($scope.user.TipoFavorecido == 1 ||
				$scope.user.TipoFavorecido == 2 ||
				$scope.user.TipoFavorecido == 3 ||
				$scope.user.TipoFavorecido == 4 ||
				$scope.user.TipoFavorecido == 5 ||
				$scope.user.TipoFavorecido == 6 ||
				$scope.user.TipoFavorecido == 7){
				$scope.user.TipoFavorecido = 1;
			}

			if($scope.user.Tipo == 8){
                $scope.grad = true;
            
				if($scope.user.Faculdade !== undefined && $scope.user.Faculdade !== null){
					$scope.user.faculdadePieces = $scope.user.Faculdade.split(",");
					$scope.user.faculdadeNome = $scope.user.faculdadePieces[0];
					$scope.user.faculdadeAno = $scope.user.faculdadePieces[1];
				}

            }else{
                $scope.grad = false;
            }

            if($scope.user.coirma !== undefined && $scope.user.coirma !== null){
                $scope.user.coirmas = $scope.user.coirma.split(",");
            
				for(var j = 0; j < $scope.user.coirmas.length ; j++){
					if($scope.user.coirmas[j] === "" || $scope.user.coirmas[j] === " "){
						$scope.user.coirmas.splice(j,1);
					}
				}
			}
		});

		$scope.RefreshSaleDisplay = function(){
			$scope.user.TipoFavorecido = $scope.user.Tipo;
			if($scope.user.TipoFavorecido == 1 ||
				$scope.user.TipoFavorecido == 2 ||
				$scope.user.TipoFavorecido == 3 ||
				$scope.user.TipoFavorecido == 4 ||
				$scope.user.TipoFavorecido == 5 ||
				$scope.user.TipoFavorecido == 6 ||
				$scope.user.TipoFavorecido == 7){
				$scope.user.TipoFavorecido = 1;
			}else
			if($scope.grad === true){
				$scope.user.TipoFavorecido = 8;
			}else
			if($scope.user.coirmas.length !== 0 && $scope.user.TipoFavorecido != 1){
				$scope.user.TipoFavorecido = 9;
			}else{
				$scope.user.TipoFavorecido = 0;
			}
		};

		$scope.loadEvento = function(){
			var data = {action:"LoadEvento", id: $scope.eventID};
			$http.post("php/eventos.php", data).success(function(response){
				$scope.evento = response[0];
			});
		};
		$scope.loadEvento();

		$scope.loadSales= function(){
			var data = {action:"LoadSales", id: $scope.eventID};
			$http.post("php/eventos.php", data).success(function(response){
				console.log(response);
				$scope.sales = response;
				for (var i = 0; i < $scope.sales.length; i++) {
					if($scope.sales[i].Favorecido ==1){
						$scope.saleABRV = $scope.sales[i];
					}
				}
				
			$scope.subsAvailable = $scope.sales > 0? true: false;
				
			});

			data = {action:"LoadAllSales", id: $scope.eventID};
			$http.post("php/eventos.php", data).success(function(response){
				console.log(response);
				$scope.allSales = response;
			});


		};
		$scope.loadSales($scope.eventID);

		$scope.subscribeNow = function(saleID){
            var data = $scope.user;
            
            updateUserType();

            data.eventID = $scope.eventID;
            data.saleID = saleID;
            
            data.action = "InscreverCurso";
            // console.log(data);
            $scope.loading = true;
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

		var updateUserType = function(newType){

            $scope.user.faculdade = $scope.user.faculdadeNome + "," + $scope.user.faculdadeAno;
			var data = $scope.user;
			$http.post("php/UpdateUserDataOnSubscription.php", data).success(function(response){
				console.log(response);
			});
		};
        
}]);