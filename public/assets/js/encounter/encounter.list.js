
var app = angular.module('app_encounters_list', ['angularUtils.directives.dirPagination']);

app.controller('ctrl_encounters_list',function($http, $scope){
	
	$scope.labelDiagnosis = function(diagString)
	{
		if($scope.filter.diagnosis!='')
		{	
			if(diagString.toLowerCase().search($scope.filter.diagnosis.toLowerCase()) >= 0 )
			{
				return 'label-success';
			}
		}
		return 'label-warning';
	}

	$scope.filter = {
		id:'',
		date:'',
		insurance:'',
		chief_complaint:'',
		diagnosis:''
	}

	//$scope.ngHelper      = new ngHelper();
	$scope.appPagination = new appPagination({
		$http:$http,
		$scope:$scope,
		url:'/encounter-list/data',
		filters: $scope.filter
	});
	
	$scope.appPagination.sort = {
		name:'date',
		type:'desc'
	};
	$scope.appPagination.getData(1);

});


$("[ng-model='filter.date']").datepicker( {
	format: "mm-yyyy",
	viewMode: "months", 
	minViewMode: "months",
	autoclose: true,
	closeText: 'Clear',
	showButtonPanel: true
});