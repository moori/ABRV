/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .controller('newsCtrl', ['$scope', '$http', function($scope, $http){
        
        $scope.loadNew = function(){
            $http.get("php/news.php").success(function (response) {
                $scope.noticias = response;
                console.log($scope.noticias);
            });
        };
        $scope.loadNew();
        
        
        
    }]);