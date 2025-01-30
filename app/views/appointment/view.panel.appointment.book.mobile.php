<div class="panel-body" ng-cloak style="height:auto;" >
	<ul class="list-group">
		<li class="list-group-item" ng-repeat="appt in appointments|filter:search|filter:status_filter|orderBy:'full_date_sort'">
			<div class="dropdown">
				<button class="btn btn-md btn-primary dropdown-toggle btn-block" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
					Eventos <span class="caret"></span>
				</button>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
					<li>
						<a ng-href="/appointment/detail/{{ appt.id }}"> <i class="fa fa-edit"></i> Detalles de cita</a>
					</li>
					<li>
						<a ng-show="['root','admin','medic','nurse'].indexOf('<?= $this->current_user->access_type; ?>')>=0 " ng-href="/patient/chart/{{ appt.patient_id }}" > <i class="icon-folder-plus"></i>  Expediente del paciente </a>
					</li>
					<li>
						<a ng-show="appt.status > 5 && appt.encounter_id>0"  ng-href="/encounter/request/{{ appt.encounter_id }}"> <i class="fa fa-medkit"></i> Solicitudes de consulta</a>
					</li>
					<li role="separator" class="divider"></li>
					<li>
						<a href="#" ng-show="appt.status === 1 && appt.confirm==0" type="button"  ng-click="action_appointment.open( appt , '#appointment-modal-reminder')" > <i class="fa fa-bell-o"></i> Recordar </a>
					</li>
					<li>
						<a href="#" ng-show="appt.status === 1" type="button"  ng-click="action_appointment.open( appt , '#appointment-modal-arrival')" > <i class="fa fa-calendar-check-o" aria-hidden="true"></i> Registrar llegada"</a>
					</li>
					<li>
						<a href="#" ng-show="appt.status === 2" type="button"  ng-click="action_appointment.open( appt , '#appointment-modal-coming')" > <i class="fa fa-stethoscope" aria-hidden="true"></i> Registrar con asistente medico</a>			      		
					</li>
					<li>
						<a href="#" ng-show="['root','admin','nurse','medic'].indexOf('<?= $this->current_user->access_type; ?>')>=0 && apt.status === 4" type="button"  ng-click="action_appointment.open( appt , '#appointment-modal-room')" > <i class="fa fa-map-marker" aria-hidden="true"></i> Seleccionar cuarto </a>
					</li>
				</ul>
			</div>
			<br>
			<div class="row">
				<div class="col-xs-5">
					<p 
						ng-class="(appt.next_appt) ? 'text-success' : ''" ng-style="(appt.next_appt) ?  {'font-weight':'bold'}: {}"> 
							<span > <i class="fa fa-clock-o"></i> {{ appt.time }}</span>
					</p>
				</div>
				<div class="col-xs-7 text-right">
					{{ get_status(appt.status) }}
				</div>
			</div>
			<p>
				<a style="text-decoration: underline;" href="/patient/detail/{{ appt.patient_id }}"> <i class="fa fa-user"></i> {{ appt.patient }}</a>
			</p>
			<p><i class="fa fa-commenting"></i> {{ appt.notes}} <span class="text-muted"> {{ appt.visit_type }}</p>
			
			<div class="well well-sm" style="margin-bottom: 0px;">
					<span ng-show="appt.status==1">Sin eventos</span>
					<span ng-show="appt.status==1 && appt.reminder_message && appt.confirm==0" class="text-warning"> <b>Recordatorio: </b>{{ appt.reminder_message }} </span> 
					<span ng-show="appt.status==1 && appt.confirm==1" class="text-success"><b>Confirmar </b> {{ appt.date_confirm }}</span>
					<span ng-show="appt.status==2" class="text-warning"><b>Tiempo de llegada: </b>{{ appt.time_arrival }} </span>
					<span ng-show="appt.status==3" class="text-warning"><b>Tiempo con asistente medico: </b>{{ appt.time_nurse }} </span>
					<span ng-show="appt.status==4" class="text-warning">Esperando un cuarto</span>
					<span ng-show="appt.time_room!='' && appt.time_open===''" ng-class="appt.waiting_minutes_time >  <?= $_['minutes_waiting_doctor']; ?> ? 'text-danger' : 'text-success'" > <b>Cuarto {{ appt.room }},</b>  {{ appt.waiting_open }} esperando por un doctor </span>
					<span ng-show="appt.status==5 && appt.time_open"  class="text-warning"><b>Dr time: </b>{{ appt.time_open }} </span>
					<span ng-show="appt.status==6" class="text-warning"><b>Hora de firma: </b>{{ appt.time_signed }} </span>
					<span ng-show="appt.status==7" class="text-warning"><b>Hora de completado: </b>{{ appt.time_done }} </span>
					<span ng-show="appt.status==8" class="text-danger"><b>Razón: </b>{{ appt.reason_cancel }} </span>
			</div>
		</li>	
	</ul>
</div>