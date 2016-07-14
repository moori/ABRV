/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .controller('homeCtrl', ['$scope', '$location', '$http', 'AuthenticationService','$window', function($scope, $location, $http, AuthenticationService, $window ){

        $scope.loadList = function(){
            $http.get("php/home.php").then(function (response) {
                $scope.noticias = response.data;
            });
        };
        $scope.loadList();
        
        // $scope.isActive = function () { 
        //     var viewLocation;
        //     return viewLocation === $location.path();
        // };

        $scope.myInterval = 5000;
        $scope.noWrapSlides = false;

        var slides = $scope.slides = [];

        slides.push({
              image: 'images/slider/IVSindiv_.png',
              externalLink: 'http://www.sindiv.com.br',
            });
        slides.push({
              image: 'images/slider/curso-banner-onco2.jpg',
              link: 'evento({id: 3, slug: "curso_de_oncologia_II"})',
            });

        // slides.push({
        //       image: 'images/slider/xxx.png',
        //       link: 'evento({id: 3, slug: "curso_de_oncologia_II"})',
        //     });
        // slides.push({
        //       image: 'images/slider/xxx.png',
        //       link: 'evento({id: 4, slug: "SINDIV_VI"})',
        //     });
        
       
        
    }]);
    