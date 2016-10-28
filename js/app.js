/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular.module('app', ['ui.router', 'ui.bootstrap', 'ngAnimate', 'truncate', 'ngSanitize', 'bootstrapLightbox', 'nya.bootstrap.select', 'ngStorage'])
    .config(['$urlRouterProvider', '$stateProvider', function($urlRouteProvider, $stateProvider){
        $urlRouteProvider.otherwise('/');

        $stateProvider
            .state('home', {
            url: '/',
            templateUrl: 'templates/home.html',
            controller: 'homeCtrl'
        }).

        state('associese', {
            url: '/assoc/{memberCode}',
            templateUrl: 'templates/associese.html',
            controller: 'assocCtrl'
        }).

        state('comunicado', {
            url: '/colegio',
            templateUrl: 'templates/colegio/comunicado.html'
        }).state('objetivosDoColegio', {
            url: '/colegio/objetivosDoColegio',
            templateUrl: 'templates/colegio/objetivosDoColegio.html'
        }).state('diretoriaColegio', {
            url: '/colegio/diretoria',
            templateUrl: 'templates/colegio/diretoria.html'
        }).state('membros', {
            url: '/colegio/membros',
            templateUrl: 'templates/colegio/membros.html'
        }).state('termosDeDisplasia', {
            url: '/colegio/termosDeDisplasia',
            templateUrl: 'templates/colegio/termosDeDisplasia.html'
        }).state('normasDoColegio', {
            url: '/colegio/normasDoColegio',
            templateUrl: 'templates/colegio/normasDoColegio.html'
        }).

        state('membrosTeste', {
            url: '/colegio/membrosTeste',
            templateUrl: 'templates/colegio/membrosTeste.html'
        }).

        state('materias', {
            url: '/materias',
            templateUrl: 'templates/materias.html'
        }).

        state('eventos', {
            url: '/eventos',
            templateUrl: 'templates/eventos.html',
            controller: "eventosCtrl"
        }).
        state('evento', {
            url: '/evento/{id}/{slug}',
            templateUrl: 'templates/evento.html',
            controller: "eventoCtrl",
            params: {
                id: null,
                slug: null
            }
        }).
        state('inscricaoEvento', {
            url: '/evento/inscricoes/{id}/{slug}',
            templateUrl: 'templates/inscricaoEvento.html',
            controller: "inscricaoEventoCtrl",
            params: {
                id: null,
                slug: null
            }
        }).
        state('eventosPoliticaCancelamento', {
            url: '/evento/politicaDeCancelamento',
            templateUrl: 'templates/politicaDeCancelamento.html'
        }).

        state('sindiv', {
            url: '/sindiv',
            templateUrl: 'templates/sindiv/sindiv.html'
        }).
        state('sindivAnais', {
            url: '/sindiv/anais',
            templateUrl: 'templates/sindiv/sindivAnais.html'
        }).

        state('objetivos', {
            url: '/sobre/objetivos',
            templateUrl: 'templates/sobre/objetivos.html'
        }).state('estatuto', {
            url: '/sobre/estatuto',
            templateUrl: 'templates/sobre/estatuto.html'
        }).state('fundador', {
            url: '/sobre/fundador',
            templateUrl: 'templates/sobre/fundador.html'
        }).state('diretoria', {
            url: '/sobre/diretoria',
            templateUrl: 'templates/sobre/diretoria.html'
        }).state('historiaDoRX', {
            url: '/sobre/historiaDoRX',
            templateUrl: 'templates/sobre/historiaDoRX.html'
        }).

        state('faleConosco', {
            url: '/faleConosco',
            templateUrl: 'templates/faleConosco.html',
            controller: 'faleConoscoCtrl'
        }).

        state('noticia', {
            url: '/noticia/{id}/{slug}',
            templateUrl: 'templates/noticiaDetalhe.html',
            controller: 'newsDetailCtrl',
            params: {
                id: null,
                slug: null
            }
        }).
        state('noticias', {
            url: '/noticias/',
            templateUrl: 'templates/news.html',
            controller: 'newsCtrl'
        }).

        state('login', {
            url: '/login',
            templateUrl: 'templates/login.html',
            controller: 'loginCtrl'
        }).

        state('activation', {
            url: '/activation/{authCode}',
            templateUrl: 'templates/activation.html',
            controller: 'activationCtrl'
        }).

        state('newsletterActivation', {
            url: '/newsletterActivation/{authCode}',
            templateUrl: 'templates/newsletterActivation.html',
            controller: 'newsletterActivationCtrl'
        }).

        state('eventosNotification', {
            url: '/eventosNotification',
            templateUrl: 'templates/eventosNotification.html',
            controller: 'eventosNotificationCtrl'
        }).

        state('dashboard', {
            url: '/dashboard',
            templateUrl: 'templates/dashboard/dashboard.html',
            controller: 'dashboardCtrl'
        }).
        state('atualizarDados', {
            url: '/dashboard/atualizarDados',
            templateUrl: 'templates/dashboard/atualizarDados.html',
            controller: 'atualizarDadosCtrl'
        }).
        state('renovarAnuidade', {
            url: '/dashboard/renovarAnuidade',
            templateUrl: 'templates/dashboard/renovarAnuidade.html',
            controller: 'renovarAnuidadeCtrl'
        }).
        state('alterarSenha', {
            url: '/dashboard/alterarSenha',
            templateUrl: 'templates/dashboard/alterarSenha.html',
            controller: 'alterarSenhaCtrl'
        }).
        state('inscricoes', {
            url: '/dashboard/inscricoes',
            templateUrl: 'templates/dashboard/inscricoes.html',
            controller: 'inscricoesCtrl'
        }).
        state('cancelar', {
            url: '/dashboard/cancelar',
            templateUrl: 'templates/dashboard/cancelar.html',
            controller: 'cancelarCtrl'
        }).

        state('pacotes', {
            url: '/pacotes',
            templateUrl: 'templates/pacotes.html'
        }).

        state('resetPassword', {
            url: '/resetPassword',
            templateUrl: 'templates/resetPassword.html',
            controller: 'resetPasswordCtrl'
        });
    }])
    .controller('mainCtrl', [ '$scope', 'AuthenticationService', function ($scope) {
//        $scope.loggedIn = false;
    }]);
