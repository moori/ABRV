/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .controller('associadoCtrl', ['$scope', '$http','AuthenticationService', '$rootScope', '$stateParams','$state', function($scope, $http, AuthenticationService, $rootScope, $stateParams, $stete){

        $scope.showErrors=true;
		$scope.error=false;
		$scope.loading = false;
		$scope.statusMessage = "Atualizando";
        $scope.waitingConfirmation = false;

        $scope.userID = $stateParams.id;

        $scope.errorMessage ={
            emailRegistrado: "",
            loginIncorreto: "",
            cpfInvalido: "",
            senhaDiferente: ""
        };

        $scope.estados = [
            {"valor":"ac", "label":"Acre"},
            {"valor":"al", "label":"Alagoas"},
            {"valor":"am", "label":"Amazonas"},
            {"valor":"ap", "label":"Amapá"},
            {"valor":"ba", "label":"Bahia"},
            {"valor":"ce", "label":"Ceará"},
            {"valor":"df", "label":"Distrito Federal"},
            {"valor":"es", "label":"Espírito Santo"},
            {"valor":"go", "label":"Goiás"},
            {"valor":"ma", "label":"Maranhão"},
            {"valor":"mt", "label":"Mato Grosso"},
            {"valor":"ms", "label":"Mato Grosso do Sul"},
            {"valor":"mg", "label":"Minas Gerais"},
            {"valor":"pa", "label":"Pará"},
            {"valor":"pb", "label":"Paraíba"},
            {"valor":"pr", "label":"Paraná"},
            {"valor":"pe", "label":"Pernambuco"},
            {"valor":"pi", "label":"Piauí"},
            {"valor":"rj", "label":"Rio de Janeiro"},
            {"valor":"rn", "label":"Rio Grande do Norte"},
            {"valor":"ro", "label":"Rondônia"},
            {"valor":"rs", "label":"Rio Grande do Sul"},
            {"valor":"rr", "label":"Roraima"},
            {"valor":"sc", "label":"Santa Catarina"},
            {"valor":"se", "label":"Sergipe"},
            {"valor":"sp", "label":"São Paulo"},
            {"valor":"to", "label":"Tocantins"}
        ];



        $scope.especialidades = [
            {"valor":"radiologia", "label":"Radiologia"},
            {"valor":"ultrassom", "label":"Ultrassonografia"},
            {"valor":"tomografia", "label":"Tomografia Computadorizada"},
            {"valor":"ressonancia", "label":"Imagem por Ressonância Magnética"}
        ];

        $scope.statusList = [
            {"valor":"0", "label":"Atrasado"},
            {"valor":"1", "label":"Em dia"}
        ];

        $scope.ativoList = [
            {"valor":"0", "label":"Não Autenticado"},
            {"valor":"1", "label":"Autenticado"}
        ];

        $scope.modalidades = [
            {"valor":"0", "label":"Veterinário"},
            {"valor":"1", "label":"Associado Efetivo"},
            {"valor":"2", "label":"Associado Honorário"},
            {"valor":"3", "label":"Associado Benemérito"},
            {"valor":"4", "label":"Associado Correspondente"},
            {"valor":"5", "label":"Associado Residente"},
            {"valor":"6", "label":"Associado Colegiado"},
            {"valor":"7", "label":"Associado Emérito"},
            {"valor":"8", "label":"Graduando"}
        ];

        AuthenticationService.checkToken();
        $scope.logged = $rootScope.logged;

        $scope.loading = false;

        AuthenticationService.getUserDataByID($scope.userID).then( function (response){ //TODO: Encapsular
            // console.log(response.data[0]);
            $scope.signUpInfo = response.data[0];
            $scope.signUpInfo.ID = response.data[0].ID;
            $scope.signUpInfo.nome = response.data[0].Nome;
            $scope.signUpInfo.endereco = response.data[0].Endereco;
            $scope.signUpInfo.end_numero = response.data[0].Numero;
            $scope.signUpInfo.end_complemento = response.data[0].Complemento;
            $scope.signUpInfo.bairro = response.data[0].Bairro;
            $scope.signUpInfo.cidade = response.data[0].Cidade;
            $scope.signUpInfo.estado = response.data[0].Estado;
            $scope.signUpInfo.cep = response.data[0].CEP;
            $scope.signUpInfo.tel_ddd = response.data[0].Tel_DDD;
            $scope.signUpInfo.telefone = response.data[0].Telefone;
            $scope.signUpInfo.cel_ddd = response.data[0].Cel_DDD;
            $scope.signUpInfo.celular = response.data[0].Cel;
            $scope.signUpInfo.crmv = response.data[0].CRMV;
            $scope.signUpInfo.email = response.data[0].Email;
            $scope.signUpInfo.especialidade = response.data[0].Especialidade;
            $scope.signUpInfo.cpf = response.data[0].CPF;
            $scope.signUpInfo.tipo = response.data[0].Tipo;
            $scope.signUpInfo.stat = response.data[0].Status;
            $scope.signUpInfo.ativo = response.data[0].Ativo;
            $scope.signUpInfo.auth = response.data[0].Auth;
            $scope.signUpInfo.dateCreated = response.data[0].DateCreated;
            $scope.signUpInfo.dateModified = response.data[0].DateModified;
			$scope.validateForm();

            $scope.signUpInfo.crmvArray = $scope.signUpInfo.crmv.split("-");
            $scope.signUpInfo.crmv = $scope.signUpInfo.crmvArray[0];
            $scope.signUpInfo.crmvEstado = $scope.signUpInfo.crmvArray[1];

            $scope.signUpInfo.especialidades = $scope.signUpInfo.especialidade.split(",");

            for(var i = 0; i < $scope.signUpInfo.especialidades.length ; i++){
                if($scope.signUpInfo.especialidades[i] === "" || $scope.signUpInfo.especialidades[i] === " "){
                    $scope.signUpInfo.especialidades.splice(i,1);
                }
            }
        });

        $scope.updatesignUpInfoData = function (){
            $scope.validateCPF($scope.signUpInfo.cpf);


                $scope.loading = true;
                var data = $scope.signUpInfo;
                data.action = "LoadList";
                $http.post("php/updateUserData.php", data).then( function (response){
                    if(response.data === "ERROR"){
                        $scope.error=true;
                        $scope.loading = false;
                    }else{
                        $scope.waitingConfirmation = true;
                        $scope.loading = false;

                    }
                });
        };

        $scope.validateCPF =  function(strCPF) {
            if(!strCPF || !strCPF.length){
                return;
            }

            var Soma;
            var Resto;
            Soma = 0;
            if (strCPF == "00000000000"){
                $scope.error=true;
                $scope.errorMessage.cpfInvalido = "CPF inválido";
                return false;
            }
            for (i=1; i<=9; i++){
                Soma = Soma + parseInt(strCPF.substring(i-1, i), 10) * (11 - i);
            }
            Resto = (Soma * 10) % 11;
            if ((Resto == 10) || (Resto == 11)){
                Resto = 0;
            }
            if (Resto != parseInt(strCPF.substring(9, 10), 10)){
                $scope.error=true;
                $scope.errorMessage.cpfInvalido = "CPF inválido";
                return false;
            }
                Soma = 0;
                for (i = 1; i <= 10; i++)
                    Soma = Soma + parseInt(strCPF.substring(i-1, i), 10) * (12 - i);
                    Resto = (Soma * 10) % 11;
                    if ((Resto == 10) || (Resto == 11))
                        Resto = 0;
                if (Resto != parseInt(strCPF.substring(10, 11), 10) ){
                    $scope.error=true;
                    $scope.errorMessage.cpfInvalido = "CPF inválido";
                    return false;
                }
                $scope.errorMessage.cpfInvalido = "";
                return true;
        };

        $scope.validateForm = function (){
            
                

            if( $scope.signUpInfo.nome !== "" &&
                $scope.signUpInfo.email !== "" &&
                $scope.signUpInfo.senha !== "" &&
                $scope.signUpInfo.senhaConf !== "" &&
                $scope.signUpInfo.endereco !== "" &&
                $scope.signUpInfo.end_numero !== "" &&
                $scope.signUpInfo.bairro !== "" &&
                $scope.signUpInfo.cidade !== "" &&
                $scope.signUpInfo.estado !== "" &&
                $scope.signUpInfo.cep !== "" &&
                $scope.signUpInfo.tel_ddd !== "" &&
                $scope.signUpInfo.telefone !== "" &&
                $scope.signUpInfo.cel_ddd !== "" &&
                $scope.signUpInfo.celular !== "" &&
                $scope.signUpInfo.crmv !== "" &&
                $scope.signUpInfo.especialidade !== "" &&
                $scope.signUpInfo.cpf !== ""
            ){
                $scope.error = false;
                // $scope.validatePass();
                $scope.validateCPF($scope.signUpInfo.cpf);
            }else{
                $scope.error = true;
                $scope.errorMessage.incompleto = "Por favor, preencha todos os campos indicados";
                $scope.validateCPF($scope.signUpInfo.cpf);
            }


        };

        $scope.confirmDelete = function(){
            var deleteConfirmation = confirm("Tem certeza que deseja excluir permanentemente este usuário?");

            if(deleteConfirmation){
                $scope.deleteUser();
            }
        };

        $scope.deleteUser = function(){
                $scope.loading = true;
                var data = {};
                data.userID = $scope.signUpInfo.ID;
                data.action = "DeleteUser";
                console.log("DELTE " + data.userID);
                $http.post("php/associados.php", data).then( function (response){
                    if(response.data === "SUCCESS"){
                        $scope.waitingConfirmation = true;
                        $scope.loading = false;
                    }else{
                        $scope.error=true;
                        $scope.loading = false;
                    }

                    console.log(response);
                });
        };


        
    }]);
    