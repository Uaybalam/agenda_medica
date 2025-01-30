<input type="hidden" id="prevent-default-loading" value="1" />
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default custom-widget">
			<div class="panel-heading">
				<div class="row">
					<div class="col-sm-4"> <label >Información del paciente</label></div>
					<div class="col-sm-8 text-right">
						<button data-toggle="modal" data-target="#encounter-detail-modal-patient-healthhistory" class="btn btn-success btn-xs">Historial médico</button>
						<button data-toggle="modal" data-target="#encounter-detail-modal-patient-activehx" class="btn btn-success btn-xs">Historial médico activo</button>
						<a title="Expediente del paciente" data-placement="bottom" data-toggle="tooltip" class="btn btn-info btn-xs" ng-href="/patient/chart/{{data.patient.id}}" > <i class="icon-folder-plus"></i></a>
						<a title="Detalle del paciente" data-placement="bottom" data-toggle="tooltip" class="btn btn-info btn-xs" ng-href="/patient/detail/{{data.patient.id}}" ><i class="fa fa-user "></i></a>
					</div>
				</div>
			</div>
			<div class="panel-body body-normal">
				<div class="row">
					<div class="col-md-6">
						<table ng-cloak  class="table table-hover-app table-condensend table-bordered">
							<tbody>
								<tr>
									<th class="col-xs-4 col-md-4">ID del paciente</th>
									<td class="col-xs-8 col-md-8">{{ data.patient.id }}</td>
								</tr>
								<tr>
									<th class="col-xs-4 col-md-4">Paciente</th>
									<td class="col-xs-8 col-md-8">{{ data.patient.name +' '+data.patient.middle_name+' '+data.patient.last_name}}</td>
								</tr>
								<tr>
									<th class="col-xs-4 col-md-4">Seguro</th>
									<td class="col-xs-8 col-md-8">{{ data.encounter.insurance_title }}</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-md-6">
						<table ng-cloak  class="table table-hover-app table-condensend table-bordered">
							<tbody>
								<tr>
									<th class="col-xs-4 col-md-4">Edad (Fecha de naciemiento)</th>
									<td class="col-xs-8 col-md-8">{{ data.patient.age }} <span class="text-opacity">({{data.patient.date_of_birth}})<span> </td>
								</tr>
								<tr>
									<th class="col-xs-4 col-md-4">Alergias</th>
									<td class="col-xs-8 col-md-8 ">  <span style="margin:2px;"  ng-repeat="name in data.patient.prevention_allergies.split(',')"
								class="label" ng-class="name==='NKDA' ? 'label-success' : 'label-danger'">{{ name }}</span> </td>
								</tr>
								<tr>
									<th class="col-xs-4 col-md-4">Numero de seguro</th>
									<td class="col-xs-8 col-md-8">{{ data.encounter.insurance_number }}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row"  ng-cloak>
	<div ng-show="data.encounter.status==1" class="col-md-3">
		<div class="panel panel-default custom-widget">
			<div class="panel-heading text-center">
				<b>Actiones de consultas</b>
			</div>
			<div class="panel-body body-normal">
				<button ng-click="action_vitals.open()" class="btn btn-success btn-encounters">
					Signos vitales
				</button>
				<button ng-click="action_illness.open()"  class="btn btn-success btn-encounters">
					Historial de enfermedades actuales
				</button>
				<button ng-click="action_physicalexam.open()"  class="btn btn-success btn-encounters">
					Examen físico
				</button>
				<button ng-click="action_diagnosis.open()" class="btn btn-success btn-encounters">
					Diagnostico
				</button>
				<button ng-click="action_medication.open()" class="btn btn-success btn-encounters">
					Medicamentos
				</button>
				<button ng-click="action_results.open()" class="btn btn-success btn-encounters">
					Solicitudes
				</button>
				<button ng-click="action_referrals.open()" class="btn btn-success btn-encounters">
					Derivaciones
				</button>
				<button ng-click="action_education.open()" class="btn btn-success btn-encounters">
					Educación
				</button>
				<button ng-disabled="!data.encounter_child.id?true:false" ng-click="action_childphysical.open()" class="btn btn-success btn-encounters">
					Examen físico pediátrico
				</button>
			</div>
		</div>
	</div>
	<div ng-class="(data.encounter.status == 1) ? 'col-md-9' : 'col-lg-12'"  >
		<div class="panel panel-default custom-widget">
			<div class="panel-heading">
				<div class="row">
					<div class="col-sm-4">
						<label style="margin-top:5px;" >General information</label>
					</div>
					<div class="col-sm-8 text-right">
						<button 
							data-toggle="modal" 
							data-target="#billing-modal-create" 
							ng-show="data.encounter.status==2 && data.encounter.has_insurance==0" 
							type="button" ng-click="createBill()" class="btn btn-success btn-xs"> Crear facturación 
						</button>
						<a 
							ng-show="data.encounter.status==2" 
							target="_blank" 
							ng-href="/encounter/pdf/{{ data.encounter.id }}/encounter" 
							data-placement="bottom" class="btn btn-warning btn-xs" data-toggle="tooltip" title="Ver PDF"> 
							<i class="fa fa-print"></i> 
						</a>

						<a 
						ng-href="/encounter/pdf/{{ data.encounter.id }}/prescription" 
							ng-show="data.encounter.status==2" 
							title="Generar receta" 
							target="_blank" 
							class="btn btn-info btn-xs">
							<i class="fa fa-file-text-o" aria-hidden="true"></i> Generar receta 
						</a>
						<a 
							ng-href="/encounter/request/{{ data.encounter.id }}" 
							ng-show="data.encounter.status==2" 
							title="Solicitudes" 
							data-placement="bottom" 
							data-toggle="tooltip" 
							class="btn btn-info btn-xs">
							<i class="fa fa-archive" aria-hidden="true"></i> Solicitudes 
						</a>
						<button 
							type="button" 
							ng-click="open_compare()" class="btn btn-success btn-xs"> <i class="fa fa-compress" aria-hidden="true"></i> Comparación de consultas  </button>
						<button 
							type="button" 
							ng-click="open_activity('#encounter-detail-modal-activity')" class="btn btn-success btn-xs">Historial de consulta</button>
						<button 
							ng-show="data.encounter.status==2" type="button" ng-click="action_addendum.open()" class="btn btn-success btn-xs"> <i class="fa fa-plus-square" aria-hidden="true"></i> Addendum </button>
						<button 
							ng-show="data.encounter.status==1" type="button" ng-click="action_sign.open('#encounter-detail-modal-sign')" class="btn btn-success btn-xs"> <i class="fa fa-pencil" aria-hidden="true"></i> Firmar consulta</button>
					
					</div>
				</div>
			</div>
			<?php $this->template->render_view('encounter/view.encounter.body.content'); ?>
		</div>
	</div>
</div>

