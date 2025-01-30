angular
	.module('app_capture_history', [] )
	.controller( 'ctrl_capture_history', function( $scope, $http ){

		$scope.recorded_data = {} ;
		$scope.submit = function()
		{	
			$('.submit').attr('disabled','disabled');
			var Data = get_param( $scope.catalog_history );
			
			Data.current_medications = $scope.recorded_data.current_medications;
			Data.comments            = $scope.recorded_data.comments;
			Data.surgeries           = $scope.recorded_data.surgeries;
			$http({
		   	 	method: 'POST',
			    url: '/patient/history/save/' + $scope.patient.id ,
			    data:  $.param( Data ) ,
			    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			}).then(function(response){
				
				if( response.data.status )
				{
					window.location =  response.data.redirect;
				}
				else
				{	
					Notify.error(response.data.message);
					$('.submit').removeAttr('disabled');
				}
			});
			
		};

		$scope.initialize  = function( patient_id ){
			
			$http.get('/patient/history/data/'+ patient_id).success(function( response ){
				$scope.recorded_data.current_medications = response.patient.recorded_history_current_medications;
				$scope.recorded_data.comments            = response.patient.recorded_history_comments;
				$scope.recorded_data.surgeries           = response.patient.recorded_history_surgeries;
			});

			setTimeout(function(){
				$('[data-toggle="tooltip"]').tooltip();
			}, 1000)
		}
	});		


var get_param = function( catalog_history ){
	
	var D = {
		data: []
	};
	for(position in catalog_history)
	{	
		for(var i = 0; i< catalog_history[position].length; i++)
		{	
			for (var j = 0 ; j< catalog_history[position][i].data.length; j++ ) {
				D.data.push({
					position: position,
					group: catalog_history[position][i].data[j].group,
					title: catalog_history[position][i].data[j].title,
					patient: catalog_history[position][i].data[j].patient,
					family: catalog_history[position][i].data[j].family,
					comments: catalog_history[position][i].data[j].comments,
				});
			};
		}
	}
	return D; 
}

