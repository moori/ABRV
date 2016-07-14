/* global angular */
/*jshint browser:true */
/*jshint devel:true */

angular
    .module('app')
    .controller('footerCtrl', ['$scope', '$http','AuthenticationService', function($scope, $http, AuthenticationService){

    $scope.newsletterEmail = "";
	$scope.emailSent=false;
	$scope.loading=false;
	$scope.errorMSG=false;

    

    $scope.sendEmail = function(){
	var data = {action:"sendEmail", email: $scope.newsletterEmail};
	$scope.loading=true;
		$http.post("php/newsletter.php", data).success(function(response){

		$scope.loading=false;
			if (response == "Sent"){
				$scope.emailSent=true;
				} else {
					console.log("error");

					$scope.errorMSG=true;
					return response;
				}
			});
    };
    
}]);