
var action_settings = function( $scope, $http )
{
	var vm = this;
	
	//options
	vm.option_selected;
	
	vm.options = {
		insurance: { 
			url_insert: '/settings/insurance/insert/',
			url_update: '/settings/insurance/update/',
			url_delete: '/settings/insurance/delete/',
		},
		educations: { 
			url_insert: '/settings/education/insert/',
			url_update: '/settings/education/update/',
			url_delete: '/settings/education/delete/',
		},
		medications: {
			url_insert: '/settings/medication/insert/',
			url_update: '/settings/medication/update/',
			url_delete: '/settings/medication/delete/',
		},
		specialities: {
			url_insert: '/settings/speciality/insert/',
			url_update: '/settings/speciality/update/',
			url_delete: '/settings/speciality/delete/',
		},
		results: {
			url_insert: '/settings/results/insert/',
			url_update: '/settings/results/update/',
			url_delete: '/settings/results/delete/',
		},
		global: {
			url_insert: '/settings/global/insert/',
			url_update: '/settings/global/update/',
			url_delete: '/settings/global/delete/',
		},
		refer_services:{
			url_insert: '/settings/referrals-services/insert/',
			url_update: '/settings/referrals-services/update/',
			url_delete: '/settings/referrals-services/delete/',
		}
	};
	
	vm.set_option = function( option )
	{	
		vm.option_selected = option;
	};
	
	vm.insert = function( data_default )
	{
		var Data = data_default;
		$http({
		    method: 'POST',
		    url: vm.options[vm.option_selected].url_insert,
		    data:  $.param( Data ) ,
		    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).then(function(response){
			Notify.response( response.data );
			
			if( response.data.status )
			{	
				
				for(d in data_default)
				{
					response.data.item[d + '_initial'] = data_default[d];
					data_default[d] = '';
				}
				
				$scope.data[vm.option_selected].push( response.data.item )
			}	
		});
	};
	
	vm.update = function( Item )
	{
		var Data = angular.copy( Item );
		
		$http({	
		    method: 'POST',
		    url:  vm.options[vm.option_selected].url_update + Item.id,
		    data:  $.param( Data ) ,
		    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).then(function(response){
			Notify.response( response.data );
			if( response.data.status )
			{
				for(d in Data)
				{	
					Item[d + '_initial'] = Item[d];
				}
			}
		});
	};

	vm.delete = function( Item )
	{
		$http.get(vm.options[vm.option_selected].url_delete+Item.id).success(function( response ){
			
			Notify.response( response );
			
			if(response.data.status)
			{	
				var position = $scope.data[vm.option_selected].indexOf(Item);
				$scope.data[vm.option_selected].splice( position ,1);
			}
		});
	};
}


angular
	.module('app_settings',[] )
	.controller('ctrl_settings', function( $scope, $http ){
		
		$scope.initialize = function(){
			$http.get('/settings/init').success(function( response ){
				$scope.data = response;
			}).finally(function(){	
				$scope.action_settings.set_option('insurance');
			})
		};

		$scope.disabled = function( data, validate )
		{	
			var $disabled = true;
			
			validate  = validate || ['title'];
			
			for( i = 0; i <= validate.length; i ++)
			{
				if(data[validate[i]] != data[validate[i] + '_initial'])
				{	
					$disabled = false;
					break;
				}	
			}

			return  $disabled;
		};

		//$scope.action_insurance = new action_insurance($scope, $http);
		$scope.action_settings  = new action_settings($scope, $http );
	});


