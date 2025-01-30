var action_vitals = function($scope, $http)
{
	var SELF = this;

	this.modal = function(){
		return '#encounter-detail-modal-vitals';
	};
	this.submit = function( event ){
		event.preventDefault();
		var Form = $(event.currentTarget);
		var Data = $.param(   $scope.default.encounter   ),
			Btn  = $('[type="submit"]', Form );
		$(Btn).attr( 'disabled', 'disabled' );
			
		$http({
		    method: 'POST',
		    url: $(Form).attr('action') + '/' + $scope.data.encounter.id ,
		    data:  Data ,
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
			
		$scope.default.encounter = copy_data('default.encounter', $scope.data.encounter);
		$scope.default.encounter.insurance_radio = $scope.default.encounter.insurance_title + '|' + $scope.default.encounter.insurance_number
		$(SELF.modal()).modal();
	};
	this.calc_bmi = function()
	{
		var weight = $scope.default.encounter.physical_weight || 0;
		var height = $scope.default.encounter.physical_height || 0;

		weight = parseFloat(weight, 2);	
		height = parseFloat(height, 2);

		if( weight > 0 && height > 0)
		{	
			var bmi = parseFloat(parseFloat( ( weight / (height * height) )).toFixed(2)) 
		}
		else
		{	
			var bmi = 0;
		}

		$scope.default.encounter.physical_bmi = bmi;

	};
	
	this.include_ins = function()
	{
		$scope.default.encounter.chief_complaint += "\n" + angular.copy($scope.data.questions_ins_inmigration);
		
	}
	
}