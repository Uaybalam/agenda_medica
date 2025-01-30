angular
	.module('ng_appointment_detail', ['ngSanitize'] )
	.controller('ctrl_appointment_detail', function( $scope, $http ){

	$scope.canCancel = function( status ){
		
		if(status == 1 || status == 2)
		{
			return true;
		}
		
		return false;
	}

	$scope.visitTypeClass = function( visitType )
	{

		if(visitType === 'Nueva' )
		{
			return 'label-success';
		}
		else if(visitType === 'Programada')
		{
			return 'label-primary';
		}
		else if(visitType === 'Seguimiento')
		{
			return 'label-info'
		}
		else
		{
			return 'label-warning';	
		}	
	}

	$scope.default = {
		appointment: {}
	}

	$scope.humanDate = function(d)
	{
		return moment( d, "YYYY-MM-DD hh:mm:ss").fromNow()
	}
	$scope.formatDate = function(d)
	{	
		return moment( d, "YYYY-MM-DD hh:mm:ss").format('lll');
	}
	
	$scope.initialize = function( appointment_id ){
		$http.get('/appointment/detail/'+ appointment_id + '/initialize')
			.then(function(response){
 
				response.data.events = response.data.events.map(element => {
					return {
						...element,
						notes: element.notes.replace('Reason Cancel', 'Razón de cancelación').replace("Changed by","Cambiado por")
					}
				});
				
				$scope.data = response.data
 				
				var begin = moment($scope.data.opened,'H:mm');
				var end   = moment($scope.data.closed,'H:mm'); 
				var allH  = [];

				while(begin.isBefore(end))
				{ 
					let time    = begin.format('hh:mm A');
					let timeTmp = begin.format('HHmm');
					
					allH.push(time);
					begin.add("minutes",$scope.data.time);
				}

				$scope.default.time = {hours: allH};  
			})
			.finally(function(response){
				setTimeout(function(){
				
					$('[data-toggle="tooltip"]').tooltip();
					
				}, 1 );
			});
	};

	$scope.get_name_event = function( u_name ){
		if(typeof $scope.data.available_events[u_name] !== 'undefined' )
		{
			return $scope.data.available_events[u_name]['name'];
		}
		return '';
	};

	$scope.get_class_finished = function( u_name ){
		
		if(u_name === 'checkout')
		{
			return 'success';
		}
		else if(u_name === 'not_show')
		{
			return 'warning';
		}
		else if(u_name === 'cancel')
		{
			return 'danger';
		}

		return '';
	}
	
	$scope.action_appointment = {
		open: function( type_update ){console.log(type_update);
			
			var times   = moment($scope.data.appointment.date_appointment).format('h:mm A');
		 
			$scope.default.appointment = {
				type_update: type_update,
				date: moment($scope.data.appointment.date_appointment).format('L'),
				hour: times, 
				reason_cancel: '',
				visit_type: $scope.data.appointment.visit_type,
				notes: $scope.data.appointment.notes,
				code: $scope.data.appointment.code,
				insurance_type:$scope.data.appointment.insurance_type
			};
			
			$('#appointment-modal-edit-detail').modal();
		},
		update:function(){
			
			var Url  = '/appointment/update/' + $scope.data.appointment.id + '/' + $scope.default.appointment.type_update;
			var Data = angular.copy( $scope.default.appointment );
			
			$('.submit').attr('disabled','disabled');

			$http.post( Url, $.param( Data ), $httpConfig )
				.then(function(response ){
					
					Notify.response( response.data )
					
					if( response.data.status )
					{	
						$scope.data.appointment = response.data.appointment;
						$scope.data.events      = response.data.events;
						$scope.data.can_edit    = response.data.can_edit;

						$('#appointment-modal-edit-detail').modal('hide');
					}
					
					$('.submit').removeAttr('disabled');
					
		        }).finally(function(response){
					setTimeout(function(){  $('[data-toggle="tooltip"]').tooltip();}, 1 );
				});
		},
		checkDisabled:function(){

			if($scope.default.appointment.type_update === 'date' && this.sameValueDate())
			{
				return true;
			}
			else if($scope.default.appointment.type_update === 'code' && this.sameValueCustom('code'))
			{
				return true;
			}
			else if($scope.default.appointment.type_update === 'insurance_type' && this.sameValueCustom('insurance_type'))
			{
				return true;
			}
			else if($scope.default.appointment.type_update === 'visit_type' && this.sameValueCustom('visit_type'))
			{
				return true;
			}
			else if($scope.default.appointment.type_update === 'notes' && this.sameValueCustom('notes'))
			{
				return true;
			}
			else if($scope.default.appointment.type_update === 'cancel' && this.sameValueCustom('reason_cancel'))
			{
				return true;
			}
			return false;
		},
		sameValueDate:function(){
			
			var times   = moment($scope.data.appointment.date_appointment).format('h:mm:A').split(':');
			var $date   = moment($scope.data.appointment.date_appointment).format('L');
			var $hour   = parseInt(times[0]);
			var $minute = times[1];
			var $meridian= times[2];
			
			if( $scope.default.appointment.date == $date 
				&& $scope.default.appointment.hour == $hour 
				&& $scope.default.appointment.minute == $minute
				&& $scope.default.appointment.meridian == $meridian )
			{
				return true;
			}

			return false;
		},
		sameValueCustom:function( name ){
			if($scope.default.appointment[name] == $scope.data.appointment[name] )
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}
	
});
