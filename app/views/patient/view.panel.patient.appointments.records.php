<div class="panel panel-default custom-widget" style="font-size:12px;">
	<div class="panel-heading">
		<div class="row">
			<div class="col-xs-6 col-sm-6">
				<label for="" ng-cloak >Citas del paciente {{ data.patient.full_name }} ( {{ data.patient.date_of_birth}} ) <span class="badge" data-placement="right" data-toggle="tooltip" title="Total appointments">{{ (data.appointments|filter:search|filter:specialFilter).length }}</span></label>	
			</div>
			<div class="col-xs-6 col-sm-6 text-right" >
				<a  title="Expediente del paciente" data-placement="bottom" data-toggle="tooltip" ng-href="/patient/chart/{{ data.patient.id }}"  class="btn btn-info btn-xs"> <i class="icon-folder-plus"></i> </a> 
				<a  title="Demograficos del paciente" data-placement="bottom" data-toggle="tooltip" class="btn btn-info btn-xs" ng-href="/patient/detail/{{data.patient.id}}"  ><i class="fa fa-user "></i> </a>
			</div>
		</div>
	</div>
	<div class="panel-body" style="min-height:580px;">
		<table class="table table-hover table-condensed table-bordered" >
			<thead>
				<tr>
					<td class="col-xs-1"><input class="form-control input-sm" placeholder="Actions"  disabled="true" /></td>
					<td class="col-xs-3">
						<div class="input-group input-group-sm">
                           	<input type="text"  ng-change="appPagination.getData(1)" class="form-control input-sm"  ng-model="search.date" placeholder="Fecha" />
                            <span class="input-group-btn">
                                <a ng-click="sort('full_date_sort')" class="btn btn-default btn-sm" >
                                <i class="fa " ng-class="{'fa-angle-up':reverse,'fa-angle-down':!reverse}"></i></a>
                            </span>
                        </div>
					</td>
					<td class="col-xs-2">
						<select ng-model="filter_status" class="form-control input-sm" >
							<option value="">Todos los estatus</option>
							<option ng-repeat="status in data.array_status" value="{{status}}"> {{ status }} </option>
						</select>
					</td>
					<td class="col-xs-4"><input class="form-control input-sm" placeholder="Notas"  disabled="true" />	</td>
					<td class="col-xs-3"><input class="form-control input-sm" placeholder="Recordatorios"  disabled="true" /></td>
				</tr>
			</thead>
			<tbody>
				<tr ng-cloak dir-paginate="appt in data.appointments | filter:search | filter:specialFilter | orderBy:sortKey:reverse | itemsPerPage:15" >
					<td class="">	
						<a data-toggle="tooltip" title="Detalle" ng-href="/appointment/detail/{{ appt.id }}" class="btn btn-info btn-xs" > <i class="fa fa-calendar" ></i>  </a>
						<a data-toggle="tooltip" title="Solicitudes de consulta"	ng-show="appt.status >= 6 && appt.encounter_id"  class="btn btn-xs btn-info button-book" ng-href="/encounter/request/{{ appt.encounter_id }}"> <i class="fa fa-medkit"></i> </a>
					</td>
					<td>{{ appt.date }} {{ appt.time}}</td>
					<td>{{ appt.status_string }}</td>
					<td>{{ appt.notes }} <span class="label" ng-class="visitTypeClass(appt.visit_type)" style="font-size:10px;"> {{ appt.visit_type }} </span></td>
					<td>{{ appt.reminder_message }} </td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="panel-footer">
		<div class="row">
			<div class="col-lg-12 text-right">
				<dir-pagination-controls
				    max-size="5"
				    direction-links="true"
				    boundary-links="false" >
				</dir-pagination-controls>
			</div>
		</div>
	</div>
</div>