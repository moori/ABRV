/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular.module("app").service('AuthenticationService', ["$http", "$state", "$sessionStorage", function($http, $state, $sessionStorage){
	var self = this;

	self.isLogged = function(){
		$token = $sessionStorage.adminToken;

		if(typeof $token === 'undefined'){
			return false;
		}else{
			return true;
		}
	};
    
	self.checkToken = function(){
		$token = $sessionStorage.adminToken;

		if(typeof $token === 'undefined'){
			$state.go("login");
		}

		var data = {action: "CheckToken", token: $token};

		$http.post("php/authenticationAPI.php", data).success(function(response){
			if (response == "unauthorized"){
					// console.log("Logged out");
					$state.go("login");
				} else {
					// console.log("Logged In");
					return response;
				}
			});
	};

	self.loginUser = function (userData){
    var data = {action: "LoginUser", email: userData.email, senha: userData.senha };
    var promise = $http.post("php/authenticationAPI.php", data);
		return promise;
	};

	self.logoutUser = function (){
	$token = $sessionStorage.adminToken;
    var data = {action: "LogoutUser", token: $token};
    var promise = $http.post("php/authenticationAPI.php", data);
    delete $sessionStorage.adminToken;
		return promise;

	};

	self.getUserDataByID = function (uid) {
		var data = {action: "GetUserDataByID", id:uid};
		var promise = $http.post("php/authenticationAPI.php", data);
		return promise;
	};
    
   

}]);