var action_physicalexam = function( $scope, $http, $filter ) {
	var SELF = this;
	this.modal = function(){
		return '#encounter-detail-modal-physicalexam';
	};	
	this.submit = function( event )
	{
		event.preventDefault();
		var Form = $(event.currentTarget);
		var Data = $scope.default.physicalexam ,
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
				if($scope.default.physicalexam.idx >= 0 )
				{	
					$scope.data.encounter_physicalexam[$scope.default.physicalexam.idx ] =  response.data.physicalexam ;
					 $( SELF.modal() ).modal('hide');
				}
				else
				{
					$scope.data.encounter_physicalexam.push( response.data.physicalexam  );
					SELF.open();
				}	
				
				
			}
			
		});
	};
	this.open = function()
	{
		$scope.default.physicalexam = {
			idx: -1,
			id: 0,
			title: '',
			content: ''
		};
		$(SELF.modal()).modal();
	};
	this.edit = function( idx )
	{			
		var exam = angular.copy($scope.data.encounter_physicalexam[idx]);
		$scope.default.physicalexam = {
			idx: idx,
			id: parseInt(exam.id),
			title: exam.title,
			content: exam.content
		};
		$(SELF.modal()).modal();
	}
	this.delete = function( idx )
	{
		var ele = $scope.data.encounter_physicalexam[idx];

		$http.get('/encounter/physicalexam/delete/'+ ele.id).then(function(response) {
	        Notify.response( response.data );
	        $scope.data.encounter_physicalexam.splice( idx, 1);
	        $( SELF.modal() ).modal('hide');
	    });	
	}
	this.import_template = function()
	{
		if($scope.default.physicalexam.title === '')
		{
			return false;
		}
		
		var found = $filter('filter')($scope.data.catalog_examinations, {
				title: $scope.default.physicalexam.title 
			}, true );

        if (found.length) 
        {
        	var c = $scope.default.physicalexam.content;

        	if($scope.default.physicalexam.content.length != 0)
        	{
        		c+= "\n";
        	}
			
			$scope.default.physicalexam.content = c + found[0].content;
			value = found[0]
        }
	}
}