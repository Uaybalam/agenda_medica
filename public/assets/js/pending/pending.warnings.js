

angular
	.module('app_pending_warnings', ['angularUtils.directives.dirPagination'] )
	.controller('ctrl_pending_warnings', function( $scope, $http ){
		
		$scope.default  = {};
		
		$scope.initialize = function( data )
		{		
			$http.get('/pending/warnings/initialize/')
				.then(function( response ){
					$scope.data = response.data;
				})
				.finally(function(){
					setTimeout(function(){
						$('[data-toggle="tooltip"]').tooltip();
					}, 1);
				})
		};
		
		$scope.ngHelper = new ngHelper($scope);
			
	});
