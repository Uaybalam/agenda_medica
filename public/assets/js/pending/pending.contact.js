var action_communicate = function( $scope, $http )
{
	var vm = this;
	
	$scope.default.status_contact = 0;
	
	$scope.default.communicate = {
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
		notes: '',
		minute: 0,
		date: moment().format('L'),
		notes_appointment: ''
	};	
	
	vm.open_history = function()
	{
		
		$('#patient-communicate-modal-history-communication').modal();
		
		var patient_id = $scope.default.communicate.patient_id;

		$http.get('/patient/communication/history/' + patient_id ).success(function(response){ 
	    	$scope.data.history_communications = response.history_communications;
	    });
	}

	vm.modal_pending = function( )
	{		
		$('#appointment-modal-current-date').modal();
		
		var date = $scope.default.communicate.date;

		$http.get('/appointment/records/?date=' + date + '&visit_types=1').success(function(response){ 
	    	$scope.data.appointments = response.appointments;
	    	$scope.data.visit_types  = response.visit_types;
	    });
	}

	vm.open = function( contact )
	{	
		
		$scope.default.communicate.contact_id        = parseInt(contact.id);
		$scope.default.communicate.patient_full_name = contact.patient_full_name;
		$scope.default.communicate.patient_email     = contact.patient_email;
		$scope.default.communicate.patient_phone     = contact.patient_phone;
		$scope.default.communicate.patient_id        = parseInt(contact.patient_id);
		$scope.default.communicate.notes_appointment = '';
		$scope.default.status_contact                = contact.status;
		$scope.default.communicate.reason            = contact.reason;
		$scope.default.communicate.patient_insurance = contact.patient_insurance;
		$scope.default.communicate.patient_gender    = contact.patient_gender;
		$('#patient-communicate-modal-create-communication').modal();
	};

	vm.submit = function()
	{	
		var Data = angular.copy( $scope.default.communicate );
		Data['redirect'] = true;
		Data = $.param( Data );
		
		$('.submit').attr('disabled','disabled');
		$http
			.post('/patient/communication/save', Data ,{
				headers : {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
			})
			.then( function(response){
				
				if(response.data.status)
				{	
					window.location = '/pending/contact';
				}
				else
				{	
					Notify.error( response.data.message );
					$('.submit').removeAttr('disabled');
				}
				
			});
	}

	vm.withoutAnswer = function()
	{
		var contactId = $scope.default.communicate.contact_id;
		var a = document.createElement("a");
	    a.target = "_blank";
	    a.href = "/pending/contact/messagenocontact/" + contactId;
	    a.click();
	};
};

angular
	.module('app_pending_contact', ['angularUtils.directives.dirPagination'] )
	.controller('ctrl_pending_contact', function( $scope, $http ){
		
		$scope.default 		 = {};

		$scope.data = {
			history_communications: []
		};

		$scope.paginate = {
			contact:null
		};

		$scope.filter = {
			created_at: '',
			created_by: '',
			patient: '',
			reason: '',
			status:'0'
		}


		$scope.initialize = function( typesOfCommunications )
		{	
			$scope.typesOfCommunications = typesOfCommunications;
		};
		
		var currentDate = new Date().getTime();

		$scope.paginate['contact'] = new appPagination({
			$http:$http,
			$scope:$scope,
			url:'/pending/contact/search',
			filters: $scope.filter,
			postQuery:function(response , $scope ){
				var closestAppt, idAppt, dateAppt;
				
				for(var i=0; i< response.result_data.length; i++)
				{
					
					closestAppt = response.result_data[i].appointment.date_appointment;
					idAppt 	    = response.result_data[i].appointment.id;

					if(closestAppt)
					{
						$scope.paginate.contact.result_data[i]['closest_appointment_date'] = moment( closestAppt, "YYYY-MM-DD hh:mm:ss").format('lll');

						$scope.paginate.contact.result_data[i]['closest_appointment_id']   = idAppt ;
						
						dateAppt = new Date( closestAppt ).getTime();
						
						if( dateAppt <= currentDate)
						{
							$scope.paginate.contact.result_data[i]['closest_appointment_class'] = 'label-warning'
						}
						else
						{
							$scope.paginate.contact.result_data[i]['closest_appointment_class'] = 'label-info';
						}
					}
				}
				
				$('[data-toggle="tooltip"]').tooltip();
			}
		});
		
		$scope.paginate['contact'].sortData('created_at', 'asc');
		
		$scope.paginate['contact'].getData(1);

		$scope.ngHelper           = new ngHelper($scope);
		$scope.action_communicate = new action_communicate($scope, $http);
	});




