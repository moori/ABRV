/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular.module("app").service('AuthenticationService', ["$http", "$state", "$sessionStorage", "$rootScope", function($http, $state, $sessionStorage, $rootScope){
	var self = this;


	self.isLogged = function(){
		$token = $sessionStorage.token;

		if(typeof $token === 'undefined' || $token === null || $token === ""){
			return false;
		}else{
			return true;
		}
	};
    
	self.checkToken = function(){
		$token = $sessionStorage.token;

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
	$token = $sessionStorage.token;
	delete $sessionStorage.token;
	// self.checkToken();
    var data = {action: "LogoutUser", token: $token};
    var promise = $http.post("php/authenticationAPI.php", data);
		return promise;

	};

	self.getUserData = function () {
		var data = {action: "GetUserData", token: $sessionStorage.token };
		var promise = $http.post("php/authenticationAPI.php", data);
		return promise;
	};

	self.signUpUser = function (signUpData) {
		var crmvCompleto = signUpData.crmv.concat("-", signUpData.crmvEstado.valor);
		var especialidadeString = ""	;
		var coirmaString = ""	;
		for (i = 0; i < signUpData.especialidade.length; i++) {
			especialidadeString = especialidadeString.concat(signUpData.especialidade[i].valor, ",");
		}
		for (i = 0; i < signUpData.coirma.length; i++) {
			coirmaString = coirmaString.concat(signUpData.coirma[i], ",");
		}

		var data = {action: "SignUpUser",
			nome: signUpData.nome,
            email: signUpData.email,
            senha: signUpData.senha,
            senhaConf: signUpData.senhaConf,
            endereco: signUpData.endereco,
            end_numero: signUpData.end_numero,
            end_complemento: signUpData.end_complemento,
            bairro: signUpData.bairro,
            cidade: signUpData.cidade,
            estado: signUpData.estado.valor,
            cep: signUpData.cep,
            tel_ddd: signUpData.tel_ddd,
            telefone: signUpData.telefone,
            cel_ddd: signUpData.cel_ddd,
            celular: signUpData.celular,
            crmv: crmvCompleto,
            especialidade: especialidadeString,
            coirma: coirmaString,
            faculdade: signUpData.faculdade,
            cpf: signUpData.cpf,
            tipo: signUpData.tipo,
            grad: signUpData.grad
        };
        
		var promise = $http.post("php/authenticationAPI.php", data);
		return promise;
		
	};

	self.updateUserData = function (updateData) {
		var crmvCompleto = updateData.crmv.concat("-", updateData.crmvEstado);
		var especialidadeString = ""	;
		var coirmaString = ""	;
		for (i = 0; i < updateData.especialidades.length; i++) {
			especialidadeString = especialidadeString.concat(updateData.especialidades[i], ",");
		}
		for (i = 0; i < updateData.coirmas.length; i++) {
			coirmaString = coirmaString.concat(updateData.coirmas[i], ",");
		}
		var data = {action: "UpdateUserData",
			nome: updateData.nome,
            email: updateData.email,
            endereco: updateData.endereco,
            end_numero: updateData.end_numero,
            end_complemento: updateData.end_complemento,
            bairro: updateData.bairro,
            cidade: updateData.cidade,
            estado: updateData.estado,
            cep: updateData.cep,
            tel_ddd: updateData.tel_ddd,
            telefone: updateData.telefone,
            cel_ddd: updateData.cel_ddd,
            celular: updateData.celular,
            crmv: crmvCompleto,
            especialidade: especialidadeString,
            coirma: coirmaString,
            faculdade: updateData.faculdade,
            grad: updateData.grad,
            cpf: updateData.cpf,
            token: $sessionStorage.token
        };
        console.log(data);
		var promise = $http.post("php/authenticationAPI.php", data);
		return promise;
		
	};



	self.LoginFromAuthentication = function (userData){
    var data = {action: "LoginFromAuthentication", email: userData.email, hash: userData.hash };
    var promise = $http.post("php/authenticationAPI.php", data);
		return promise;
	};

	self.changePass = function (passData){
		var data = {action: "ChangePassword", senha: passData.senha, novaSenha: passData.novaSenha, token: $sessionStorage.token };
		var promise = $http.post("php/authenticationAPI.php", data);
		console.log(promise.data);
		return promise;
	};

	self.resetPassword = function (userData){
		var data = {action: "ResetPassword", email: userData.email };
		var promise = $http.post("php/authenticationAPI.php", data);
			return promise;
	};
    
   

}]);