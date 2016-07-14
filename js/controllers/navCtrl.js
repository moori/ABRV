/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .controller('navCtrl', ['$scope', '$stateParams', '$state', function($scope, $stateParams, $state){
        $scope.$on('$locationChangeSuccess', function(scope, next, current) {
           $('#mobileMenuBtn').click();
        });
        
    }]);