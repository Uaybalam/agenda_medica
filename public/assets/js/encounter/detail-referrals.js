var action_referrals =  function( $scope, $http ){
	var SELF = this;
	this.modal = function(){
		return '#encounter-detail-modal-referrals'
	};
	this.open = function()
	{	
		SELF.clear();
		$(SELF.modal()).modal();
	};
	this.submit= function( event )
	{	
		event.preventDefault();
		var Form = $(event.currentTarget);
		var Data = $scope.default.referrals,
			Btn  = $('.submit', Form );
		$(Btn).attr( 'disabled', 'disabled' );
		
		
		$http({
		    method: 'POST',
		    url: $(Form).attr('action') + '/' + $scope.data.encounter.id ,
		    data:  $.param(  Data ),
		    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).then(function(response){
			
			Notify.response( response.data );
			
			if( response.data.status )
			{	
				if($scope.default.referrals.id > 0 )
				{
					$scope.data.encounter_referrals[$scope.default.referrals.idx] =  response.data.referral ;
					$(SELF.modal()).modal('hide');
				}
				else
				{
					$scope.data.encounter_referrals.push( response.data.referral  );
					SELF.clear();
				}
				
				
			}
				
			$(Btn).removeAttr( 'disabled' );
		});
	};
	this.clear= function()
	{	
		$scope.default.referrals = {
			idx: -1,
			id: 0,
			speciality: '',
			service: '',
			reason: '',
			acuity:'Routine'
		};
	};
	this.edit= function( idx )
	{	
		var ref            = $scope.data.encounter_referrals[idx];
		$scope.default.referrals = {
			idx: idx,
			id: parseInt(ref.id),
			speciality: ref.speciality,
			service: ref.service,
			reason: ref.reason,
			acuity: ref.acuity
		};

		$(SELF.modal()).modal();
	};
	this.delete= function( idx )
	{
		var ele = $scope.data.encounter_referrals[idx];
		
		$http.get('/encounter/referrals/delete/'+ ele.id ).then(function(response) {
	        Notify.response( response.data );
	        $scope.data.encounter_referrals.splice( idx, 1);
	        $(SELF.modal()).modal('hide');
	    });
	};
	this.getStatus = function( status )
	{				
		return $scope.data.status_referrals[status];
	};
};