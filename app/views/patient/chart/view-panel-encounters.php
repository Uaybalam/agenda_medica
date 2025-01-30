
<div class="panel panel-default custom-widget" >
	<div class="panel-heading" style="font-size:12px;padding-top:4px;padding-bottom:4px;">
		<div class="row">
			<div class="col-xs-3 col-sm-3"><label>Consultas  <span ng-cloak   class="badge" data-placement="right" data-toggle="tooltip" title="Total encounters" >{{ (data.encounters|filter:action_vitals.range_filter).length }}</span> </label>  </div>
			<div class="col-xs-9 col-sm-9 text-right">
				<!-- -->
				<a ng-show="data.encounters.length" data-placement="bottom" target="_blank" ng-href="/encounter/pdf/{{ data.patient.id }}/patient/?{{action_vitals.data_filter()}}" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="Imprimir consutas (Firmadas)"> <i class="fa fa-print"></i> </a>
				<div class="remark" ng-show="data.last_appointment>0"  >
					<button ng-click="action_vitals.open( data.last_appointment )" type="button" class="btn btn-success btn-xs"> <i class="fa fa-plus" aria-hidden="true"></i> Crear Consultas </button>
				</div>
				<button ng-click="list_medications()" type="button" class="btn btn-success btn-xs"> <i class="fa fa-medkit" aria-hidden="true"></i> Medicamentos </button>
				<button ng-click="list_diagnosis()" type="button" class="btn btn-success btn-xs"> <i class="fa fa-stethoscope" aria-hidden="true"></i> Diagnóstico </button>
			</div>
		</div>
	</div>
	<div class="panel-body"    style="height:200px;font-size:12px;padding: 5px;overflow-y:auto;" >
		<table ng-cloak style="margin:0px;"  class="table table-hover-app table-condensend table-bordered"  >
			<thead>
				<tr class="well">
					<td class=""><b>ID</b></td>
					<td class=""><b>Solicitud</b></td>
					<td class="col-md-2"><b>Creado el</b></td>
					<td class="col-md-1"><b>Estatus</b></td>
					<td class="col-md-9"><b>Motivo de consulta</b></td>
				</tr>
			</thead>
			<tbody>
				<tr ng-cloak dir-paginate="encounter in data.encounters| filter:action_vitals.range_filter | orderBy:sortKey:reverse | itemsPerPage:5 " pagination-id="dirpagination_encounters" >
					<td >
						<span ng-show="['nurse','reception'].indexOf('<?= $this->current_user->access_type; ?>')>=0" >
							<a  class="link" ng-href="/encounter/detail/{{ encounter.id }}/nurse" > {{encounter.id}}</a>
						</span>
						<span ng-show="['root','admin','medic','manager','billing'].indexOf('<?= $this->current_user->access_type; ?>')>=0" >
							<a  class="link" ng-href="/encounter/detail/{{ encounter.id }}" > {{encounter.id}}</a>
						</span>
						<span ng-show="['secretary'].indexOf('<?= $this->current_user->access_type; ?>')>=0" >
							{{encounter.id}}
						</span>
					</td>
					<td>
						<a  ng-href="/encounter/request/{{ encounter.id }}" ng-show="encounter.status==2" title="Requests" data-placement="bottom" data-toggle="tooltip" class="btn btn-info btn-xs fa fa-archive" ></a>
					
					</td>
					<td >{{ encounter.date }} </td>
					<td ><span ng-style="(encounter.status==1) ? {'font-weight':'bold'} : {}">{{ (encounter.status==1) ? 'En cuarto' : 'Firmado' }}</span> </td>
					<td >
						<button title="Change room" data-toggle="tooltip" type="button" ng-click="action_appointment.open( encounter , '#appointment-modal-room' )"  ng-show="encounter.status==1" class="btn btn-success btn-xs fa fa-map-marker"> {{ encounter.room }}</button>
						{{ encounter.chief_complaint }}
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="panel-footer" style="padding-top:4px; padding-bottom:0px;height:42px;">
		<div class="row">
			<div class="col-md-4">
				<div class="input-group" >
				    <input placeholder="Del" ng-model="default.filter.encounter_date_from" readonly="true" type="text" class="create-datepicker input-xs form-control">
				    <span class="input-group-addon" style="padding:0px;"></span>
				    <input placeholder="A" ng-model="default.filter.encounter_date_to" readonly="true" type="text" class="create-datepicker input-xs form-control">
				</div>
			</div>
			<div class="col-md-8 text-right dirpagination-xs" >
				<dir-pagination-controls
				    max-size="3"
				    direction-links="true"
				    boundary-links="false" 
				    auto-hide="false" 
				    pagination-id="dirpagination_encounters" >
				</dir-pagination-controls>
			</div>
		</div>
	</div>
</div>

