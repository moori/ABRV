angular
    .module('app')
    .filter('espFilter', function(){
        return function(input){
            switch(input){
                case "radiologia":
                    return "Radiologia";
                case "ultrassom":
                    return "Ultrassonografia";
                case "tomografia":
                    return "Tomografia Computadorizada";
                case "ressonancia":
                    return "Imagem por Ressonância Magnética";
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

    .filter('statusInscricao', function(){
        return function(input){
            switch(input){
                case "0":
                    return "Em processamento";
                case "1":
                    return "Inscrito";
                case "-1":
                    return "Cancelada";
            }
        };
    })

    .filter('capitalize', function() {
        return function(input, all) {
        var reg = (all) ? /([^\W_]+[^\s-]*) */g : /([^\W_]+[^\s-]*)/;
        return (!!input) ? input.replace(reg, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();}) : '';
        };
    })

    .filter('dataBrasileira', function() {
        return function(input) {
            if(input !== undefined){
                var explode = input.split("-");
                return explode[2] + "/" + explode[1] +"/"+ explode[0];
            }else{
                return null;
            }
        };
    })


    .filter('dataBrasileiraSemHoras', function() {
        return function(input) {
            if(input !== undefined){
                var dia = input.split("|");
                var explode = dia[0].split("-");
                return explode[2] + "/" + explode[1] +"/"+ explode[0];
            }else{
                return null;
            }
        };
    });