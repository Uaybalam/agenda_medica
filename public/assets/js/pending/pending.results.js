var action_contact = function($scope, $http){
	
	var vm = this;

	vm.open = function( result )
	{	
		$scope.default.contact = {
			patient_id: result.patient_id,
			full_name: result.patient,
			reason: ''
		}
			
		$('#patient-modal-create-contact').modal();;
	};
	
	vm.submit = function()
	{
		var Data = $.param( $scope.default.contact );
		
		$('.submit').attr('disabled','disabled');
		
		$http
			.post('/patient/contact/insert', Data ,$httpConfig )
			.then( function(response){
				
				Notify.response( response.data );

				if(response.data.status)
				{	
					$('#patient-modal-create-contact').modal('hide');
					$('#pending-get_pending_contacts').html( response.data.pending );
				}

				$('.submit').removeAttr('disabled');

			});
	};
};

var action_results = function($scope, $http, $filter ){
	
	var vm       = this;
	
	var randomID = 0;
	
	var originalStatus = 0;
	vm.showUpload = function(){

		if(typeof($scope.default['result']) === 'undefined')
			return false;
		if(typeof($scope.default['result']['status']) === 'undefined')
			return false;
		
		//console.log("show upload", $scope.default['result']['status'] );

		if($scope.default['result']['status'] == 3 || $scope.default['result']['status']== 4)
			return true;

		return false;
		/*
		if($scope.action_results.originalStatus==3 || $scope.action_results.originalStatus==4)
			return true;

		return false;
		*/
	}
	
	vm.hideUpdate = function()
	{
		if(vm.originalStatus == 5 || vm.originalStatus == 6 || vm.originalStatus ==8 )
		{
			return true;
		}
		
		return false;
	};
	vm.remove = function(){ 
		
		
		var url = '/encounter/results/remove-results/' + $scope.default.result.id;
		$('.submit').attr('disabled', 'disabled');

		$http.get( url )
			.then(function( response ){
				Notify.response( response.data );
				
				if( response.data.status)
	        	{
	        		$scope.default.result.status    = response.data.result.status;
					$scope.default.result.file_name = response.data.result.file_name;
					$scope.appPagination.getData(1);
	        		vm.randomID  			= Math.floor(Math.random() * 99999 );	
					$('#pending-get_pending_results_waiting').html( response.data.pending.waiting );
					$('#pending-get_pending_results_check').html( response.data.pending.check );

	        	}
				$('.submit').removeAttr('disabled');
			});
	};
	vm.upload = function( file ){
		var F = $(file)[0].files[0];
		
		var Form = new FormData();
		
		file.value = ''; 
		$('.submit').attr('disabled', 'disabled');
        
        Form.append('file', F );

        $http.post('/encounter/results/upload/' + $scope.default.result.id , Form, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined}
        }).then(function(response ){
        	
        	Notify.response( response.data );

        	if( response.data.status)
        	{
        		var index = $scope.default.result.$index;
				
				$scope.default.result.status          = response.data.result.status;
				$scope.default.result.file_name       = response.data.result.file_name;
				$scope.default.result.recive_date     = response.data.result.recive_date;
				$scope.default.result.recive_nickname = response.data.result.recive_nickname

				$('#pending-get_pending_results_waiting').html( response.data.pending.waiting );
				$('#pending-get_pending_results_check').html( response.data.pending.check );
        		vm.randomID              = Math.floor(Math.random() * 99999 );	
        		$scope.appPagination.getData(1);
        	}	
        	$('.submit').removeAttr('disabled');
        });
	};
	vm.open = function( result , $index, $filter ){
		
		$scope.default.result = angular.copy( result );

		vm.originalStatus = angular.copy( result.status );
		//
		$scope.default.result.$index         = $index;
		$scope.default.result.status         = String($scope.default.result.status);
		$scope.default.result.refused        = 0;
		$scope.default.result.pin            = '';
		if(!$scope.default.result.title_document)
		{
			$scope.default.result.title_document =  $scope.default.result.title;
		}
		//doc_on_file_reason
		$scope.default.result.reason_contact = '';
		$scope.default.result.contact_patient = '0';
		console.log($scope.default.result);
		$('#encounter-request-modal-result').modal();
	};
	vm.confirm_refused = function()
	{
		$('.submit').attr('disabled','disabled');

		var Data = angular.copy( $scope.default.result ); 

		$http.post('/encounter/results/set-refused/' + $scope.default.result.id , $.param( Data ) , $httpConfig )
			.then(function( response ){

				Notify.response( response.data );
				
				if(response.data.status == 1) 
				{
					
					$scope.appPagination.getData(1);
					$('#pending-get_pending_results_waiting').html( response.data.pending.waiting );
					$('#pending-get_pending_results_check').html( response.data.pending.check );
					$('#encounter-request-modal-result').modal('hide');
				}

				$('.submit').removeAttr('disabled');
			})
	};
	vm.confirm_done = function()
	{
		$('.submit').attr('disabled','disabled');
		
		var Data = angular.copy( $scope.default.result ); 
		
		$http.post('/encounter/results/set-done/' + $scope.default.result.id , $.param( Data ) , $httpConfig )
			.then(function( response ){

				Notify.response( response.data );
				
				if(response.data.status == 1) 
				{	
					$('#pending-get_pending_results_waiting').html( response.data.pending.waiting );
					$('#pending-get_pending_results_check').html( response.data.pending.check );
					$('#encounter-request-modal-result').modal('hide');
					$scope.appPagination.getData(1);
				}
				
				$('.submit').removeAttr('disabled');
			})
	};
	vm.refreshStatus = function()
	{
		$('.submit').attr('disabled','disabled');
		var Data = angular.copy( $scope.default.result ); 

		$http.post('/pending/results/refreshStatus/' + $scope.default.result.id , $.param( Data ) , $httpConfig )
			.then(function( response ){
				Notify.response( response.data );

				if(response.data.status)
				{
					$scope.default.result = response.data.result;
					vm.originalStatus     = response.data.result.status;

					$('#pending-get_pending_results_waiting').html( response.data.pending.waiting );
					$('#pending-get_pending_results_check').html( response.data.pending.check );
					$('#encounter-request-modal-result').modal('hide');
					$scope.appPagination.getData(1);

					$('#encounter-request-modal-result').modal('hide');
				}
				$('.submit').removeAttr('disabled');
			})
	};
}

	
angular
	.module('app_pending_results', ['angularUtils.directives.dirPagination'] )
	.controller('ctrl_pending_results', function( $scope, $http , $filter){
		
		$scope.filter = {
			created_at: '',
			patient:'',
			type: '',
			status: '',
			title: ''
		};
		
		$scope.default 		 = {};
		
		$scope.data = {
			availible_types: [],
			status_result: []
		};
		$scope.startData = function( $status, $types ){

			$scope.data.status_result   = $status;
			$scope.data.availible_types = $types;

			$scope.filterStatus();
		}

		$scope.onChangeStatus = function( sta) 
		{
			if(sta.checked == 1 )
				sta.checked = 0;
			else
				sta.checked = 1;

			$scope.filterStatus();
		}
		
		$scope.nameStatus = function( staID ){
			var dataStatus = $filter('filter')( $scope.data.status_result , {
				id: staID
			}, true );

			if(dataStatus.length && dataStatus[0])
			{
				return dataStatus[0].name;
			}
			else
			{
				return '';
			}
		}

		$scope.filterStatus = function( ){

			var dataStatus = $filter('filter')( $scope.data.status_result , {
				checked: 1
			}, true );
			
			arrIds = [];

			for(i = 0; i < dataStatus.length; i++ )
			{
				arrIds.push(dataStatus[i].id);
			}
			
			$scope.filter['status'] = arrIds;

			if(arrIds.length >= 4)
			{ 
				setTimeout(() => { 
					$('button[data-toggle="dropdown"]').html(arrIds.length+" Seleccionadas");
				}, 10);
			}

			if($scope.data.status_result.length == arrIds.length)
			{
				setTimeout(() => { 
					$('button[data-toggle="dropdown"]').html("Todos");
				}, 10);
			}

			$scope.appPagination.getData(1);
		}
		//
		$scope.appPagination = new appPagination({
			$http:$http,
			$scope:$scope,
			url:'/pending/results/search',
			filters: $scope.filter,
			postQuery:function(response,$scope){
				$('[data-toggle="tooltip"]').tooltip();
			}
		});

		$scope.printYears = function( dob ){
			return get_age_patient( dob );
		}

		//sort default
		$scope.appPagination['sort']['type'] = 'desc';
		$scope.appPagination['sort']['name'] = 'created_at';

		//get pagination
		
		
		$scope.ngHelper       = new ngHelper();
		$scope.action_contact = new action_contact( $scope, $http );
		$scope.action_results = new action_results($scope, $http , $filter);
	});

