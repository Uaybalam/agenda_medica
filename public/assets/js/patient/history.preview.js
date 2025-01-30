angular
	.module('app_preview_history', ['angular.filter'])
	.controller('ctrl_preview_history',function( $scope, $http ){
		$scope.data    = {};
		$scope.options = {Yes: "Si", No: "No"};
		$scope.initialize = function( patient_id )
		{	
			$http.get('/patient/history/init/'+ patient_id).success(function( response ){
				$scope.data = response ;
			})
		}

	});

