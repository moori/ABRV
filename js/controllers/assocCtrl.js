/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .controller('assocCtrl', ['$scope', '$http', '$state','AuthenticationService', '$rootScope', 'EmailService', '$stateParams', function($scope, $http, $state, AuthenticationService, $rootScope, EmailService, $stateParams){

        
        $scope.showErrors=false;

        $scope.error=false;
        $scope.waitingConfirmation = false;
        $scope.loading = false;
        $scope.grad = false;

        $scope.errorMessage ={
            emailRegistrado: "",
            loginIncorreto: "",
            cpfInvalido: "",
            senhaDiferente: ""
        };

        $scope.signUpInfo = {
            nome: "",
            email: "",
            senha: "",
            senhaConf: "",
            endereco: "",
            end_numero: "",
            end_complemento: "",
            bairro: "",
            cidade: "",
            estado: "",
            cep: "",
            tel_ddd: "",
            telefone: "",
            cel_ddd: "",
            celular: "",
            crmv: "",
            crmvEstado: "",
            especialidade: "",
            coirma: "",
            faculdade: "",
            cpf: "",
            tipo: "",
            grad:"111"
        };

        if($stateParams.memberCode ===""){
            $scope.signUpInfo.tipo = 0;
        }else if($stateParams.memberCode ==="d026c90a202654ce0fa77ecd3b72c532d4170f8c"){
            $scope.signUpInfo.tipo = 2;
        }else if($stateParams.memberCode ==="5de94cc2b835c3883af6badae40f4db941f4ccda"){
            $scope.signUpInfo.tipo = 3;
        }

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

        // $scope.estados = [
        //     "Acre",
        //     "Alagoas",
        //     "Amazonas",
        //     "Amapá",
        //     "Bahia",
        //     "Ceará",
        //     "Distrito Federal",
        //     "Espírito Santo",
        //     "Goiás",
        //     "Maranhão",
        //     "Mato Grosso",
        //     "Mato Grosso do Sul",
        //     "Minas Gerais",
        //     "Pará",
        //     "Paraíba",
        //     "Paraná",
        //     "Pernambuco",
        //     "Piauí",
        //     "Rio de Janeiro",
        //     "Rio Grande do Norte",
        //     "Rondônia",
        //     "Rio Grande do Sul",
        //     "Roraima",
        //     "Santa Catarina",
        //     "Sergipe",
        //     "São Paulo",
        //     "Tocantins"
        // ];
        
        $scope.signUpInfo.crmvEstado = $scope.estados[25];
        $scope.signUpInfo.estado = $scope.estados[25];

        $scope.especialidades = [
            {"valor":"radiologia", "label":"Radiologia"},
            {"valor":"ultrassom", "label":"Ultrassonografia"},
            {"valor":"tomografia", "label":"Tomografia Computadorizada"},
            {"valor":"ressonancia", "label":"Imagem por Ressonância Magnética"}
        ];


        $scope.entidadesCoirmas = [
            {"valor":"abev", "label":"ABEV"},
            {"valor":"abravas", "label":"ABRAVAS"}
        ];

        // $scope.especialidades = [
        //     "Radiologia",
        //     "Ultrassonografia",
        //     "Tomografia Computadorizada",
        //     "Imagem por Ressonância Magnética"
        // ];
        // $scope.signUpInfo.especialidade = $scope.especialidades[0];



        $scope.signup = function (){
            $scope.validatePass();
            $scope.validateCPF($scope.signUpInfo.cpf);
            $scope.signUpInfo.faculdade = $scope.signUpInfo.faculdadeNome + "," + $scope.signUpInfo.faculdadeAno;
            $scope.signUpInfo.grad = $scope.grad;
            
            console.log($scope.signUpInfo);

            $scope.showErrors=true;
            if($scope.userForm.$valid){
                $scope.loading = true;
                AuthenticationService.signUpUser($scope.signUpInfo).then( function (response){
                    if(response.data === "ERROR"){
                        // console.log("Email já registrado");
                        $scope.error=true;
                        $scope.errorMessage.usuarioRegistrado = true;
                        $scope.loading = false;
                    }else{

                        var data = {
                            nome: $scope.signUpInfo.nome,
                            email: $scope.signUpInfo.email,
                            tipo:$scope.signUpInfo.tipo
                        };
                        EmailService.SendAuthentication(data).then( function(response){
                            $scope.waitingConfirmation = true;
                            $scope.loading = false;
                        });

                    }
                });
            }

            
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

        $scope.validatePass = function () {
            if($scope.signUpInfo.senhaConf != $scope.signUpInfo.senha){
                $scope.error=true;
                $scope.errorMessage.senhaDiferente = "As senhas não conferem";
            }else{
                $scope.errorMessage.senhaDiferente ="";
            }
        };

        $scope.validateForm = function (){

            if( $scope.signUpInfo.nome !== "" &&
                $scope.signUpInfo.email !== "" &&
                $scope.signUpInfo.senha !== "" &&
                $scope.signUpInfo.senhaConf !== "" &&
                $scope.signUpInfo.endereco !== "" &&
                $scope.signUpInfo.end_numero !== "" &&
                $scope.signUpInfo.end_complemento !== "" &&
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
                $scope.validatePass();
                $scope.validateCPF($scope.signUpInfo.cpf);
            }else{
                $scope.error = true;
                $scope.errorMessage.incompleto = "Por favor, preencha todos os campos indicados";
            }


        };

        $scope.sendData = function (userData){
            console.log("Submit " + userData);
        };
        
        $scope.validateForm();
    
}]);