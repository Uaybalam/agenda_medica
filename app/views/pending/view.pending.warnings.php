<div class="panel panel-default panel-custom">
	<div class="panel-heading">
		<div class="row">
			<div class="col-xs-6 col-md-6">
				<label>Advertencias pendientes <span ng-cloak data-toggle="tooltip" data-placement="right" title="Total de advertencias pendientes" class="badge"> {{ data.warnings.length }}</span></label>				
			</div>
		</div>
	</div>
	<div class="panel-body" >
		<table class="table table-condensed table-bordered table-hover" >
			<thead>
				<tr>
					<th class="col-md-1">
						<input type="text" class="form-control input-sm" placeholder="Estatus"  readonly="true" />
					</th>
					<th class="col-md-1">
						<div class="input-group input-group-sm">
							<input type="text" ng-model="search.create_at"  class="form-control" placeholder="Creado el" />
	                        <span class="input-group-btn">
	                            <a ng-click="ngHelper.sort('create_at')" class="btn btn-default btn-sm" >
	                            	<i class="fa " ng-class="ngHelper.sortClass('create_at')"></i>
	                            </a>
	                        </span>
	                    </div>
					</th>
					<th class="col-md-2">
						<div class="input-group input-group-sm">
							<input type="text" ng-model="search.user_create"  class="form-control" placeholder="Creado por" />
	                        <span class="input-group-btn">
	                            <a ng-click="ngHelper.sort('user_create')" class="btn btn-default btn-sm" >
	                            	<i class="fa " ng-class="ngHelper.sortClass('user_create')"></i>
	                            </a>
	                        </span>
	                    </div>
					</th>
					<th class="col-md-2">
						<div class="input-group input-group-sm">
							<input type="text" ng-model="search.patient"  class="form-control" placeholder="Paciente" />
	                        <span class="input-group-btn">
	                            <a ng-click="ngHelper.sort('patient')" class="btn btn-default btn-sm" >
	                            	<i class="fa " ng-class="ngHelper.sortClass('patient')"></i>
	                            </a>
	                        </span>
	                    </div>
					</th>
					<th class="col-md-6">
						<input type="text" class="form-control input-sm" placeholder="Descripción/Notas"  readonly="true" />
					</th>
				</tr>
			</thead>
			<tbody ng-cloak >
				<tr dir-paginate="warning in data.warnings | filter:search  | orderBy:sortKey:reverse | itemsPerPage:15" >
					<td>{{ data.available_status[warning.status] }}</td>
					<td> <i class="fa fa-clock-o" data-toggle="tooltip" title="{{ ngHelper.formatDate(warning.create_at )}}"></i> {{ ngHelper.humanDate(warning.create_at ) }}</td>
					<td>{{ warning.user_create }}</td>
					<td>
						<a ng-href="/patient/chart/{{ warning.patient_id }}" > <i class="icon-folder-plus"></i> {{ warning.patient }}</a>
					</td>
					<td>{{ warning.description }}</td>
				</tr>
				<tr >
					<td ng-show="(!data.warnings.length)" class="text-center" colspan="5">
						<h3>No responde a los resultados de advertencia </h3>
						<p>Agregue una nueva respuesta en: <b>Demograficos del paciente</b>, en el panel de <b>Alertas</b> y presione el botón "más". Marque la opción "Sí" en <b>Solicitar respuesta</b></p>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="panel-footer ">
		<div class="row">
			
			<div class="col-md-3" style="font-size: 12px;">
				<p>Estatus: <b>Enviar solicitud</b> (Proveedor y administrador)</p>
			</div>
			<div class="col-md-3" style="font-size: 12px;">
				<p>Estatus: <b>Responder solicitud</b> (Secretaria y asistente medico)</p>
			</div>
			<div class="col-md-6 text-right">
				<dir-pagination-controls 
				    max-size="5"
				    direction-links="true"
				    boundary-links="false" >
				</dir-pagination-controls>
			</div>
		</div>
	</div>
</div>