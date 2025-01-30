
<div class="panel panel-default custom-widget" ng-cloak > 
	<div class="panel-heading"> 
		<div class="row"> 
			<div class="col-xs-6 col-md-6"> 
				<label for="">Acerca del paciente <span title="Patient id" data-placement="bottom" data-toggle="tooltip">({{ data.patient.id}})</span></label> 
			</div> 
			<div class="col-xs-6 col-md-6 text-right"> 
				<a  title="Imprimir demograficos" data-placement="bottom" data-toggle="tooltip" ng-href="/patient/pdf/{{ data.patient.id }}" target="_blank" class="btn btn-warning btn-xs"> <i class="fa fa-print"></i> </a> 
				<a title="Crear Cita" data-placement="bottom" data-toggle="tooltip" ng-href="/appointment/create?patient_id={{ data.patient.id }}" class="btn btn-info btn-xs"> <i class="fa fa-calendar-plus-o"></i> </a>
				<a  title="Ver Citas" data-placement="bottom" data-toggle="tooltip" ng-href="/patient/appointments/{{ data.patient.id }}"  class="btn btn-info btn-xs"> <i class="fa fa-book"></i> </a> 
				<?php if(in_array($this->current_user->access_type,['root','admin','billing','nurse','medic'])):?>
				<a  title="Historia Clínica" data-placement="bottom" data-toggle="tooltip" ng-href="/patient/chart/{{ data.patient.id }}"  class="btn btn-info btn-xs"> <i class="icon-folder-plus"></i> </a>
				<?php endif; ?> 
				<button  title="Editar" data-placement="bottom" data-toggle="tooltip" ng-click="action_about.open('#patient-detail-modal-patient-detail-about');" class="btn btn-success btn-xs"> <i class="fa fa-pencil"></i> </button> 
			</div> 
		</div> 
	</div> 
	<div class="panel-body" style="height:220px;font-size:12px;"> 
		<div class="col-md-6">
			<table class="table table-condensend table-hover-app table-bordered">
				<tr>
					<th class="col-xs-4 col-md-4">Nombres</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ data.patient.name }} {{ data.patient.middle_name }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4">Apellidos</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ data.patient.last_name }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4"  ng-class="!data.patient.gender ? 'text-danger' : '' ">Genero</th>
					<td class="col-xs-4 col-md-4" >{{ data.patient.gender == "Male" ? "Masculino" : "Femenino" }}</td>
					<th class="col-xs-3 col-md-3" >Estado civil</th>
					<td class="col-xs-3 col-md-5" >{{ data.settings_marital_status[data.patient.marital_status] }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4">Teléfono</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ ngHelper.parsePhone(data.patient.phone) }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4">Descripción de teléfono</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ data.patient.phone_memo }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4">Teléfono alterno</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ ngHelper.parsePhone(data.patient.phone_alt) }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4">Descripción de teléfono alternativo</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ data.patient.phone_alt_memo }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4" ng-class="!data.patient.date_of_birth ? 'text-danger' : '' ">Fecha de Naciemiento</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ data.patient.date_of_birth }} <span class="pull-right text-success">{{ data.patient.age}}</span></td>
				</tr>
			</table>
		</div>
		<div class="col-md-6">
			<table class="table table-condensend table-hover-app table-bordered">
				<tr>
					<th class="col-xs-4 col-md-4" ng-class="!data.patient.how_found_us ? 'text-danger' : '' ">¿Como nos encontraste?</th>
					<td class="col-xs-8 col-md-8">{{ data.patient.how_found_us }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4">Email</th>
					<td class="col-xs-8 col-md-8">{{ data.patient.email }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4">Etnicidad</th>
					<td class="col-xs-8 col-md-8">{{ data.patient.ethnicity }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4">Tipo Sanguineo</th>
					<td class="col-xs-8 col-md-8">{{ data.patient.blood_type }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4">Idioma</th>
					<td class="col-xs-8 col-md-8">{{ data.patient.language }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4" ng-class="!data.patient.interpreter_needed ? 'text-danger' : '' ">¿Requiere Interprete?</th>
					<td class="col-xs-8 col-md-8">{{ data.patient.interpreter_needed }}</td>
				</tr>
				<!--<tr>
					<th class="col-xs-4 col-md-4" ng-class="!data.patient.advanced_directive_offered ? 'text-danger' : '' ">Directive offered</th>
					<td class="col-xs-8 col-md-8">{{ data.patient.advanced_directive_offered }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4" ng-class="!data.patient.advanced_directive_taken ? 'text-danger' : '' ">Directive taken</th>
					<td class="col-xs-8 col-md-8">{{ data.patient.advanced_directive_taken }}</td>
				</tr> -->
				<tr>
					<th class="col-xs-4 col-md-4" >Descuento de empresa</th>
					<td class="col-xs-8 col-md-8">{{ data.patient.discount_type }}</td>
				</tr>
			</table>
		</div>
	</div>
</div>
