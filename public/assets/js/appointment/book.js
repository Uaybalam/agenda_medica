moment.locale("en");

angular
	.module('ng_records_appointment', [] )
	.controller('ctrl-records',function( $scope, $http , $interval, $filter ){
	
	$scope.default                = {};
	$scope.appointments           = [];
	$scope.date_appointment       = moment().format('L');
	$scope.last_date_query        = angular.copy( $scope.date_appointment );
	$scope.next_date 			  = 0;
	
	var dt = new Date();
	$scope.dS = dt.getFullYear() + "-" + (dt.getMonth() + 1) + "-" + dt.getDate();
	
	$scope.compareArrival = function( appt , limitMinutes )
	{
		if(appt.time_arrival==='')
			return '';

		var dateApt    = $scope.date_appointment + ' '+ appt.time;
		var dateArrival = $scope.date_appointment + ' '+ appt.time_arrival;

		//console.log("fullDates",dateApt, dateArrival);
		
		var momentApt 	   = moment(dateApt, "MM/DD/YYYY hh:mm A");
		var momentArraival = moment(dateArrival,"MM/DD/YYYY hh:mm A");
		
		if(momentArraival <= momentApt )
		{
			return 'text-success';
		}
		if(limitMinutes)
		{
			momentApt = momentApt.add(limitMinutes, 'minutes');
		}

		if(momentApt >= momentArraival )
			return 'text-warning';
		else
			return 'text-danger';
	}

	$scope.backDay = function()
	{	
		var newDate = moment($scope.date_appointment, "MM/DD/YYYY").add(-1, 'days').format("MM/DD/YYYY");
		//$scope.date_appointment = newDate;
		//$scope.updateData($scope.date_appointment, true );

		setTimeout(function(){
			$("[ng-model='date_appointment']").datepicker('update',newDate)
		},1);
	}

	$scope.nextDay = function()
	{
		var newDate = moment($scope.date_appointment, "MM/DD/YYYY").add(1, 'days').format("MM/DD/YYYY");
		//$scope.date_appointment= moment($scope.date_appointment, "MM/DD/YYYY").add(1, 'days').format("MM/DD/YYYY");
		//$scope.updateData($scope.date_appointment, true );

		setTimeout(function(){
			$("[ng-model='date_appointment']").datepicker('update',newDate)
		},1);
	}

	$scope.visitTypeClass = function( visitType )
	{

		if(visitType === 'New' )
		{
			return 'label-success';
		}
		else if(visitType === 'Established')
		{
			return 'label-primary';
		}
		else if(visitType === 'F/Up')
		{
			return 'label-info'
		}
		else
		{
			return 'label-warning';	
		}	
	}

	$scope.initializeAppointments = function(){ 
		
		$scope.updateData( $scope.date_appointment );
		


    	$interval(function(){
			$scope.updateData( $scope.date_appointment );
		}, 5000 );

	}

	$scope.currentDate = function()
	{	
		setTimeout(function(){
			$(".form-control.input-sm.create-datepicker")
				.val($scope.last_date_query)
				.trigger("change");
		},1);
	}

	$scope.updateData = function( date , change ){
		
		
		var update = change || false;

		$http.get('/appointment/records/?date=' + date ).success( function(response){ 
			
			var current_time = parseInt( moment().format('YMMDDHHmm') );
			
			$scope.next_date   = 0;
			$scope.is_new_next = ( moment().format('L') === $scope.date_appointment ) ? true : false;
			
			if( update || $scope.appointments.length === 0 )
			{
				$scope.appointments = response.appointments;
				for( i = 0; i < $scope.appointments.length ; i++ )
		        {
					lapse_time = moment( $scope.appointments[i].date_appointment, "YYYY-MM-DD hh:mm:ss").fromNow();
					$scope.appointments[i].patient_age = get_age_patient( $scope.appointments[i].date_of_birth);

		        	$scope.appointments[i].lapse_time 	= lapse_time;
		        	$scope.appointments[i].waiting_open = ($scope.appointments[i].time_room !== '' && $scope.appointments[i].time_open==='' ) ? 
		        		moment($scope.appointments[i].time_room,'hh:mm A').fromNow(true) : '';
					
					if($scope.appointments[i].waiting_open!='')
					{
						unix     = moment($scope.appointments[i].time_room,'hh:mm A').diff(moment());
						duration = Math.round(parseFloat( moment.duration( Math.abs(unix) ).asMinutes() ));
						$scope.appointments[i].waiting_minutes_time = duration;
					}

					$scope.appointments[i].time_duration = $scope.diff_time($scope.appointments[i].time_arrival, $scope.appointments[i].time_done);

					$scope.mark_next_appt( $scope.appointments[i] , current_time );

		        }
			}
			else
			{
				for( i = 0; i < response.appointments.length ; i++ )
		        {
					
					lapse_time   = moment( response.appointments[i].date_appointment, "YYYY-MM-DD hh:mm:ss").fromNow();;
		        	waiting_open = (response.appointments[i].time_room !== '' && response.appointments[i].time_open==='' ) ? moment(response.appointments[i].time_room,'hh:mm A').fromNow(true) : '';
					appointment  = $filter('filter')( $scope.appointments , {
						id: response.appointments[i].id
					}, true );

					$scope.appointments[i].patient_age = get_age_patient( $scope.appointments[i].date_of_birth);

		        	if(waiting_open!='')
					{
						unix = moment(response.appointments[i].time_room,'hh:mm A').diff(moment());
						duration = Math.round(parseFloat( moment.duration( Math.abs(unix) ).asMinutes() ));
						response.appointments[i].waiting_minutes_time =  duration;
					}else{
						response.appointments[i].waiting_minutes_time = 0;
					}
					
					response.appointments[i].lapse_time    = lapse_time;
					response.appointments[i].waiting_open  = waiting_open;
					response.appointments[i].time_duration = $scope.diff_time(response.appointments[i].time_arrival, response.appointments[i].time_done);

					//is new
		        	if( appointment.length === 0 )
		        	{
		        		
		        		$scope.mark_next_appt( response.appointments[i], current_time );
			        	$scope.appointments.push( response.appointments[i] );
		        	}	
		        	else
		        	{//check 
		        		appointment[0].lapse_time   = lapse_time;
		        		appointment[0].waiting_open = waiting_open;
		        		if(waiting_open!='')
						{		
							unix     = moment(response.appointments[i].time_room,'hh:mm A').diff(moment());
							duration = Math.round(parseFloat( moment.duration( Math.abs(unix) ).asMinutes() ));
							appointment[0].waiting_minutes_time = duration;
						}else
						{
							appointment[0].waiting_minutes_time = 0;
						}

						appointment[0].arrival_at     = response.appointments[i].arrival_at;
						appointment[0].encounter_at   = response.appointments[i].encounter_at;
						
						appointment[0].room           = response.appointments[i].room;
						appointment[0].confirm        = response.appointments[i].confirm;
						
						appointment[0].date_confirm   = response.appointments[i].date_confirm;
						appointment[0].time_open      = response.appointments[i].time_open;
						appointment[0].time_nurse     = response.appointments[i].time_nurse;
						appointment[0].time_confirm   = response.appointments[i].time_confirm;
						appointment[0].time_arrival   = response.appointments[i].time_arrival;
						appointment[0].time_room      = response.appointments[i].time_room;
						appointment[0].status         = response.appointments[i].status;
						appointment[0].time_signed    = response.appointments[i].time_signed;
						appointment[0].time_done      = response.appointments[i].time_done;
						appointment[0].time_duration  = response.appointments[i].time_duration;
						appointment[0].insurance_type = response.appointments[i].insurance_type;
						//appointment[0].code           = response.appointments[i].code;
						appointment[0].notes          = response.appointments[i].notes;
						appointment[0].visit_type     = response.appointments[i].visit_type;

						$scope.mark_next_appt( appointment[0], current_time );

		        	}

		        	
		        }
			}

	    }).finally(function(){

			setTimeout(function(){
				
				$('[data-toggle="tooltip"]').tooltip();
				
				if($('#loading').css("display") === 'block')
				{
					$('#loading').fadeOut('fast');	
				}
			}, 1 );
	    });
	}

	$scope.mark_next_appt = function( appt, current_time){

		if( $scope.next_date === appt.full_date_sort)
		{	
			appt.next_appt     = true;
			$scope.is_new_next = false;
		}		
		else if( appt.full_date_sort >= current_time && $scope.is_new_next)
		{	
			$scope.next_date 	=   appt.full_date_sort ;
			appt.next_appt 		= true;
			$scope.is_new_next 	= false;
		}
		else
		{	
			appt.next_appt = false;
		}
	}

    $scope.set_status = function( sta ) {
    	
        var i = $.inArray(sta, $scope.statusInclude);
        if (i > -1) {
            $scope.statusInclude.splice(i, 1);
        } else {
            $scope.statusInclude.push(sta);
        }
    }
 	
    $scope.status_filter = function(appointment) {
    	
        if ($scope.statusInclude.length > 0) {
        	
            if ($.inArray(appointment.status, $scope.statusInclude) < 0)
                return;
        }	
        
        return appointment;
    }
    
	$scope.get_status = function( sta ){
		var status = $filter('filter')( $scope.catalog_status , {
			id: sta
		}, true );

		return status[0].name;
	}

	$scope.count_status = function( sta ){
		
		var found = $filter('filter')( $scope.appointments , {
			status: sta.id
		}, true );

		return found.length;
	}

	$scope.diff_time = function( time_start, time_end )
	{

		if(!time_start || !time_end)
		{
			return '';
		}
		
		var now  = moment($scope.dS+" "+time_start, "Y-MM-DD hh:mm A");
		var then = moment($scope.dS+" "+time_end, "Y-MM-DD hh:mm A");
		
		var diff = moment.duration(now.diff(then)).humanize();

		return diff 
	}
	
	$scope.action_appointment = new action_appointment( $scope, $http , $filter);

	$scope.initializeAppointments( $scope.date_appointment);
	
});

var action_appointment 	= function( $scope, $http, $filter ){
	
	var _modal_name = '';
	
	this.open = function( app , modal_name ){
		_modal_name = modal_name;
		
		if(app.status == 2 )
		{
			app.what_to_do = "1";
		}
		
		$scope.default.appointment     = angular.copy( app );

		if(modal_name === '#appointment-modal-reminder')
		{	
			
			$scope.default.appointment.reminder_message = '';

			$http.get('/patient/communication/' +  app.id + '/appointment' ).success( function(response){
				$scope.default.last_communications = response.communications;
				
			}); 
		}
		
		
		$( _modal_name ).modal();
	};		
	this.submit = function( event ){
		event.preventDefault();
		var Form = $(event.currentTarget);
		var Data = $scope.default.appointment,
			Btn  = $('.submit', Form );

		$(Btn).attr( 'disabled', 'disabled' );
		
		$http({
		    method: 'POST',
		    url: $(Form).attr('action') + '/' + $scope.default.appointment.id ,
		    data:  $.param(Data) ,
		    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).then(function(response){

			Notify.response( response.data );
			
			if(response.data.status === 1 )
			{
				var redirect = response.data.redirect || '';
				
				if( redirect!='')
				{		
					window.location = redirect;
					return true;
				}

				var appt  = $filter('filter')( $scope.appointments , {
					id: $scope.default.appointment.id
				}, true );
				
				appt[0].confirm          = response.data.appointment.confirm;
				appt[0].date_confirm     = response.data.appointment.date_confirm;
				appt[0].reminder_message = response.data.appointment.reminder_message;
				appt[0].encounter_at     = response.data.appointment.encounter_at;
				appt[0].time_arrival     = response.data.appointment.time_arrival;
				appt[0].arrival_at       = response.data.appointment.arrival_at;
				appt[0].status           = response.data.appointment.status;
				
				$( _modal_name ).modal('hide');
			};
			
			$(Btn).removeAttr( 'disabled' );
		});
	};
};

