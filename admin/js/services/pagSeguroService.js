/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .service('pagSeguroService', ['$http', '$state','AuthenticationService', function($http, $state, AuthenticationService){
        var self = this;
        
        self.Request = function (data){
        var promise = $http.post("php/PagSeguroAPI.php", data);
                return promise;
        };

        self.updateNotifications = function (){
        $http.post("php/updateNotification.php");
        };
        
}]);