/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .service('backendService', ['$http', '$state','AuthenticationService', function($http, $state, AuthenticationService){
        var self = this;
        
        self.getAnoVigente = function (){
        var data = {action: "GetAnoVigente"};
        var promise = $http.post("php/backendAPI.php", data);
                return promise;
        };

        self.getAdmins = function (){
        var data = {action: "GetAdmins"};
        var promise = $http.post("php/backendAPI.php", data);
                return promise;
        };
        
}]);