var action_diagnosis = function($scope, $http)
{
	var SELF = this;
	this.modal = function(){
		return '#encounter-detail-modal-diagnosis';
	};
	this.open = function(){
		SELF.clear();
		$(SELF.modal()).modal();
	};
	this.submit = function( event ){
		event.preventDefault();
		var Form = $(event.currentTarget);
		
		var Data = $scope.default.diagnosis,
			Btn  = $('.submit', Form );
		$(Btn).attr( 'disabled', 'disabled' );
		
		$http({
		    method: 'POST',
		    url: $(Form).attr('action') + '/' + $scope.data.encounter.id ,
		    data:  $.param(Data) ,
		    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).then(function(response){
			
			Notify.response( response.data );
			if(response.data.status === 1 )
			{	
				if($scope.default.diagnosis.idx >= 0 )
				{		
					$scope.data.encounter_diagnosis[$scope.default.diagnosis.idx] = response.data.diagnosis;
					 $( SELF.modal() ).modal('hide');
				}
				else
				{
					$scope.data.encounter_diagnosis.push(  response.data.diagnosis );
					SELF.open();
				}	
				
				
				//$(SELF.modal()).modal('hide');
			}

			$(Btn).removeAttr( 'disabled' );
		});
	};
	this.edit = function( idx ){

		var diagnosis  = $scope.data.encounter_diagnosis[idx];
		
		$scope.default.diagnosis = {
			idx: idx,
			id: parseInt(diagnosis.id),
			comment: diagnosis.comment,
			chronic:  diagnosis.chronic
		};
		$(SELF.modal()).modal();
	};
	this.clear = function(){
		$scope.default.diagnosis = {
			idx: -1,
			id: 0,
			comment: '',
			chronic: 0
		};
	};
	this.delete = function(){
		var idx       = $scope.default.diagnosis.idx;
		var diagnosis = $scope.data.encounter_diagnosis[idx];
		
		$http.get('/diagnosis/delete/'+ diagnosis.id).then(function(response) {
	        Notify.response( response.data );
	        $scope.data.encounter_diagnosis.splice( idx , 1);
	        $(SELF.modal()).modal('hide');
	    });
	};
};