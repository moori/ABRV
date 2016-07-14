/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .service('EmailService', ['$http', '$state', function($http, $state){
        var self = this;
        
        
        self.SendAuthentication = function (data){

            var promisse = $http.post("php/mailAuthCode.php", data);
                return promisse;
        };
        
    
}]);