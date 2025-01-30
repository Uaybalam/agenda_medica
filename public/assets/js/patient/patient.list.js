var action_communicate = function( $scope, $http )
{

	$scope.default['communicate'] = {
		contact_id: 0,
		has_appointment : false,
		close_pending: false,
		notes: '',
		midday: 'AM',
		patient_id: 0,
		patient_full_name: '',
		patient_email: '',
		patient_phone: '',
		reason: '',
		visit_type: '1',
		code: '',
		minute: 0,
		date: moment().format('L'),
		type: 1
	};

	angular.extend(this, {
		open_history : function()
		{		
			if(typeof $scope.data === 'undefined')
			{
				$scope.data = {
					history_communications: [] 
				}	
			}

			$('#patient-communicate-modal-history-communication').modal();
				
			var patient_id = $scope.default.communicate.patient_id;

			$http.get('/patient/communication/history/' + patient_id ).success(function(response){ 
		    	$scope.data.history_communications = response.history_communications;
		    });
		},
		modal_pending : function()
		{
			if(typeof $scope.data === 'undefined')
			{
				$scope.data = {
					history_communications: [] 
				}	
			}
			
			$('#appointment-modal-current-date').modal();
			
			var date = $scope.default.communicate.date;

			$http.get('/appointment/records/?date=' + date + '&visit_types=1').success(function(response){ 
		    	$scope.data.appointments = response.appointments;
		    	$scope.data.visit_types  = response.visit_types;
		    });
		},
		open : function( patient )
		{
			
			var full_name = patient.names+' '
				+ patient.last_name;

			$scope.default.communicate.patient_full_name = full_name;
			$scope.default.communicate.patient_email     = patient.email;
			$scope.default.communicate.patient_phone     = patient.phone;
			$scope.default.communicate.patient_id        = patient.id;
			$scope.default.communicate.minute  			 = "0";
			$scope.default.communicate.hour  			 = "-1";

			$('#patient-communicate-modal-create-communication').modal();
			
		},
		submit : function(  )
		{

			var Data = $.param( $scope.default.communicate );
			$('.submit').attr('disabled','disabled');
			
			$http
				.post('/patient/communication/save', Data ,{
					headers : {
	                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
	                }
				})
				.then( function(response){

					Notify.response( response.data );

					if(response.data.status)
					{	
						$scope.default.communicate.notes = '';
						$('#patient-communicate-modal-create-communication').modal('hide');
					}
					
					$('.submit').removeAttr('disabled');

				});
		}
	});
}

var app = angular.module('app_patients_list', ['angularUtils.directives.dirPagination']);

app.controller('ctrl_patients_list',function( $http, $scope, $filter){

	$scope.default 	= {
		patientList: [],
		patient: {
			name: '',
			middle_name: '',
			last_name: '',
			gender: "Male",
			phone: '',
			phone_memo: '',
			date_of_birth: '',
			how_found_us: '',
			interpreter_needed: false,
			advanced_directive_offered: false,
			advanced_directive_taken: false,
		}
	};

	$scope.filter = {
		id:'',
		names:'',
		last_name:'',
		date_of_birth:'',
		gender:'',
		insurance:''
	}

	var currentDate = new Date().getTime();

	$scope.appPagination = new appPagination({
		$http:$http,
		$scope:$scope,
		url:'/patient/search',
		filters: $scope.filter,
		postQuery:function(response,$scope){
			
			var closestAppt, idAppt, dateAppt;

			for(var i=0; i< response.result_data.length; i++)
			{	
				
				closestAppt = response.result_data[i].appointment.date_appointment;
				idAppt 	    = response.result_data[i].appointment.id;

				$scope.appPagination.result_data[i].age          = get_age_patient(response.result_data[i].date_of_birth);	
				$scope.appPagination.result_data[i].focusPatient = false;
				
				if(closestAppt)
				{
					$scope.appPagination.result_data[i].closest_appointment_date = moment( closestAppt, "YYYY-MM-DD hh:mm:ss").format('lll');
					$scope.appPagination.result_data[i].closest_appointment_id   = idAppt ;
					
					dateAppt = new Date( closestAppt ).getTime();
					
					if( dateAppt <= currentDate)
					{
						$scope.appPagination.result_data[i].closest_appointment_class = 'label-warning'
					}
					else
					{
						$scope.appPagination.result_data[i].closest_appointment_class = 'label-info';
					}
				}
				
			}

			$scope.focusPatient("top");
		}
	});
	
	$scope.appPagination.getData(1);
	
	setTimeout(function(){
			
		var found                 = $('#settings_how_found_us').val() || '';
		var settings_how_found_us = (found!='') ? found.split(',') : [];

		$('[ng-model="default.patient.how_found_us"]').typeahead({
			source: settings_how_found_us,
			minLength: 0,
			autoSelect: true,
			items: 50
		}).on("click", function() {
	        var ev = $.Event("keydown");
	        ev.keyCode = ev.which = 40;
	        $(this).trigger(ev);
	        return true;
	 	});
		
		$(".datepicker-range-years").datepicker( {
		    format: " yyyy",
		    viewMode: "years", 
		    minViewMode: "years",
		    autoclose:true,
		    todayHighlight:true,
		    toggleActive: true,
		});	

	 	$('[data-toggle="tooltip"]').tooltip();
	}, 1000 );

	$scope.open_modal = function()
	{
		$('#patient-modal-create').modal();
	}

	$scope.similarPatients = function(){
		var Data = $.param( $scope.default.patient );
		
		$http.get('/patient/similarPatients?'+ Data ).then(function(response){
			$scope.default.patientList = response.data.patients;

			setTimeout(function(){
				if($scope.default.patientList.length > 0 )
					$('[data-toggle="tooltip"]').tooltip();
			}, 10 );
		});
	}

	$scope.submit_patient = function( event ){
		
		event.preventDefault();

		var Data = $.param( $scope.default.patient );
		
		$http
			.post('/patient/save', Data ,{headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}
		})
		.then( function(response){
			if(!response.data.status)
			{	
				Notify.error(response.data.message);
			}
			else
			{	
				window.location.href = response.data.edit_patient;
			}	
		});
	}

	$scope.add_patient_not_found = function( data )
	{

		$scope.default.patient.name      = data.names; 
		$scope.default.patient.last_name = data.last_name;
		
		if(data.gender)
		{
			$scope.default.patient.gender = data.gender;
		}
		if(data.date_of_birth)
		{
			$scope.default.patient.date_of_birth = moment(data.date_of_birth, 'MM/DD/YYYY').format("MM/DD/YYYY");
			
		}
		$('#patient-modal-create').modal();
	}

	$scope.focusPatient = function( type )
	{
		if(!$scope.appPagination.result_data.length)
		{
			return false
		}

		if( type === 'top' )
		{
			$scope.appPagination.result_data[0].focusPatient = true;
		}
		else
		{
			length = $scope.appPagination.result_data.length;

			for( var i=0; i < length; i++)
			{
				if( $scope.appPagination.result_data[i].focusPatient )
				{
					$scope.appPagination.result_data[i].focusPatient  = false;

					if(type === 'up')
					{
						newPosition = ( (i - 1) >= 0 ) ? ( i - 1 ) : i;
					}
					else
					{
						newPosition = ( (i + 1) < length ) ? ( i + 1 ): i;
					}

					$scope.appPagination.result_data[newPosition].focusPatient  = true;
					break;
				}
			}
		}
	}

	$scope.keyup = function(keyEvent) {
		console.log("keyEvent.shiftKey", keyEvent.shiftKey);
		if(  keyEvent.keyCode  == 38 )
		{
			$scope.focusPatient('up')
		}
		else if( keyEvent.keyCode == 40)
		{
			$scope.focusPatient('down')
		}
		else if(keyEvent.keyCode === 13)
		{
			patient  = $filter('filter')( $scope.appPagination.result_data , {
				focusPatient: true
			}, true );
			if(patient.length)
			{
				$scope.redirectDemographics( patient[0] );
				
			}
		}
		else if(keyEvent.keyCode === 37 )
		{
			currentPage = $scope.appPagination.currentPage;
			if( ( currentPage - 1 ) > 0 )
			{
				$scope.appPagination.getData( currentPage - 1 );
			}
		}
		else if(keyEvent.keyCode === 39 )
		{	
			currentPage = $scope.appPagination.currentPage;
			numPages    = Math.ceil( ($scope.appPagination.total_count / $scope.appPagination.itemsPerPage) );
			if( ( currentPage + 1 ) <= numPages )
			{
				$scope.appPagination.getData( currentPage + 1 );
			}
		}
    };

    $scope.choseClick = function( ptClicked ){
    	patient  = $filter('filter')( $scope.appPagination.result_data , {
			focusPatient: true
		}, true );

		if(patient.length)
		{
			patient[0].focusPatient = false;
		}

		ptClicked.focusPatient = true;
    }

    $scope.redirectDemographics = function( patient ){
    	if(typeof patient == 'undefined')
    	{
    		return false;
    	}

    	window.location = '/patient/detail/' + patient.id;
    }
	
	$scope.ngHelper = new ngHelper($scope);
	

	$scope.action_communicate = new action_communicate( $scope, $http );
	
	$scope.data_filter_patients = function(){
		console.log( $scope.appPagination.sort);
		let data = {
			filters: $scope.filter,
			sort: $scope.appPagination.sort
		}
		return $.param( data );
	};

});