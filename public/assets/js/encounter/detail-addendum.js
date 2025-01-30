var action_addendum = function($scope, $http)
{
	var SELF = this;
	this.modal = function(){
		return '#encounter-detail-modal-addendum';
	};
	this.open = function(){
		$scope.default.addendum = {
			notes: '',
			password: ''
		};
		$(SELF.modal()).modal();
	};
	this.submit = function( event ){
		
		event.preventDefault();
		var Form = $(event.currentTarget);
		
		var Data = $scope.default.addendum,
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
				$(SELF.modal()).modal('hide');
				
				$scope.data.encounter_addendums.push(  response.data.addendum );
			}
			
			$scope.default.addendum.password = '';

			$(Btn).removeAttr( 'disabled' );
		});
	};
};