<div class="row" style="font-size:12px;" ng-cloak>
	<div class="col-md-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-8"> <label>Detalle de cita</label></div>
					<div class="col-md-4 text-right">
						<a title="Imprimir cita" data-placement="bottom" target="_blank" data-toggle="tooltip" ng-href="/appointment/pdf/{{ data.appointment.id }}" class="btn btn-warning btn-xs" > <i class="fa fa-print"></i> </a>
						<a ng-show="data.appointment.encounter_id>0 &&  data.appointment.status>5" title="Encounter requests"  data-placement="bottom" data-toggle="tooltip" ng-href="/encounter/request/{{ data.appointment.encounter_id }}" class="btn btn-info btn-xs" > <i class="fa fa-medkit"></i> </a>
						<button ng-show="canCancel(data.appointment.status)" ng-click="action_appointment.open('cancel');" title="¿Estás seguro/a de que deseas cancelar esta cita?" data-placement="bottom" data-toggle="tooltip" type="button" class="btn btn-danger btn-xs" > <i class="fa fa-trash"></i> Cancelar cita</button>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<table class="table table-hover-app table-condensend table-bordered" ng-cloak>
					<tbody>
						<tr>
							<th class="col-md-3" >Numero</th>
							<td class="col-md-9" colspan="3">{{  data.appointment.id }}</td>
						</tr>
						<tr>
							<th class="col-md-3">Estatus</th>
							<td class="col-md-3">{{  data.arr_status[data.appointment.status] }}</td>
							<th class="col-md-3">Tipo</th>
							<td class="col-md-3"> 
								<span ng-show="data.appointment.type_appointment==0">Cita</span>
								<span ng-show="data.appointment.type_appointment==1">Sin cita</span>
							</td>
						</tr>
						<tr>
							
						</tr>
						<tr>
							<th class="col-md-3">Identificador de paciente</th>
							<td class="col-md-3">{{ data.patient_info.id }}</td>
							<th class="col-md-3">Nombre del paciente</th>
							<td class="col-md-3">
								<a  ng-href="/patient/detail/{{ data.appointment.patient_id }}" >{{ data.patient_info.name }} {{ data.patient_info.middle_name }} {{ data.patient_info.last_name }} </a> </td>
						</tr>
						<tr>
							<th class="col-md-3">Codigo</th>
							<td class="col-md-3" >{{ data.appointment.code }}
								<button ng-show="data.can_edit.code" ng-click="action_appointment.open('code');" title="Editar codigo"  data-toggle="tooltip" type="button" class="btn btn-success btn-xs pull-right" > <i class="fa fa-edit"></i></button>
							</td>
							<th class="col-md-3">Tipo de seguro</th>
							<td class="col-md-3">{{ data.appointment.insurance_type }}
								<button ng-show="data.can_edit.insurance_type" ng-click="action_appointment.open('insurance_type');" title="Editar tipo de seguro"  data-toggle="tooltip" type="button" class="btn btn-success btn-xs pull-right" > <i class="fa fa-edit"></i></button>
							</td>
						</tr>
						<tr>
							<th class="col-md-3">Tipo de visita</th>
							<td class="col-md-9" colspan="3">
								<span style="font-size: 11px;" class="label" ng-class="visitTypeClass(data.appointment.visit_type)">{{ data.appointment.visit_type }}</span>
								<button ng-show="data.can_edit.visit_type" ng-click="action_appointment.open('visit_type');" title="Editar tipo de visita"  data-toggle="tooltip" type="button" class="btn btn-success btn-xs pull-right" > <i class="fa fa-edit"></i></button>
							</td>
						</tr>
						<tr>
							<th class="col-md-3">Notas</th>
							<td class="col-md-9" colspan="3">{{ data.appointment.notes }}
								<button ng-show="data.can_edit.notes" ng-click="action_appointment.open('notes');" title="Editar Notas"  data-toggle="tooltip" type="button" class="btn btn-success btn-xs pull-right" > <i class="fa fa-edit"></i></button>
							</td>
						</tr>
						<tr>
							<th class="col-md-3">Fecha de cita</th>
							<td class="col-md-9" colspan="3"><i class="fa fa-clock-o" 
									data-toggle="tooltip" 
									data-original-title="{{ formatDate(data.appointment.date_appointment) }}" ></i>
							 	{{  humanDate(data.appointment.date_appointment) }}
							 	<button ng-show="data.can_edit.appointment_date" ng-click="action_appointment.open('appointment_date');" title="Editar fecha de cita"  data-toggle="tooltip" type="button" class="btn btn-success btn-xs pull-right" > <i class="fa fa-edit"></i></button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-8"> <label>Horario</label></div>
					<div class="col-md-4 text-right">
					</div>
				</div>
			</div>
			<div class="panel-body">
				<table class="table table-hover-app table-condensend table-bordered">
					<tbody>
						<tr>
							<th class="col-md-4">Llegada</th>
							<td class="col-md-8">{{ data.appointment.time_arrival }}</td>
						</tr>
						<tr>
							<th class="col-md-4">Expediente Preparado</th>
							<td class="col-md-8">{{ data.appointment.time_chartup }}</td>
						</tr>
						<tr>
							<th class="col-md-4">Llegada con Enfermera</th>
							<td class="col-md-8">{{ data.appointment.time_nurse }}</td>
						</tr>
						<tr>
							<th class="col-md-4">Cuarto</th>
							<td class="col-md-8">{{ data.appointment.time_room }}</td>
						</tr>
						<tr>
							<th class="col-md-4">Hora de atención <!-- Doctor--></th>
							<td class="col-md-8">{{ data.appointment.time_open }}</td>
						</tr>
						<tr>
							<th class="col-md-4">Firmado</th>
							<td class="col-md-8">{{ data.appointment.time_signed }}</td>
						</tr>
						<tr >
							<th class="col-md-4">Completado</th>
							<td class="col-md-8">{{ data.appointment.time_done }}</td>
						</tr>
						<tr
							data-toggle="tooltip" 
							data-placement="bottom" 
							title="Time at the clinic">
							<th class="col-md-4">Total time</th>
							<td class="col-md-8">{{ data.appointment.time_length }}</td>
						</tr>
					</tbody>
				</table>

			</div>
		</div>
	</div>


</div>

<div class="row"  style="font-size:12px;" ng-cloak>
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-8"> <label>Registro de Eventos</label> 
						<button  
							title="Descripcion de eventos" 
							data-target="#appointment-modal-helpevents" 
							data-toggle="modal"
							type="button" class="btn btn-success btn-xs" ><i class="fa fa-question"></i></button> 
					</div>
					<div class="col-md-4 text-right">
					</div>
				</div>
			</div>
			<div class="panel-body">
				<table class="table table-hover-app table-condensend table-bordered">
					<thead>
						<tr>
							<th class="col-md-1 text-center" style="text-align: left;">Usuario</th>
							<th class="col-md-2 text-center" style="text-align: left;">Fecha</th>
							<th class="col-md-3 text-center" style="text-align: left;">Evento</th>
							<th class="col-md-6 text-center" style="text-align: left;">Notas</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="event in data.events" ng-class="get_class_finished(event.event)" >
							<td>{{ event.user}}</td>
							<td><i class="fa fa-clock-o" 
									data-toggle="tooltip"
									data-original-title="{{ formatDate(event.date) }}" ></i>
							 {{  humanDate(event.date) }}</td>
							<td>{{ get_name_event(event.event) }}</td>
							<td><div ng-bind-html="event.notes"></div></td>
						</tr>
					</tbody>

				</table>
			</div>
		</div>
	</div>
</div>
