/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular.module('app', ['ui.router', 'ui.bootstrap', 'ngAnimate', 'truncate', 'ngStorage','nya.bootstrap.select'])
    .config(['$urlRouterProvider', '$stateProvider', function($urlRouteProvider, $stateProvider){
        $urlRouteProvider.otherwise('/');
        
        $stateProvider
            .state('dashboard', {
            url: '/',
            templateUrl: 'templates/dashboard.html',
            controller: 'dashboardCtrl'
        })
            .state('associados', {
            url: '/associados',
            templateUrl: 'templates/associados.html',
            controller: 'associadosCtrl'
        })
            .state('login', {
            url: '/login',
            templateUrl: 'templates/login.html',
            controller: 'loginCtrl'
        })
            .state('transacoes', {
            url: '/transacoes',
            templateUrl: 'templates/transacoes.html',
            controller: 'transacoesCtrl'
        })
            .state('anuidades', {
            url: '/anuidades',
            templateUrl: 'templates/anuidades.html',
            controller: 'anuidadesCtrl'
        })
            .state('associado', {
            url: '/associado/{id}',
            templateUrl: 'templates/associado.html',
            controller: 'associadoCtrl'
        })
            .state('evento', {
            url: '/evento/{id}',
            templateUrl: 'templates/evento.html',
            controller: 'eventoCtrl',
            params: {
                id: null
            }
        })
            .state('eventos', {
            url: '/eventos/',
            templateUrl: 'templates/eventos.html',
            controller: 'eventosCtrl'
        });
    }])
    .controller('mainCtrl', ['$scope','AuthenticationService' , function($scope, AuthenticationService){
        
    }]);