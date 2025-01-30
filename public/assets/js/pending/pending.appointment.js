
angular
	.module('app_pending_appointment', ['angularUtils.directives.dirPagination'] )
	.controller('ctrl_pending_appointment', function( $scope, $http, $filter ){
		
		$scope.default 		 = {};
		$scope.filter_status = '-1';

		$scope.initialize = function( data )
		{	
			$scope.data = JSON.parse(data);
			
			update_lapse_time( $scope.data.appointments );
		};

		$scope.specialFilter = function (data) {
		
			if( parseInt(data.status) === parseInt( $scope.filter_status ) )
			{
				return true;
			}
			else if( parseInt( $scope.filter_status ) === -1 )
			{
				return true;
			}
			else
			{
				return false;
			}
		};

		$scope.sort = function(keyname){
		    $scope.sortKey = keyname;         
	        $scope.reverse = !$scope.reverse;
	    };
	    
	    $scope.get_status = function( sta ){
			var status = $filter('filter')( $scope.data.catalog_status , {
				id: parseInt(sta)
			}, true );

			return status[0].name;
		};

	});

function update_lapse_time( data )
{
	for( i=0; i< data.length; i++ )
	{	
		var lapse_time = moment( data[i].date_appointment, "YYYY-MM-DD hh:mm:ss").fromNow();;
        data[i].lapse_time = lapse_time;
	};
}



