var action_procedure = function($scope, $http)
{
	var SELF = this;

	this.modal = function(){
		return '#encounter-detail-modal-procedure';
	};
	this.submit = function( event ){
		event.preventDefault();
		var Form = $(event.currentTarget);
		
		var Data ={
				procedure_text: $scope.default.encounter.procedure_text,
				procedure_xray_request: $scope.default.encounter.procedure_xray_request,
				procedure_patient_education: $scope.default.encounter.procedure_patient_education,
			},
			Btn  = $('.submit', Form );
		
		$(Btn).attr( 'disabled', 'disabled' );
		
		$http({
		    method: 'POST',
		    url: $(Form).attr('action') + '/' + $scope.data.encounter.id ,
		    data:   $.param( Data ) ,
		    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).then(function(response){
			
			Notify.response( response.data );
			if(response.data.status === 1 )
			{
				$scope.data.encounter =  response.data.encounter;
				$(SELF.modal()).modal('hide');
			}

			$(Btn).removeAttr( 'disabled' );
		});
	};
	this.open = function(){	
		
		var data_copy  			= angular.copy($scope.data.encounter );
		
		var current_education	= (data_copy.procedure_patient_education!=null) ? 
			data_copy.procedure_patient_education.split(',') : [];
		
		$scope.default.encounter.procedure_text              =  data_copy.procedure_text;
		$scope.default.encounter.procedure_xray_request      =  data_copy.procedure_xray_request;
		$scope.default.encounter.procedure_patient_education =  current_education;
		$(SELF.modal()).modal();
		
		setTimeout(function(){
			$('[ng-model="default.encounter.procedure_patient_education"]').trigger('change');
		}, 1);
	}
}