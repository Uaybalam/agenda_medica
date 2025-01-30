var action_tuberculosis = function($scope, $http ){
	angular.extend(this, {
		open:function(){
			$scope.default.tuberculosis = angular.copy( $scope.data.patient_tuberculosis );
			$('#patient-chart-modal-tuberculosis').modal();
		},
		submit:function(){
			
			var Tuberculosis = angular.copy($scope.default.tuberculosis );
			
			$('.submit').attr('disabled','disabled');

			$http({
			    method: 'POST',
			    url: '/patient/tuberculosis/update/' + $scope.data.patient.id,
			    data:  $.param( Tuberculosis ) ,
			    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			}).then(function(response){
				Notify.response( response.data );
				if(response.data.status)
				{		
					$scope.data.patient_tuberculosis = angular.copy( $scope.default.tuberculosis );
					$('#patient-chart-modal-tuberculosis').modal('hide');
				};

				$('.submit').removeAttr('disabled');
			});
		}
	});
}

var action_vaccines = function( $scope, $http , $filter)
{
	angular.extend(this, {
		open: function(){
			$('#patient-chart-modal-vaccines').modal();
		},
		has_subtitle: function( title )
		{
			subtitles = this.get_subtitles(title);
			return subtitles.length>0 ? true : false;
		},
		get_subtitles:function( title ){
			
			var found  = $filter('filter')( $scope.data.vaccines_settings , {
				title: title
			}, true );

			if(found.length > 0 && found[0].subtitle != undefined)
			{
				var s = found[0].subtitle || '';
				return s.split(',');
			}
			else
			{
				return [];
			}
		},
		change_intern: function( Index ){
			if( $scope.data.vaccines_data[Index].intern ==='Yes' )
			{
				$scope.data.vaccines_data[Index].intern          = '';
				$scope.data.vaccines_data[Index].date_given      = '';
				$scope.data.vaccines_data[Index].administered_by = '';
			}
			else
			{		
				var today = new Date();
				var dd = today.getDate();
				var mm = today.getMonth()+1;
				var yyyy = today.getFullYear();
				
				dd = (dd < 10 ) ? '0'+dd : dd;
				mm = (mm < 10 ) ? '0'+mm : mm;
					
				$scope.data.vaccines_data[Index].intern = 'Yes';
				$scope.data.vaccines_data[Index].date_given = mm+'/'+dd+'/'+yyyy;
			}
			this.autosave( Index , true );
		},
		autosave: function( Index  , from_button ){
			var from_btn = from_button || false;
			var Vaccine = angular.copy($scope.data.vaccines_data[Index]);
			
			$http({
			    method: 'POST',
			    url: '/patient/vaccine/autosave/' + $scope.data.patient.id,
			    data:  $.param( Vaccine ) ,
			    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			}).then(function(response){
				if(!response.data.status)
				{
					Notify.error(response.data.message);
				}
				else
				{
					Notify.success("Last update "+moment().fromNow());
					if(from_btn)
					{		
						$scope.data.vaccines_data[Index] = response.data.vaccine_update;
					}
				}
				
				
			});
		}
	});
}

var action_warning = function( $scope , $http )
{
		
	angular.extend(this,{
		open: function(){
				
			$scope.default.warning = {
				description:'',
				request_reply:0,
				patient_id: $scope.data.patient.id
			};
			$('#patient-warnings-modal-patient-warning-create').modal();
		},
		reply:function( element, index ){
			$scope.default.warning_reply          = angular.copy( element );
			$scope.default.warning_reply['index'] = index; 
			$('#patient-warnings-modal-patient-warning-reply').modal();
		},
		update_reply:function(){

			$('.submit').attr('disabled','disabled');

			$http.post('/patient/warning/'+$scope.default.warning_reply.id+'/update-reply/',
				$.param( angular.copy( $scope.default.warning_reply ) ), 
				$httpConfig 
			).then(function( response ){
				Notify.response( response.data );
				if(response.data.status)
				{
					var index = $scope.default.warning_reply.index;
					$scope.data.warnings[index] = response.data.warning;
					$scope.data.warnings[index].lapse_time = moment($scope.data.warnings[index].create_at, '"YYYY-MM-DD hh:mm:ss"').fromNow();
					$('#patient-warnings-modal-patient-warning-reply').modal('hide');
					$('#pending-get_pending_warnings').html(response.data.pending);
				};
				
				$('.submit').removeAttr('disabled');
			}).finally(function(){
				setTimeout(function(){
					$("[data-toggle=tooltip]").tooltip();
				},1);
			});
		},
		submit: function( ){
			
			var add_warning;

			$('.submit').attr('disabled','disabled');

			$http({
			    method: 'POST',
			    url: '/patient/warning/create/',
			    data:  $.param( $scope.default.warning ) ,
			    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			}).then(function(response){
				
				Notify.response( response.data );
				
				if(response.data.status)
				{		
					add_warning = response.data.warning;
					add_warning.lapse_time = moment(add_warning.create_at, '"YYYY-MM-DD hh:mm:ss"').fromNow();
					$scope.data.warnings.push( response.data.warning );

					$('#patient-warnings-modal-patient-warning-create').modal('hide');
					$('#pending-get_pending_warnings').html(response.data.pending);
				};

				$('.submit').removeAttr('disabled');
			});
		},
		remove: function( element, index ){
			$http.get('/patient/warning/remove/'+ element.id).then(function(response) {
		        Notify.response( response.data );
		        $('#pending-get_pending_warnings').html(response.data.pending);
		        $scope.data.warnings.splice( index , 1);
		    });	
		}
		
	});
}

var action_file = function( $scope , $http )
{
	
	angular.extend(this,{
		open: function(){
			$scope.default.patient_related_files = {
				title:'',
				type:"0",
				name_file: '',
				document_for_done: "0",
				encounter_id: "0"
			};
			$('#action_file_value')[0].files[0] = {

			};
			$('#patient-chart-modal-related-file').modal();
		},
		changed: function( data ){
			var f = $(data)[0].files[0];
			
			if( typeof f != 'undefined' )
			{
				$scope.$apply(function(scope) {
		         	$scope.default.patient_related_files.name_file = f.name;
		     	});
			}
			return true;
		},
		submit: function(){
			$('.submit').attr('disabled', true );
			
			var file 	 = $('#action_file_value')[0].files[0] || {};
			var formData = new FormData();
			
			formData.append("file", file );
			formData.append("patient_id", $scope.data.patient.id );
			formData.append("title", $scope.default.patient_related_files.title );
			formData.append("type", $scope.default.patient_related_files.type );
			formData.append("encounter_id", $scope.default.patient_related_files.encounter_id );
			formData.append("document_for_done", $scope.default.patient_related_files.document_for_done );
			
			$http.post("/patient/related-files/save", formData, {
			    headers: { 'Content-Type': undefined },
			    transformRequest: angular.identity
			}).success(function (data) {
				Notify.response( data );
				if( data.status === 1 )
				{	
					$scope.data.related_files.push( data.related_file );
					$('#patient-chart-modal-related-file').modal('hide');
				}
				$('.submit').removeAttr('disabled');
			});
		},
		special_filter: function (data) {
			$('#view-panel-relatedfiles tr[data-toggle="tooltip"]').tooltip();	
			if( data.type ===  $scope.default.filter.related_file_type )
			{
				return true;
			}
			else if( parseInt( $scope.default.filter.related_file_type ) === 0 )
			{
				return true;
			}
			else
			{
				return false;
			}
		},
		preview: function($event, data ){
			$event.preventDefault();
	
			var random = Math.floor(Math.random() * 99999 );
			var pt = angular.copy($scope.data.patient);
			
			$scope.default.document                 = angular.copy(data);
			$scope.default.document['patient']      = pt.name+' '+pt.last_name;
			$scope.default.document['create_at']    = $scope.ngHelper.formatDate($scope.default.document.create_at); 
			$scope.default.document['urlOpenImage'] = '/patient/related-files/open/'+data.id+'?random='+random;
			$scope.default.document['urlImage']     = '/patient/related-files/open/'+data.id+'/preview/?random='+random; 
			
			$('#patient-chart-modal-related-file-preview').modal(); 
		},
		download_all: (files) =>
		{
			let ids = [];

			for (var i = 0; i < files.length; i++) {
				ids.push("ids["+i+"]="+files[i].id);
			} 
			let link    = document.createElement('a');
			link.href   = '/patient/related-files/download?' + ids.join("&");
			link.target = '_blank';
			link.click();
		}
	});
}

var action_activehistory = function($scope, $http)
{
	angular.extend(this,{
		show_pregnancies: function()
		{
			if( typeof $scope.data.history_active === 'undefined') return false;

			if( $scope.data.history_active.pregnancy_birth_control || 
				$scope.data.history_active.pregnancy_last_pap ||
				$scope.data.history_active.pregnancy_last_mamo ||
				this.total_pregnancy($scope.data.history_active)> 0 	)
			{
				return true;	
			}
			else
			{	
				return false;		
			}
			
			
		},
		total_pregnancy: function( data )
		{
			if( typeof data === 'undefined') return 0;
			var succesfull = parseInt(data.pregnancy_count_succesfull) || 0;
			var cesarean   = parseInt(data.pregnancy_count_cesarean) || 0;
			var abortions  = parseInt(data.pregnancy_count_abortions) || 0;
			return succesfull + cesarean + abortions;
		},
		open: function( data ){
			$("input[placeholder='Fecha']").datepicker({
		        format: 'mm/dd/yyyy',
		        language: 'es',
		        autoclose: true,
		        toggleActive: true,
		        todayHighlight: true,
		        todayBtn: true,
		        zIndexOffset: 1040,
		    }).on('hide', function(e) 
		    {
		    	if(this.value == "")
		    	{
		    		this.value = $scope.default.history_active[this.getAttribute("ng-model").split(".")[2]];
		    	} 
			});

			$scope.default.history_active = copy_data('default.history_active', data);
			$('#patient-detail-modal-history-active').modal();
		},
		submit: function(){
			
			$http({
			    method: 'POST',
			    url: '/patient/history-active/update/',
			    data:  $.param( $scope.default.history_active ) ,
			    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			}).then(function(response){
				
				Notify.response( response.data );

				if(response.data.status)
				{	
					$scope.data.history_active = $scope.default.history_active;
					$('#patient-detail-modal-history-active').modal('hide');
				};
			});
		}
	});
}


var action_vitals = function($scope, $http)
{
	var SELF = this;
	
	this.data_filter = function(){
		var filter = {
			from: $scope.default.filter.encounter_date_from,
			to: $scope.default.filter.encounter_date_to,
		};
		return $.param( filter );
	};
	
	this.range_filter = function ( item ) {
		
		var dateItem = date_to_number(item.date);
		var dateFrom = date_to_number($scope.default.filter.encounter_date_from);
		var dateTo   = date_to_number($scope.default.filter.encounter_date_to);
		
		if(dateFrom && dateFrom > dateItem)
		{
			return false;
		}
		if(dateTo && dateTo < dateItem)
		{
			return false;
		}

		return true;
	};

	this.modal = function(){
		return '#encounter-detail-modal-vitals';
	};

	this.submit = function( event ){
		event.preventDefault();
		var Form = $(event.currentTarget);	
		var Data =  $scope.default.encounter,
			Btn  = $('.submit', Form );
		$(Btn).attr( 'disabled', 'disabled' );
		
		$http({
		    method: 'POST',
		    url: '/encounter/create/' + $scope.data.patient.id ,
		    data:  $.param( Data ) ,
		    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).then(function(response){
			
			Notify.response( response.data );

			if(response.data.status === 1 )
			{		
				$scope.data.encounters.unshift( response.data.encounter );
				$scope.data.last_appointment = response.data.last_appointment;
				$( SELF.modal() ).modal('hide');
			}
			
			$(Btn).removeAttr( 'disabled' );
			
		}).finally(function(){
			setTimeout(function(){
				$('[data-toggle="tooltip"]').tooltip();
			},1);
		});
	};

	this.open = function( appointment_id ){
		
		$scope.default.encounter = {
			eye_glasess: '',
			heart_pulse: '',
			heart_respiratory: '',
			heart_temperature: '',
			heart_hemoglobin: '',
			heart_hematocrit: '',
			heart_head_circ: 0,
			heart_last_menstrual_period: '',
			physical_birth_weight: 0,
			physical_weight: 0,
			physical_height: 0,
			eye_left: '',
			eye_right: '',
			eye_both: '',
			eye_glasess: '',
			urinalysis_color: '',
			urinalysis_specific_gravity: 0,
			urinalysis_ph: '',
			urinalysis_protein: '',
			urinalysis_glucose: '',
			urinalysis_ketones: '',
			urinalysis_bilirubim: '',
			urinalysis_blood: '',
			urinalysis_leuktocytes: '',
			urinalysis_nitrite: '',
			urinalysis_human_chorionic_gonadotropin: '',
			condition_employment: '',
			condition_autoaccident: '',
			condition_other_accident: '',
			condition_state: '',
			procedure_text: '',
			procedure_xray_request: '',
			procedure_patient_education: '',
			audio_left_1000: 0,
			audio_left_2000: 0,
			audio_left_3000: 0,
			audio_left_4000: 0,
			audio_right_1000: 0,
			audio_right_2000: 0,
			audio_right_3000: 0,
			audio_right_4000: 0,
			appointment_id: appointment_id,
			chief_complaint: $scope.data.vitals_default_chief_complaint,
			current_medications: '',
			insurance_title: '',
			insurance_number: '',
			insurance_radio: ''
		};
		
		$(SELF.modal()).modal();
	};
	
	this.calc_bmi = function(){
		var weight = $scope.default.encounter.physical_weight || 0;
		var height = $scope.default.encounter.physical_height || 0;

		weight = parseFloat(weight, 2);	
		height = parseFloat(height, 2);

		if( weight > 0 && height > 0)
		{	
			var bmi = parseFloat(parseFloat( ( weight / (height * height) )).toFixed(2)) 
		}
		else
		{	
			var bmi = 0;
		}

		$scope.default.encounter.physical_bmi = bmi;
	};

	this.include_ins = function()
	{
		$scope.default.encounter.chief_complaint += "\n" + angular.copy($scope.data.questions_ins_inmigration);
		
	}
}

var action_appointment 	= function( $scope, $http )
{
	
	var _modal_name = '';
	
	this.open = function( encounter , modal_name ){
		_modal_name = modal_name;
		$scope.default.encounter = encounter;
		$scope.default.appointment = {
			room: ''
		};
		$( _modal_name ).modal();
	};
	this.submit = function( event ){
		event.preventDefault();
		
		var Form = $(event.currentTarget);
		
		var Data = {
			room: $scope.default.appointment.room
		}

		$('.submit').attr( 'disabled', 'disabled' );
		
		$http({
		    method: 'POST',
		    url: $(Form).attr('action') + '/' + $scope.default.encounter.appointment_id ,
		    data:  $.param(Data) ,
		    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).then(function(response){
			Notify.response( response.data );
			if(response.data.status === 1 )
			{
				$scope.default.encounter.room = Data.room;
				$( _modal_name ).modal('hide');
			};
			
			$('.submit').removeAttr( 'disabled' );
		});
	}
};

var action_contact = function($scope, $http)
{ 
	angular.extend( this, {
		open: function(){

			var full_name = $scope.data.patient.name+' '
				+ $scope.data.patient.middle_name+' '
				+ $scope.data.patient.last_name;

			$scope.default.contact = {
				patient_id: $scope.data.patient.id,
				full_name: full_name,
				reason: ''
			};

			$('#patient-modal-create-contact').modal();
		},
		submit: function(){
			
			var Data = $.param( $scope.default.contact );
			
			$http
				.post('/patient/contact/insert', Data ,{
					headers : {
	                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
	                }
				})
				.then( function(response){
					
					Notify.response( response.data );

					if(response.data.status)
					{	
						$('#patient-modal-create-contact').modal('hide');
					}

				});
		}
	});
};

var action_communicate = function( $scope, $http )
{
	moment.locale("en");

	$scope.default.communicate = {
		contact_id: 0,
		has_appointment : false,
		notes: '', 
		patient_id: 0,
		patient_full_name: '',
		patient_email: '',
		patient_phone: '',
		reason: '',
		visit_type: '1',
		code: '', 
		date: moment().format('L'),
		type: 2
	}; 	

	var cDate = new Date();
	var year  = cDate.getFullYear();
	var month = cDate.getMonth() + 1;
	month = ( month < 10 ) ? "0" + month : month;
	var day = cDate.getDate();
	day = (day < 10 ) ? "0" + day : day;
	$scope.currentDate = year + "" +month + "" + day;


	angular.extend(this, {
		open_history : function()
		{	
			$('#patient-communicate-modal-history-communication').modal();
			
			var patient_id = $scope.data.patient.id;

			$http.get('/patient/communication/history/' + patient_id ).success(function(response){ 
		    	$scope.data.history_communications = response.history_communications;
		    });
		},
		modal_pending : function()
		{	
			$('#appointment-modal-current-date').modal();
			
			var date = $scope.default.communicate.date;

			$http.get('/appointment/records/?date=' + date + '&visit_types=1').success(function(response){ 
		    	$scope.data.appointments = response.appointments;
		    	$scope.data.visit_types  = response.visit_types;
		    });
		},
		open : function()
		{
			var full_name = $scope.data.patient.name+' '
				+ $scope.data.patient.middle_name+' '
				+ $scope.data.patient.last_name;

			$scope.default.communicate.patient_full_name = full_name;
			$scope.default.communicate.patient_email     = $scope.data.patient.email;
			$scope.default.communicate.patient_phone     = $scope.data.patient.phone;
			$scope.default.communicate.patient_id        = $scope.data.patient.id;
			$scope.default.communicate.reason            = '';
			
			

			var choseDate   = $scope.default.communicate.date;
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
					begin.add("minutes",$scope.data.time);
				}

				allH = allH.filter((appt) => $scope.appointments.indexOf(appt) == -1);

				$scope.default.time = {hours: allH};  
			}

			$('#patient-communicate-modal-create-communication').modal();
		},
		submit : function()
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
						$scope.data.communications.unshift( response.data.item );
						$scope.default.communicate.notes = '';
						$('#patient-communicate-modal-create-communication').modal('hide');
					}
					
					$('.submit').removeAttr('disabled');

				});
		},
		data_filter: function(){
			var filter = {
				from: $scope.default.filter.communicate_date_from,
				to: $scope.default.filter.communicate_date_to,
			};
			return $.param( filter );
		},
		range_filter:function ( item ) {

			//var dateItem = date_to_number(item.date);
			
			var dateItem = date_to_number(item.create_at, true );	
			var dateFrom = date_to_number($scope.default.filter.communicate_date_from);
			var dateTo   = date_to_number($scope.default.filter.communicate_date_to);
			
			if(dateFrom && dateFrom > dateItem)
			{
				return false;
			}
			if(dateTo && dateTo < dateItem)
			{
				return false;
			}

			return true;
		},
		change_time:() => 
		{   
			if(moment($scope.default.communicate.date, "MM/DD/YYYY").format("YYYY-MM-DD") != "Invalid date")
			{
				$http
				.post('/patient/appointments/getAppointments', $.param({patient_id:$scope.data.patient.id,date:$scope.default.communicate.date}),{
					headers : {
	                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
	                }
				})
				.then( function(response){

					$scope.default.communicate.hour  = "";
					var choseDate   = $scope.default.communicate.date;
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
							begin.add("minutes",$scope.data.time);
						}

						$scope.appointments = response.data.appointments.map(element => moment(element.date_appointment).format('hh:mm A'));
						
						allH = allH.filter((appt) => $scope.appointments.indexOf(appt) == -1);

						$scope.default.time = {hours: allH};  
					}
					else
					{
						$scope.default.time = {hours: {}};
					}

				}); 
			}
		}
	});
}

angular
	.module('ng_patient_chart',['angular.filter', 'angularUtils.directives.dirPagination'])
	.controller('ctrl_patient_chart',function($scope, $http, $filter){
		
		$scope.dirPagCommunication = function( num )
		{
			setTimeout(function(){
				$("[data-toggle=tooltip]").tooltip();
			},1)
		}

		$scope.appointment_id           = 0;
		$scope.pending_create_encounter = {
			appointment_id: 0
		};

		$scope.data    = {}; 
		$scope.options = {Yes: "Si", No: "No"};
		$scope.default = {
			encounter: {},
			appointment: {},
			time:{hours:{}},
			vitals: {},
			history_active: {},
			document: {urlImage: ""},
			filter:{
				related_file_type: "0",
				communicate_date_from: '',
				communicate_date_to: ''
			},
			statement:{
				bmi_text:[
					{'Bajo peso':'<18.5'},
					{'Normal':'>=18.5 && <=25'},
					{'Sobrepeso':'>=25 && <=30'},
					{'Obesidad':'>30'}
				],
				bmi_class:[
					{'text-danger':'<18.5'},
					{'text-success':'>=18.5 && <=25'},
					{'text-warning':'>=25 && <=30'},
					{'text-danger':'>=31'}
				],
				bp_sys_class:[
					{'text-warning':'<=90'},
					{'text-success':'>90 && <=120'},
					{'text-info':'>120 && <140'},
					{'text-warning':'>=140 && <150'},
					{'text-danger':'>=150'}
				],
				bp_sys_text:[
					{'Baja':'<=90'},
					{'Normal':'>90 && <=120'},
					{'Prehipertensión':'>120 && <140'},
					{'Hip. Etapa 1':'>=140 && <150'},
					{'Hip. Etapa 2':'>=150 && <180'},
					{'**Crisis hipertensiva**':'>=180'}
				],
				bp_dia_class:[
					{'text-warning':'<=60'},
					{'text-success':'>60 && <=80'},
					{'text-info':'>80 && <90'},
					{'text-warning':'>=90 && <100'},
					{'text-danger':'>=100'}
				],
				bp_dia_text:[
					{'Low':'<=60'},
					{'Normal':'>60 && <=80'},
					{'Prehipertensión':'>80 && <90'},
					{'Hip. Etapa 1':'>=90 && <100'},
					{'Hip. Etapa 2':'>=100 && <=110'},
					{'**Crisis hipertensiva**':'>110'}
				],
				temp_class:[
					{'text-warning':'>0 && <=95.8'},
					{'text-success':'>=95.9 && <=99.5'},
					{'text-danger':'>=99.6'}
				],
				temp_text:[
					{'Hipotermia':'>0 && <95.9'},
					{'Normal':'>=95.9 && <=99.5'},
					{'Fiebre':'>=99.6'}
				]
			}
		};

		
		$scope.initialize = function( patient_id )
		{
			
			$http.get('/patient/chart/init/'+ patient_id ).then(function(response) {
		        
		        $scope.data = response.data;
		        $scope.appointments = response.data.appointments.map(element => moment(element.date_appointment).format('hh:mm A'));
				$scope.data.patient.age   = get_age_patient( response.data.patient.date_of_birth );
			
				for(i=0; i< $scope.data.warnings.length; i++)
				{
					$scope.data.warnings[i].lapse_time = moment($scope.data.warnings[i].create_at, '"YYYY-MM-DD hh:mm:ss"').fromNow();
				}
				 
		        setTimeout(function()
		        {	
		        	$( '.create-datepicker-vaccines' ).bind('keydown', function (event) {
			            if (event.which == 13) {
			                var e = jQuery.Event("keydown");
							e.which   = 9;//tab 
							e.keyCode = 9;
			                $(this).trigger(e);
			                return false;
			            }
					}).datepicker({
				        format: 'mm/dd/yyyy',
				        language: 'es',
				        autoclose: true,
				        toggleActive: true,
				        todayHighlight: true,
				        todayBtn: true,
				        zIndexOffset: 1040
				    });
					
					$('[data-toggle="tooltip"]').tooltip();	

		        }, 100);
		    });
		}

		$scope.list_medications = function()
		{
			$http.get('/patient/chart/medications/'+ $scope.data.patient.id ).then( function( response ) {
		        $scope.data['list_medications'] = response.data.medications;
			});
			
			$('#patient-chart-modal-list-medications').modal();
		}
		
		$scope.list_diagnosis = function()
		{
			$http.get('/patient/chart/diagnostics/'+ $scope.data.patient.id ).then( function( response ) {
		        $scope.data['list_diagnostics'] = response.data.diagnostics;
			});
			
			$('#patient-chart-modal-list-diagnosis').modal();
		}
		
		$scope.action_vitals        = new action_vitals( $scope, $http);
		
		$scope.action_appointment   = new action_appointment( $scope, $http );
		$scope.action_contact       = new action_contact( $scope, $http);
		$scope.action_communicate   = new action_communicate( $scope, $http);
		$scope.action_activehistory = new action_activehistory( $scope, $http ); 
		$scope.action_file          = new action_file( $scope, $http );
		$scope.action_warning       = new action_warning( $scope, $http );
		$scope.action_vaccines 		= new action_vaccines( $scope, $http, $filter );
		$scope.action_tuberculosis  = new action_tuberculosis( $scope, $http);
		$scope.ngHelper 			= new ngHelper($scope);

		$scope.dinamicValues = function( defaultValue, statements )
		{
			if(	statements === undefined || 
				defaultValue=== undefined || 
				defaultValue==='' || 
				defaultValue===0)
			{
				return '';
			}
			
			for(var i = 0; i< statements.length; i ++)
			{
				for(var c in statements[i])
				{
					var str 	= defaultValue + " " +statements[i][c].replace("&&","&& " + defaultValue);
					var strStm 	= "if("+str+" ) {  return  '"+c+"' } else { return ''}";
					var strContent = new Function(strStm);
					var value = strContent();
					if(value)
					{	
						return value;
					}
				}
			}
		}
	});


