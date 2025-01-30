
angular
	.module('app_patient_board',[])
	.controller('ctrl_patient_board',function($scope, $http){
		
		$scope.appointment_id = 0;
		$scope.data           = {};
		$scope.default        = {};
		
		$scope.initialize = function( patient_id )
		{	
			$http.get('/patient/board/init/'+ patient_id ).then(function(response) {
		        
		        $scope.data = response.data;
		        
		        setTimeout(function()
		        {
		        	$('[ng-model="default.preventions.allergies"]').tagsinput({
						typeahead: {
							source: response.data.catalog_allergies ,
							afterSelect: function(val) { 
								this.$element.val(""); 
							},
						},
						tagClass: function(item) {	
							return 'label label-success';
						}
					});
		        }, 1);
		    });
		}
	});

