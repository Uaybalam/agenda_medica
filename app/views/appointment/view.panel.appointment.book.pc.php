<div class="panel-body">
	<table class="table table-hover table-condensed table-bordered" >
		<thead>
			<tr class="">
				<th class="col-md-1 text-center" style="vertical-align: inherit;">Eventos</th>
				<th class="col-md-1 text-center" style="vertical-align: inherit;">Hora</th>
				<th class="col-md-2">
					<input type="text" ng-model="search.patient" class="form-control input-sm" placeholder="Paciente">
				</th>
				<th class="col-md-1" >
					<div class="form-group form-grpup-sm" style="margin:0px;">
						<div class="btn-group btn-group-sm"  style="width:100% !important;">
				            <button title="Filtrar por estatus" data-toggle="dropdown" style="width:100% !important;" class="btn btn-sm btn-default dropdown-toggle" data-placeholder="Todos los estatus"> Filtrar por estatus <span class="caret"></span></button>
			            	<ul class="dropdown-menu">
				              	<li ng-repeat="sta in catalog_status">
				                	<input ng-checked="sta.checked" type="checkbox" id="apt-status-{{sta.id}}" value="{{sta.id}} " ng-click="set_status( sta.id )" >
				               	 	<label for="apt-status-{{sta.id}}">{{sta.name}} <span class="text-muted">({{count_status(sta)}})</span></label>
				              	</li> 
			           		</ul>
		          		</div>
		          	</div>
				</th>
				<th class="col-md-4">
					<input type="text" class="form-control input-sm" readonly="true" value="Notas & tipo de cita">
				</th>
				<th class="col-md-1">
					<input type="text" class="form-control input-sm" readonly="true" value="Tipo de seguro">
				</th>
				<th class="col-md-1">
					<input type="text" class="form-control input-sm" readonly="true" value="Codigo">
				</th>
				<th class="col-md-2">
					<input type="text" class="form-control input-sm" readonly="true" value="Último evento">
				</th>
			</tr>
		</thead>
		<tbody ng-cloak >
			<tr ng-repeat="appt in appointments|filter:search|filter:status_filter|orderBy:'full_date_sort'">
				<td class="hover-mark-border" >
						
						<a  class="btn btn-info btn-xs" title="Detalle de cita" data-toggle="tooltip"  ng-href="/appointment/detail/{{ appt.id }}"> <i class="fa fa-edit"></i> </a>

						<a class="btn btn-info btn-xs" title="Expediente del paciente" data-toggle="tooltip"  ng-show="['root','admin','medic','nurse'].indexOf('<?= $this->current_user->access_type; ?>')>=0 " ng-href="/patient/chart/{{ appt.patient_id }}" > <i class="icon-folder-plus"></i>  </a>
						
						<a class="btn btn-info btn-xs" title="Solicitudes" data-toggle="tooltip" ng-show="appt.status > 5 && appt.encounter_id>0"  ng-href="/encounter/request/{{ appt.encounter_id }}"> <i class="fa fa-medkit"></i></a>

						<button class="btn btn-success btn-xs" title="Recordatorio" data-toggle="tooltip" ng-show="appt.status === 1 && appt.confirm==0" type="button"  ng-click="action_appointment.open( appt , '#appointment-modal-reminder')" > <i class="fa fa-bell-o"></i>  </button>
						
						<button class="btn btn-success btn-xs" title="Registrar llegada" data-toggle="tooltip" ng-show="appt.status === 1" type="button"  ng-click="action_appointment.open( appt , '#appointment-modal-arrival')" > <i class="fa fa-calendar-check-o" aria-hidden="true"></i> </button>
						
						<button class="btn btn-success btn-xs" title="Registrar con asistente medico" data-toggle="tooltip" ng-show="appt.status === 10" type="button"  ng-click="action_appointment.open( appt , '#appointment-modal-coming')" > <i class="fa fa-stethoscope" aria-hidden="true"></i> </button>

						<button class="btn btn-success btn-xs" title="Preparar expediente" data-toggle="tooltip" ng-show="appt.status=== 2" type="button"  ng-click="action_appointment.open( appt , '#appointment-modal-chartup')" > <i class="fa fa-file" aria-hidden="true"></i>  </button>

						<button class="btn btn-success btn-xs" title="Set room" data-toggle="tooltip" ng-show="['root','admin','nurse','medic'].indexOf('<?= $this->current_user->access_type; ?>')>=0 && appt.status == 4" type="button"  ng-click="action_appointment.open( appt , '#appointment-modal-room')" > <i class="fa fa-map-marker" aria-hidden="true"></i>  </button>
				</td>
				<td class="hover-mark text-center" ng-class="(appt.next_appt) ? 'text-success' : ''" ng-style="(appt.next_appt) ?  {'font-weight':'bold'}: {}">{{ appt.time }} <i ng-show="appt.time_arrival" data-toggle="tooltip" title="Hora de llegada: {{ appt.time_arrival}}" class="pull-right fa fa-sign-in" ng-class="compareArrival(appt,  <?= $_['minutes_late_to_appointment']; ?> )" aria-hidden="true"></i> </td>
				<td class="hover-mark"><a  href="/patient/detail/{{ appt.patient_id }}">{{ appt.patient }} ({{appt.patient_age}})</a></td>
				<td class="hover-mark">
					{{ get_status(appt.status) }} <span data-toggle="tooltip" title="Room" class="pull-right label label-default" ng-show="appt.room!=''">{{ appt.room}}</span>
				</td>
				<td class="hover-mark">  
					{{ appt.notes}} <span class="pull-right label" ng-class="visitTypeClass(appt.visit_type)" style="font-size:10px;"> {{ appt.visit_type }} </span>
				</td>
				<td class="text-center">
					{{ appt.insurance_type }}
				</td>
				<td class="text-center">
					{{ appt.code }}
				</td>
				<td class="hover-mark">
					<span ng-show="appt.status==1 && appt.reminder_message && appt.confirm==0" class="text-warning"> <b>Rmd: </b>{{ appt.reminder_message }} </span> 
					<span ng-show="appt.status==1 && appt.confirm==1" class="text-success"><b>Confirmado </b> {{ appt.date_confirm }}</span>
					<span ng-show="appt.status==2" class="text-warning"><b>Hora de llegada: </b>{{ appt.time_arrival }} </span>
					<span ng-show="appt.status==3" class="text-warning"><b>Hora con asistente medico: </b>{{ appt.time_nurse }} </span>
					<span ng-show="appt.status==4" class="text-warning">Esperando por un cuarto</span>
					<span ng-show="appt.time_room!='' && appt.time_open===''" ng-class="appt.waiting_minutes_time > <?= $_['minutes_waiting_doctor']; ?> ? 'text-danger' : 'text-warning'" > <b>Cuarto {{ appt.room }},</b>  {{ appt.waiting_open }} waiting for the doctor </span>
					<span ng-show="appt.status==5 && appt.time_open"  class="text-warning"><b>Hora con el doctor: </b>{{ appt.time_open }} </span>
					<span ng-show="appt.status==6" class="text-warning"><b>Hora de firma: </b>{{ appt.time_signed }} </span>
					<span ng-show="appt.status==7" ><b>Hora en la clinica: </b>{{ appt.time_duration }} </span>
					<span ng-show="appt.status==8" class="text-danger"><b>Razón: </b>{{ appt.reason_cancel }} </span>
					
				</td>
			</tr>
			<tr ng-cloak >
				<td ng-show="(!appointments.length)" class="text-center" colspan="8">
					<h3>No hay citas para la fecha {{date_appointment}} </h3>
					<a class="btn btn-info" href="/appointment/create" style="margin-bottom: 12px;"> Agregar nueva cita </a>
				</td>
			</tr>
		</tbody>
	</table>
</div>