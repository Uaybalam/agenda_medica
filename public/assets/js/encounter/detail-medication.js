var action_medication = function($scope, $http)
{
	var SELF = this;
	this.modal = function() { 
		return '#encounter-detail-modal-medication'
	};
	this.submit = function( event ){
		event.preventDefault();
		
		var Form = $('#form-medication');
		var Data = $scope.default.medication,
			Btn  = $('.submit', Form );
		$(Btn).attr( 'disabled', 'disabled' );
		
		$http({
		    method: 'POST',
		    url: $(Form).attr('action') + '/' + $scope.data.encounter.id ,
		    data:  $.param(  Data ),
		    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).then(function(response){
			
			Notify.response( response.data );
			$(Btn).removeAttr( 'disabled' );
			if( response.data.status )
			{			
				if($scope.default.medication.id > 0)
				{	
					$scope.data.encounter_medications[$scope.default.medication.idx] =  response.data.medication ;
					$(SELF.modal()).modal('hide');
				}	
				else
				{
					$scope.data.encounter_medications.push( response.data.medication  );
					SELF.clear();
				}
				
			}
			
		});
	};
	this.open = function(){
		SELF.clear();
		$(SELF.modal()).modal();
	};	
	this.clear = function(){
		$scope.default.medication = {
			idx: -1,
			id: 0,
			title: '',
		
			amount: '',
			refill: '',
			chronic: 'No',
			directions: ''
		};
	}
	this.edit = function( idx )
	{
		var med            = $scope.data.encounter_medications[idx];
		$scope.default.medication = {
			idx: idx,
			id: parseInt(med.id),
			title: med.title,
			amount: med.amount,
			directions: med.directions,
			chronic: med.chronic,
			refill: parseInt(med.refill)
		};
		
		$(SELF.modal()).modal();
	},
	this.delete = function( idx )
	{
		var ele = $scope.data.encounter_medications[ idx ];

		$(SELF.modal()).modal('hide');
		$http.get('/encounter/medication/delete/'+ ele.id).then(function(response) {
	        Notify.response( response.data );
	        $scope.data.encounter_medications.splice( idx, 1);
	        $(SELF.modal()).modal('hide');
	    });
	};
}