var action_illness = function($scope, $http)
{
	var SELF = this;

	this.modal = function(){
		return '#encounter-detail-modal-illness';
	};
	this.submit = function( event ){
		event.preventDefault();
		var Form = $(event.currentTarget);
		
		var Data ={
			present_illness_history: $scope.default.encounter.present_illness_history,
		};
		
		Btn  = $('.submit' );
		
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
		$scope.default.encounter.present_illness_history  = angular.copy( $scope.data.encounter.present_illness_history )
		$(SELF.modal()).modal();
			
	}
}