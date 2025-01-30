


angular
	.module('app_manager',['angularUtils.directives.dirPagination'])
	.controller('ctrl_manager', function( $scope, $http ){

		$scope.active_status = 1;

		$scope.default = {
			user: {
				hide_password: true,
				nick_name: '',
				password: '',
				names: '',
				last_name: '',
				email: '',
				access_type: 'secretary',
				medic_type: '',
				medic_npi: '',
				gender:'Male',
				marital_status: 'Single',
				phone: ''
			}
		};

		$scope.initialize = function()
		{
			$http.get('/user/manager/init').then(function( response ){
				$scope.data = response.data;
			})
		}
		
		$scope.sort = function(keyname){
		    $scope.sortKey = keyname;         
	        $scope.reverse = !$scope.reverse;
	    }
		
		$scope.searchStatus = function( data ){
			if($scope.active_status == 0)
			{
				return data;
			}
			else if(data.status == 1 ) {
				return data;	
			}
		}

		$scope.action_user = {
			open: function(){
				$('#user-manager-modal-user-create').modal();
			},	
			submit:function( event ){

				event.preventDefault();
				
				var Data = angular.copy( $scope.default.user );
				
				$('.submit').attr('disabled','disabled');

				$http.post('/user/manager/create/', $.param( Data ), $httpConfig ).then(function(response ){
					
					if( response.data.status )
					{	
						window.location.href = '/user/manager/'+response.data.user_id;
					}
					else
					{		
						Notify.error( response.data.message );
						$('.submit').removeAttr('disabled');
					}
		        });
			}
		}
	});
