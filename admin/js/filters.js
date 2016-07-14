angular
    .module('app')
    .filter('pagSeguroStatus', function(){
        return function(input){
            switch(input){
                case null:
                    return "Nenhuma Atividade";
                case undefined:
                    return "Nenhuma Atividade";
                case "0":
                    return "Iniciada";
                case "1":
                    return "Aguardando Pagamento";
                case "2":
                    return "Em Análise";
                case "3":
                    return "Paga";
                case "4":
                    return "Disponível";
                case "5":
                    return "Em Disputa";
                case "6":
                    return "Devolvida";
                case "7":
                    return "Cancelada";
                case "8":
                    return "Chargeback debitado";
                case "9":
                    return "Em contestação";
            }
        };
    })

    .filter('tipoMembro', function(){
        return function(input){
            switch(input){
                case "0":
                    return "Veterinário";
                case "1":
                    return "Associado Efetivo";
                case "2":
                    return "Associado Honorário";
                case "3":
                    return "Associado Benemérito";
                case "4":
                    return "Associado Correspondente";
                case "5":
                    return "Associado Residente";
                case "6":
                    return "Associado Colegiado";
                case "7":
                    return "Associado Emérito";
                case "8":
                    return "Graduando";
                case "9":
                    return "Veterinário Sócio de Entidade Co-Irmã";
            }
        };
    })

    .filter('anuidadeStatus', function(){
            return function(input){

                switch(input){
                    case null:
                        return "Nenhuma Atividade";
                    case undefined:
                        return "Nenhuma Atividade";
                    case "0":
                        return "Atrasada";
                    case "1":
                        return "Em dia";
                }
        };
    })

    .filter('SubStatus', function(){
            return function(input){

                switch(input){
                    case null:
                        return "Interessado";
                    case undefined:
                        return "Interessado";
                    case "0":
                        return "Interessado";
                    case "1":
                        return "Inscrito";
                }
        };
    })
    .filter('dateTimeBR', function(){
            return function(input){
                if(input === undefined){
                    return null;
                }

                var date = input.split("|");
                var time = date[1];
                date = date[0].split("-");
                date = date[2] + "/" + date[1] + "/" + date[0];
                return date + " às " + time;

        };
    })
    .filter('pagSeguroDate', function(){
            return function(input){
                if(input === undefined){
                    return null;
                }

                var date = input.split("T");
                if(date[1] === undefined){
                    return null;
                }
                var time = date[1].split(".");
                time = time[0];
                date = date[0];
                return date + " | " + time;

        };
    })
    .filter('num', function() {
    return function(input) {
      return parseInt(input, 10);
    };
});