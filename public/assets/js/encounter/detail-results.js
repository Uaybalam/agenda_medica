var action_results= function($scope, $http)
{	
	var SELF = this;
	this.modal = function() { 
		return '#encounter-detail-modal-results'
	};
	this.submit = function( event ){
		
		event.preventDefault();
		
		var Form = $( event.currentTarget );
		var Data = $scope.default.results,
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
				if($scope.default.results.id > 0)
				{
					$scope.data.encounter_results[$scope.default.results.idx] =  response.data.result ;
					$(SELF.modal()).modal('hide');
				}	
				else
				{
					$scope.data.encounter_results.push( response.data.result  );
					SELF.open();
				}
			}
			
		});
	};
	this.open = function(){
		$scope.default.results = {
			idx: -1,
			id: 0,
			title: '',
			comments: '',
			type_result: ''
		};
		$(SELF.modal()).modal();
	};	
	this.edit = function( idx )
	{
		var result = $scope.data.encounter_results[idx];
		
		$scope.default.results = {
			idx: idx,
			id: parseInt(result.id),
			title: result.title,
			comments: result.comments,
			type_result: result.type_result
		};
		
		$(SELF.modal()).modal();
	};
	this.delete = function( idx )
	{		
			
		var result = $scope.data.encounter_results[idx];

		$http.get('/encounter/results/delete/'+ result.id).then(function(response) {
			$scope.data.encounter_results.splice( idx, 1);
	        Notify.response( response.data );
	        
	        $(SELF.modal()).modal('hide');
	    });
	};

}