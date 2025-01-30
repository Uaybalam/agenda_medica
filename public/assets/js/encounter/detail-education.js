var action_education = function($scope, $http)
{
	var SELF 	= this;
	this.data 	= [];

	this.modal = function(){
		return '#encounter-detail-modal-education';
	};

	this.submit = function( event ){
		
		event.preventDefault();

		var Form = $(event.currentTarget);
		
		var Data ={
			procedure_patient_education: SELF.data.join(",")
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
		if($scope.data.encounter.procedure_patient_education!='')
		{
			SELF.data = $scope.data.encounter.procedure_patient_education.split(",");
		}
		else
		{
			SELF.data = [];
		}
		
		console.log(SELF.data);
		$(SELF.modal()).modal();
	};
	this.edit = function(){

	};
	this.toggle = function (item, list) {
        var idx = list.indexOf(item);
        if (idx > -1) {
          list.splice(idx, 1);
        }
        else {
          list.push(item);
        }
     };
}