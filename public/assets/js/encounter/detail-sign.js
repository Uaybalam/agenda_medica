var action_sign = function( $scope, $http ){

	var SELF = this;

	this.modal = '';
	
	this.open = function( name_modal )
	{
		$scope.default.sign = {
			pin: '',
		};

		this.modal = name_modal;
		$(name_modal).modal();
	};

	this.submit = function( event )
	{
		event.preventDefault();
		
		var Form = $( event.currentTarget );
		var Data = $scope.default.sign,
			Btn  = $('.submit', Form );
		$(Btn).attr( 'disabled', 'disabled' );
		
		$http({
		    method: 'POST',
		    url: $(Form).attr('action') + '/' + $scope.data.encounter.id ,
		    data:  $.param(  Data ),
		    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).then(function(response){
			
			if( response.data.status )
			{	
				window.location = response.data.redirect;
			}
			else
			{	
				Notify.error( response.data.message );
				$(Btn).removeAttr( 'disabled' );
			}
			
			
		});
	};

}