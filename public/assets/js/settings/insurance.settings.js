var action_insurance = function($scope, $http )
{
	var vm 	  = this;
	
	vm.insert = function()
	{
		var Data = $scope.default.insurance;
		$http({
		    method: 'POST',
		    url: '/settings/insurance/insert/',
		    data:  $.param( Data ) ,
		    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).then(function(response){
			Notify.response( response.data );
			if( response.status )
			{	
				$scope.default.insurance.title = '';
				response.data.insurance.title_initial = angular.copy(response.data.insurance.title);
				$scope.data.insurance.push( response.data.insurance )
			}
		});
	};
	vm.update = function( insurance )
	{
		var Data = angular.copy( insurance );
		
		$http({	
		    method: 'POST',
		    url: '/settings/insurance/update/' + insurance.id,
		    data:  $.param( Data ) ,
		    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).then(function(response){
			Notify.response( response.data );
			if( response.status )
			{
				insurance.title_initial = insurance.title;
			}
		});
	}
	vm.toggle_status = function( insurance )
	{
		$http.get('/settings/insurance/toggle-status/'+insurance.id).success(function( response ){
			Notify.response( response );
			if(response.status)
			{	
				insurance.status = response.insurance_status;
			}
		});
	};
	vm.disabled = function( insurance )
	{
		return ( insurance.title === insurance.title_initial ) ?
				true : false;
	}
}

