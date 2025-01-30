
var action_childphysical= function($scope, $http)
{	
	
	var SELF = this;

	this.modal = function(){
		return '#encounter-detail-modal-childphysical';
	};

	this.open = function(){

		$scope.default.encounter_child = angular.copy($scope.data.encounter_child);

		$scope.default.encounter_child.development_options	= ($scope.data.encounter_child.development_options != null) ? 
			$scope.data.encounter_child.development_options.split(',') : [];
		
		$scope.default.encounter_child.development_plan	= ($scope.data.encounter_child.development_plan != null) ? 
			$scope.data.encounter_child.development_plan.split(',') : [];

		$scope.default.encounter_child.educations	= ($scope.data.encounter_child.educations != null) ? 
			$scope.data.encounter_child.educations.split(',') : [];
			
		$(SELF.modal()).modal();

		setTimeout(function(){
			
			$('[ng-model="default.encounter_child.development_options"]').trigger('change');

			$('[ng-model="default.encounter_child.development_plan"]').trigger('change');
			
			$('[ng-model="default.encounter_child.ethnic_code"]').trigger('change');

			$('[ng-model="default.encounter_child.educations"]').trigger('change');

		}, 1);
	};	
	
	this.active_radio = function( nameModel , val)
	{
		if( $scope.default.encounter_child[nameModel] === val )
		{
			return 'active';
		}
		return '';
	}

	this.submit = function( event )
	{	
		event.preventDefault();
		var Form = $(event.currentTarget);
		var Data = $.param(   $scope.default.encounter_child   )
			
		$('.submit').attr( 'disabled', 'disabled' );
			
		$http({
		    method: 'POST',
		    url: $(Form).attr('action') + '/' + $scope.data.encounter.id ,
		    data:  Data ,
		    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).then(function(response){
			
			Notify.response( response.data );
			if(response.data.status === 1 )
			{
				$scope.data.encounter_child =  response.data.encounter_child;
				$(SELF.modal()).modal('hide');
			}

			$('.submit').removeAttr( 'disabled' );
		});
	}
}