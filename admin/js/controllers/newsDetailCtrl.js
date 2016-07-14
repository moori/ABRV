/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .controller('newsDetailCtrl', ['$scope', '$http', '$stateParams', function($scope, $http, $stateParams){
        

        // $scope.logged = $rootScope.logged;
        
        $scope.loadNew = function(){
            $http.get("php/newDetail.php", {params: {new_ID: $stateParams.id}}).success(function (response) {
                $scope.noticia = response[0];
                console.log(response);
            });
        };
        $scope.loadNew();
        
        
        
    }]);