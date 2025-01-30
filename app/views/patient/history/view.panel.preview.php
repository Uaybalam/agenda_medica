
<div class="panel panel-default panel-custom" >
	<div class="panel-heading" >
		<div class="row">
			<div class="col-xs-6 col-md-6"><label>Patient history: {{ data.patient.full_name }} </label> </div>
			<div class="col-xs-6 col-md-6 text-right">
				<a data-toggle="tooltip" title="Editar historial médico" data-placement="bottom" class="btn btn-xs btn-info" ng-href="/patient/history/capture/{{ data.patient.id }}"> <i class="fa fa-pencil"></i>	Editar </a>
				<a data-toggle="tooltip" title="Expediente del paciente" data-placement="bottom" class="btn btn-xs btn-info" ng-href="/patient/chart/{{ data.patient.id }}"> <i class="icon-folder-plus"></i>	</a>
			</div>
		</div>
	</div>
	<div class="panel-body form-horizontal" style="padding-top: 12px;">
		
		<div class="col-md-6">
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Fecha de Captura</label>
				<div class="col-md-9">
					 <input type="text" class="form-control" readonly="true" ng-value="data.patient.date_capture" />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Usuario que captura</label>
				<div class="col-md-9">
					 <input type="text" class="form-control" readonly="true" ng-value="data.patient.user_capture" />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Cirugías</label>
				<div class="col-md-9">
					<textarea  class="form-control" readonly="true"  >{{data.patient.recorded_history_surgeries}}</textarea>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Medicación actual</label>
				<div class="col-md-9">
					 <textarea rows="3" class="form-control" readonly="true"  >{{data.patient.recorded_history_current_medications}}</textarea>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Comentarios</label>
				<div class="col-md-9">
					 <textarea   rows="3" class="form-control" readonly="true"  >{{data.patient.recorded_history_comments}}</textarea>
				</div>
			</div>
		</div>
	
		
		<div class="clearfix"></div>
	
		<div class="col-lg-4" ng-repeat="(pKey, pVal) in data.history_information.data | groupBy: 'position' ">
			<table class="table  table-condensend " >
				<thead>
					<tr class="">
						<td class="col-md-4 well well-sm "></td>
						<th class="col-md-4 well well-sm text-center">Paciente</th>
						<th class="col-md-4 well well-sm text-center">Familia</th>
					</tr>
				</thead>
				
				<tbody  ng-repeat="(gKey, gVal) in data.history_information.data  | filter: {position:pKey}  | groupBy: 'group_history' " >
						<tr  >
							<th class="well well-sm" style="padding:3px 7px;">{{ gKey }}</th>
							<th class="well well-sm"></th>
							<th class="well well-sm"></th> 
						</tr>
						<tr>
							<td colspan="3">
								<table class="table table-condensend table-hover" style="border:0px solid red;padding:3px 7px">
									<tbody ng-repeat="history in data.history_information.data | filter: {position:pKey, group_history:gKey}">
										<tr>
											<td style="padding:3px 7px;" class="col-md-4" >{{ history.title }}</td>
											<td class="text-center col-md-4">
												<span ng-class="history.patient=='Yes' ? 'text-danger text-bold' : '' ">{{ options[history.patient] }}</span>
											</td>
											<td class="text-center col-md-4">
												<span ng-class="history.family=='Yes' ? 'text-danger text-bold' : '' ">{{ options[history.family ]}}</span>
											</td>
										</tr>
										<tr ng-show="history.comments">
											<td colspan="3" >
												<span  style="font-size:12px" class="label label-warning">{{history.comments}}</span>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>

						<!--
						<tr ng-repeat="history in data.history_information.data | filter: {position:pKey, group_history:gKey}  ">
							<td>{{ history.title }}</td>
							<td class="text-center">
								<span ng-class="history.patient=='Yes' ? 'text-danger text-bold' : '' ">{{ history.patient }}</span>
							</td>
							<td class="text-center">
								<span ng-class="history.family=='Yes' ? 'text-danger text-bold' : '' ">{{ history.family }}</span>
							</td>
						</tr>
						-->
				</tbody>
				
			</table>
		</div>
		
	</div>
</div>

