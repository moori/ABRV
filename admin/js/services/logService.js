/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .service('logService', ['$http', '$state','AuthenticationService', '$sessionStorage', function($http, $state, AuthenticationService, $sessionStorage){
        var self = this;
        
        self.getUserInfo = function(data){
            var promise = $http.post("php/logAPI.php", data);
//                console.log(response);
                return promise;
            
        };

    
        self.loginUser = function (data) {

            $http.post("php/login.php", data).success(function(response){
                console.log(response);
                $sessionStorage.adminToken = response;
            }).error(function(error){
                console.log(error);
            });

        };
        
          self.signUserUp = function (data){

            $http.post("php/signup.php", data).success(function(response){
                console.log(response);
                $sessionStorage.adminToken = response;
                self.loginUser(data);
                $state.go("home");
            }).error(function(errorMSG){
                console.log(errorMSG);
            });
        };
        
        self.logout = function(){
		var data = {
			token: $sessionStorage.token
		};
            console.log(data);
		
		$http.post('php/logout.php', data).success(function(response){
			console.log(response);
            delete $sessionStorage.adminToken;
			$state.go("login");
		}).error(function(error){
			console.error(error);
		});
	};
        
    
}]);