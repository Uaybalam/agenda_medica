
angular
	.module('app_patient_appointments', ['angularUtils.directives.dirPagination'])
	.controller('ctrl_patient_appointments', function( $scope, $http ){
		
		$scope.data = {
			appointments: [],
			patient: {},
			array_status: []
		};
		
		$scope.filter_status = "";

		$scope.initialize = function( PATIENT_ID )
		{	
			$http.get('/patient/appointments/' + PATIENT_ID + '/initialize')
				.then(function( response ){
					$scope.data = response.data;

					for( var i = 0; i < response.data.appointments.length; i ++)
					{	
						$scope.data.appointments[i].status_string = response.data.array_status[response.data.appointments[i].status];
					}
				}).finally(function(){
					
					$scope.sort('full_date_sort');

					setTimeout(function(){
						$('[data-toggle="tooltip"]').tooltip();
					}, 1);
				});
		}
			
		$scope.specialFilter = function (data) {
			
			if( data.status_string === $scope.filter_status )
			{
				return true;
			}
			else if( $scope.filter_status === '' )
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		$scope.sort = function(keyname){
		    $scope.sortKey = keyname;         
	        $scope.reverse = !$scope.reverse;
	    }

	    $scope.visitTypeClass = function( visitType )
		{	

			if(visitType === 'New' )
			{
				return 'label-success';
			}
			else if(visitType === 'Established')
			{
				return 'label-primary';
			}
			else if(visitType === 'F/Up')
			{
				return 'label-info'
			}
			else
			{
				return 'label-warning';	
			}
			
		}

	});