/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .controller('dashboardCtrl', ['$scope', '$location', '$http', 'backendService', 'AuthenticationService', '$rootScope', function($scope, $location, $http, backendService, AuthenticationService, $rootScope){
        
        AuthenticationService.checkToken();
        $scope.logged = $rootScope.logged;

        $scope.loadDashboard = function(){

            $http.get('data/config.json').success(function(data) {
              $scope.configData = data;
            });
        };

        $scope.loadDashboard();
        
    }]);
    