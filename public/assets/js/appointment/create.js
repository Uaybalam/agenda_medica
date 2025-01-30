

var ngApp = angular.module('app_appointment', []);
ngApp.controller('ctrl_appointment', ( $scope, $http, $filter ) => {
	
	$scope.default 	= {
		appt: {}, 
		demographics: {},
		patientList:[],
		time: {},
	};

	$scope.lapse_time = date => {
		return moment( date, "YYYY-MM-DD hh:mm:ss").fromNow()
	}


	$scope.initialize = () => {
		
		var cDate = new Date();
		var year  = cDate.getFullYear();
		var month = cDate.getMonth() + 1;
		month = ( month < 10 ) ? "0" + month : month;
		var day = cDate.getDate();
		day = (day < 10 ) ? "0" + day : day;

		$scope.currentDate = year + "" +month + "" + day;

		moment.locale("en");

		$scope.default.appt = {
			type_appointment: 0,
			date: moment().format('L'),
			hour: "", 
			patient_id: "0",
			code: "",
			visit_type: "Nueva",
			notes: "",
			insurance_type:""
		}

		$scope.default.patient = {
			name: '',
			middle_name: '',
			last_name: '',
			gender: 'Male',
			phone: '',
			date_of_birth: '',
			insurance_primary_plan_name: '',
			insurance_primary_identify: ''
		}

		$scope.change_patient = () =>
		{
			//get my insurance? $scope
			var patient_id = $scope.default.appt.patient_id || 0;
			if(patient_id)
			{	
				$http.get('/patient/' + patient_id+'/information').then(response => {
					$scope.default.demographics = response.data;
				});
			}
			else
			{
				$scope.default.demographics = {};
			}
		}
		
		$scope.include_new_patient = (data) =>
		{
			$scope.default.demographics = {
				patient: data
			}

			var template = '<option value="'+data.id+'" selected="true">';
			template+= data.name+' '+data.middle_name+' '+data.last_name;
			template+= ', <span class="text-opacity">'+data.date_of_birth+'</span> ';
			if(data.appointments_count > 0 )
			{	
				template+= 'Last appointment <span class="text-opacity">'+data.last_appointment_at+'</span>'
			}
			template+='<option>'

			setTimeout(() => {
				$('#patient_id')
					.append( template )
					.trigger('change');
			}, 1)
		}

		$scope.action_appt.change_date();

		setTimeout(() => {

			$('[ng-model="default.appt.hour"]').select2({
				placeholder: "Selecciona una hora",
				"language": {
			       "noResults": () => {
			           return "Sin resultados";
			       }
			   },
  			});

			$('[ng-model="default.appt.minute"]').select2({
				placeholder: "Select a minute",
				"language": {
			       "noResults": () => {
			           return "Sin resultados";
			       }
			   },
  			});

			$('[ng-model="default.appt.patient_id"]').trigger('change');

			$("#patient_id").select2({
				ajax: {
					url: "/patient/filter",
					dataType: 'json',
					delay: 200,
					data: function (params) {
						return {
							q: params.term, // search term
							page: params.page
						};
					},
					processResults: function (data, params) {
						params.page = params.page || 1;
						return {
							results: data.items,
							pagination: {
								more: (params.page * 30) < data.total_count
							}
						};
					},
					cache: true
				},
				escapeMarkup: function (markup) { 
					return markup; 
				},
				minimumInputLength: 1,
				templateResult: function( data ){
					if(typeof data.name === 'undefined')
					{
						return '';
					}
					else
					{
						var template = '';
						template+= data.name+' '+data.middle_name+' '+data.last_name+': '+data.date_of_birth;
						//template+= ', <span class="text-opacity">'+data.date_of_birth+'</span> ';
						/*
						if(data.appointments_count > 0 )
						{
							template+= 'Last appointment <span class="text-opacity">'+data.last_appointment_at+'</span>'
						}
						*/
						return template;
					}
				},
				templateSelection: function( data ){

					if( data.text != '' )
					{	
						return data.text;
					}	
					else{
						var template = '';
						template+= data.name+' '+data.middle_name+' '+data.last_name+': '+data.date_of_birth;
						//template+= ', <span class="text-opacity">'+data.date_of_birth+'</span> ';
						/*
						if(data.appointments_count > 0 )
						{
							template+= 'Last appointment <span class="text-opacity">'+data.last_appointment_at+'</span>'
						}
						*/
						return template;
					}	
				}
			});
			
			$('[ng-model="default.appt.insurance_type"]').typeahead({
				source: $scope.data_insurance_types,
				minLength: 0,
				autoSelect: true,
				items: 8
			}).on("click", () =>  {
				var ev = $.Event("keydown");
				ev.keyCode = ev.which = 40;
				$(this).trigger(ev);
				return true;
		 	});

			var found                 = $('#settings_how_found_us').val() || '';
			var settings_how_found_us = (found!='') ? found.split(',') : [];
			
			$('[ng-model="default.patient.how_found_us"]').typeahead({
				source: settings_how_found_us,
				minLength: 0,
				autoSelect: true,
				items: 50
			}).on("click", () =>  {
		        var ev = $.Event("keydown");
		        ev.keyCode = ev.which = 40;
		        $(this).trigger(ev);
		        return true;
		 	});
			
		}, 1);

		moment.locale("es");
	}

	$scope.open_modal = () => 
	{
		$('#patient-modal-create-basic').modal();
	}
	
	$scope.similarPatients = () => {
		var Data = $.param( $scope.default.patient );
		
		$http.get('/patient/similarPatients?'+ Data ).then(response => {
			$scope.default.patientList = response.data.patients;
			setTimeout(() => {
				if($scope.default.patientList.length > 0 )
					$('[data-toggle="tooltip"]').tooltip();
			}, 10 );
		});
	}

	$scope.submit_patient = ( event ) => {
		
		event.preventDefault();

		var Data = $.param( $scope.default.patient );
		
		$('.submit').attr('submit','submit');

		$http.post('/patient/save-from-appointment', Data ,$httpConfig)
			.then( response => {
				
				Notify.response( response.data );

				if(response.data.status)
				{
					$scope.default.appt.patient_id  = response.data.patient.id;
					
					$scope.include_new_patient( response.data.patient );

					$scope.default.patient = {
						name: '',
						last_name: '',
						phone: '',
						date_of_birth: '',
						how_found_us:''
					}

					$('#patient-modal-create-basic').modal('hide');
				
				}

				$('.submit').removeAttr('submit');
			});
	}
	
	$scope.filterStatus = (data) =>
	{
		
		if( typeof data === 'undefined')
		{
			return false;
		}
		else if(data.status== '8' || data.status=='-1')
		{
			return false;
		}

		return data;
	}


	$scope.action_appt = {
		setBackDay: () => 
		{	
			$scope.default.appt.date = moment($scope.default.appt.date , "MM/DD/YYYY").add(-1, 'days').format("MM/DD/YYYY");
			$scope.action_appt.change_date();
			$scope.action_appt.change_type_appt(0);
		},
		setNextDay: () => 
		{	
			$scope.default.appt.date = moment($scope.default.appt.date , "MM/DD/YYYY").add( 1, 'days').format("MM/DD/YYYY");
			$scope.action_appt.change_date();
			$scope.action_appt.change_type_appt(0);
		},
		setToday: () => 
		{
			$scope.default.appt.date = moment().format("MM/DD/YYYY");
			$scope.action_appt.change_date();
			$scope.action_appt.change_type_appt(0);
		},
		change_custom_time: () => {console.log($scope.default.appt);

			var appt = angular.copy($scope.default.appt);

			var appointments = $filter('filter')( $scope.data.appointments , {
				status: '-1', choosen_time: true
			}, true );
			
			if( appointments.length )
			{
				appointments[0].choosen_time = false;	
			}

			var customTime = (appt.hour<10) ? '0' + appt.hour : String(appt.hour);
			customTime+=':'+appt.minute;
			customTime+=' '+appt.midday;

			var appointmentFound = $filter('filter')( $scope.data.appointments , {
					status: '-1', time:customTime
			}, true );

			if( appointmentFound.length )
			{
				appointmentFound[0].choosen_time = true;	
			}
		},
		change_time: ( item ) => {

			var appointments = $filter('filter')( $scope.data.appointments , {
					status: '-1', choosen_time: true
				}, true );
			
			if( appointments.length )
			{
				appointments[0].choosen_time = false;	
			}

			item.choosen_time = true;

			var timeSplit = item.time.split(":").join(" ").split(" ")

			$scope.action_appt.change_type_appt(0);

			$scope.default.appt.hour = item.time; 

			setTimeout(() => {
				$('[ng-model="default.appt.hour"],[ng-model="default.appt.minute"]').trigger("change");
			}, 1);
		},
		change_date: () => 
		{
			var date = $scope.default.appt.date || '';

			if(date==='')
			{	
				$scope.data.appointments = [];
			}
			else
			{
				$http.get('/appointment/records/?date='+ date ).then( response => {console.log(response.data);
					
					$scope.data = response.data;
					
					var hourTemporal, minuteTemporal, midday;
					var choseDate   = $scope.default.appt.date;
					var splitDate   = choseDate.split("/")
					var intDate     = splitDate[2] + "" + splitDate[0] + "" + splitDate[1];
					 
					if($scope.currentDate <= intDate)
					{ 
						var currentHour = parseInt($scope.data.opened.split(":")[0]);
						var currentMin  = parseInt($scope.data.opened.split(":")[1]);;

						if($scope.currentDate == intDate)
						{
							currentHour = moment().add($scope.data.time,'min').format('H'); 
							currentMin  = (parseInt(moment().format('mm')) + Math.abs((parseInt(moment().format('mm'))%$scope.data.time) - $scope.data.time));
						}

						if(currentMin >= 60)
						{
							currentMin = currentMin - 60;
							currentHour++;
						}

						var begin = moment(currentHour+':'+currentMin,'H:mm');
						var end   = moment($scope.data.closed,'H:mm'); 
						var allH  = [];

						while(begin.isBefore(end))
						{ 
							let time    = begin.format('hh:mm A');
							let timeTmp = begin.format('HHmm');
							
							allH.push(time);
					
							appointment  = $filter('filter')( $scope.data.appointments , {
								time: time
							}, true );

							if(! appointment.length )
							{
								$scope.data.appointments.push({
									time:time,
									status:'-1',
									full_date_sort: parseInt(intDate + "" + timeTmp ),
									availableTime:true
								});
							}

							begin.add("minutes",$scope.data.time);
						}

						$scope.default.time = {hours: allH}; 
						$scope.action_appt.change_custom_time();
					}
				});
			}
		},
		click_type_appointment:() => {
			
			var appt = angular.copy($scope.default.appt);
			
			var customTime = (appt.hour<10) ? '0' + appt.hour : String(appt.hour);
			customTime+=':'+appt.minute;
			customTime+=' '+appt.midday;

			var appointmentFound = $filter('filter')( $scope.data.appointments , {
					status: '-1', time:customTime
			}, true );

			if( appointmentFound.length )
			{
				appointmentFound[0].choosen_time = true;	
			}

		},
		change_type_appt:function( value ){
				
			$scope.default.appt.type_appointment = value;
			
			if(value == 1 )
			{
				
				$('[ng-model="default.appt.date"]').attr('disabled', 'disabled');
				$('[ng-model="default.appt.hour"]').attr('disabled', 'disabled');
				$('[ng-model="default.appt.minute"]').attr('disabled', 'disabled');
				$('[ng-model="default.appt.midday"]').attr('disabled', 'disabled');

				var appointments = $filter('filter')( $scope.data.appointments , {
					status: '-1', choosen_time: true
				}, true );
				
				if( appointments.length )
				{
					appointments[0].choosen_time = false;	
				}
			}
			else	
			{	
				$('[ng-model="default.appt.date"]').removeAttr('disabled');
				$('[ng-model="default.appt.hour"]').removeAttr('disabled');
				$('[ng-model="default.appt.minute"]').removeAttr('disabled');
				$('[ng-model="default.appt.midday"]').removeAttr('disabled');

			}
		},
		submit: () => {
			
			var Data = angular.copy( $scope.default.appt );

			$('.submit').attr('disabled', 'disabled');

			$http.post('/appointment/save/', $.param( Data ) ,$httpConfig )
				.then( function(response){
					
					Notify.response( response.data );
					
					if( response.data.status === 1 )
					{
						$scope.default.appt.patient_id = "0";
						$scope.default.appt.notes 	   = "";
						$scope.default.appt.visit_type = "Nueva";
						$scope.default.appt.code 	   = "";

						$scope.action_appt.change_date();
						setTimeout( () => {
							var template = '<option value="0" disabled="true" selected="selected">Names : Date Of Birth</option>';
							$("#patient_id").append(template).trigger('change');
							$scope.change_patient();
						},1);
					}

					$('.submit').removeAttr('disabled');	
				});
		}
	}

	$scope.ngHelper = new ngHelper();
});
