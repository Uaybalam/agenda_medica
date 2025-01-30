
var action_check_document = function($scope, $http){
	
	var self = this;
	
	self.urlSubmit   = '';
	self.currentType = '';
	self.types = {
		chart:{
			urlSubmit: '/patient/related-files/#ID#/checkDone',
			urlImage: '/patient/related-files/open/#ID#/preview/?random=#RANDOM#',
			urlOpenImage:'/patient/related-files/open/#ID#?random=#RANDOM#'
		},
		results:{
			urlSubmit: '/encounter/results/#ID#/checkDone',
			urlImage: '/encounter/results/#ID#/open-preview/?random=#RANDOM#',
			urlOpenImage:'/encounter/results/open/#ID#?random=#RANDOM#'
		},
	}

	self.open = function( element , type ){
		
		random = Math.floor(Math.random() * 99999 );
		
		$scope.default['document'] = angular.copy(element);
		$scope.default['document']['contact_patient'] = "0";
		$scope.default['document']['urlOpenImage'] = self.types[type].urlOpenImage.replace('#ID#',element.id).replace('#RANDOM#',random );
		$scope.default['document']['urlImage'] = self.types[type].urlImage.replace('#ID#',element.id).replace('#RANDOM#',random );
				
		self.urlSubmit   = self.types[type].urlSubmit.replace('#ID#', element.id );
		self.currentType = type;

		$('#pending-modal-check-documents').modal();
	}

	self.submit = function( $event ){
		
		$event.preventDefault();
		$('.submit').attr('disabled','disabled');

		var Data = angular.copy( $scope.default.document ); 

		$http.post( self.urlSubmit , $.param( Data ) , $httpConfig )
			.then(function( response ){

				Notify.response( response.data );
				
				if(response.data.status == 1) 
				{	
					if(typeof response.data.pending_check_docs !== 'undefined' )
					{
						console.log("Pending Check Docs 1 ", response.data.pending_check_docs);
						$('#pending-get_pending_results_check').html( response.data.pending_check_docs );	
					}
					else
					{
						console.log("Pending Check Docs 2", typeof response.data.pending_check_docs);
					}
					
					$scope.pagination[self.currentType].getData(1);
					$('#pending-modal-check-documents').modal('hide');
				}
				
				$('.submit').removeAttr('disabled');
			})
	}
}

angular
	.module('app_check_requests', ['angularUtils.directives.dirPagination'] )
	.controller('ctrl_check_requests', function( $scope, $http , $filter){
		
		$scope.result_filter = {
			created_at: '',
			patient:'',
			type_str: '',
			status: [4],
			title: ''
		};

		$scope.chart_filter = {
			created_at: '',
			patient:'',
			type_str: '',
			title: '',
			document_for_done: 1
		};
		
		$scope.default = {};
		
		$scope.startData = function( $status, $types ){
			
			//$scope.data.status_result   = $status;
			//$scope.data.availible_types = $types;
			
		}

		$scope.pagination = {
			results: {},
			chart: {},
			referrals: {}
		};

		var totalResults =  totalChart = 0;

		$scope.pagination['results'] = new appPagination({
			$http:$http,
			$scope:$scope,
			url:'/pending/documents/from-results/search',
			filters: $scope.result_filter,
			postQuery:function(response,$scope){
				totalResults = response.result_data.length
				$('[data-toggle="tooltip"]').tooltip();
			}
		});
		
		$scope.pagination['chart'] = new appPagination({
			$http:$http,
			$scope:$scope,
			url:'/pending/documents/from-chart/search',
			filters: $scope.chart_filter,
			postQuery:function(response,$scope){
				totalChart = response.result_data.length
				$('[data-toggle="tooltip"]').tooltip();
			}
		});
		
		setTimeout(function(){
			//enableFromChart
			if(totalResults === 0 && totalChart> 0) 
			{
				$('.nav-tabs a[href="#tab-chart"]').tab('show')
			}		
		}, 1000 )

		$scope.pagination.results.sortData('created_at', 'desc');
		$scope.pagination.chart.sortData('created_at', 'desc');

		//get pagination
		$scope.pagination['results'].getData(1);
		$scope.pagination['chart'].getData(1);

		$scope.ngHelper              = new ngHelper();
		$scope.action_check_document = new action_check_document($scope, $http );
	});

