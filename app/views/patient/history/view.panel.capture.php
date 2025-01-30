
<div class="panel panel-default panel-custom">
	<div class="panel-heading" ng-cloak>
		<div class="row">
			<div class="col-xs-6 col-md-6"><label>Historial del paciente: {{ patient.name }} {{ patient.middle_name }} {{ patient.last_name }} </label> </div>
			<div class="col-xs-6 col-md-6 text-right">
				<a data-toggle="tooltip" title="Patient chart" data-placement="bottom" class="btn btn-xs btn-info" ng-href="/patient/chart/{{ patient.id }}"> <i class="icon-folder-plus"></i>	</a>
			</div>
		</div>
	</div>
	<div class="panel-body" style="font-weight:normal;">

			<div class="col-lg-4" ng-repeat="(key, val) in catalog_history">
				<table class="table table-condensed table-hover">
					<thead>
						<tr>
							<th class="col-md-4"></th>
							<th class="col-md-4 well well-sm">Paciente</th>
							<th class="col-md-4 well well-sm">Familia</th>
						</tr>
					</thead>
					
					<tbody ng-repeat="item in catalog_history[key]" ng-cloak >
						<tr  class="text-center well well-sm" >
							<td class="" colspan="" > <b>{{ item.group }}</b> </td>
							<td class="well well-sm"></td>
							<td class="well well-sm"></td>
						</tr>
						<tr class="">
							<td colspan="3">
								<table class="table" style="border:0px solid red;padding:0px">
									<tbody ng-repeat="history in item.data">
										<tr>
											<td class="col-md-4"  style="border:0px solid red;padding:0px ">{{ history.title }}</td>
											<td class="col-md-4" style="font-size:10px;padding:0px;">
												<label class="radio"  >
													<input ng-model="history.patient" type="radio" value="">
													<span style="padding:5px !important;">Desconocido</span>
												</label>
												<label class="radio">
													<input ng-model="history.patient" type="radio" value="Yes" >
													<span style="padding:5px !important;">Si</span>
												</label>
												<label class="radio">
													<input ng-model="history.patient" type="radio" value="No" >
													<span style="padding:5px !important;">No</span>
												</label>
											</td>
											<td class="col-md-4" style="font-size:10px;padding:0px;">
												<label class="radio"  >
													<input ng-model="history.family" type="radio" value="">
													<span style="padding:5px !important;">Desconocido</span>
												</label>
												<label class="radio">
													<input ng-model="history.family" type="radio" value="Yes" >
													<span style="padding:5px !important;">Si</span>
												</label>
												<label class="radio">
													<input ng-model="history.family" type="radio" value="No" >
													<span style="padding:5px !important;">No</span>
												</label>
											</td>
										</tr>
										<tr ng-show="(history.show_comments==1 && (history.family=='Yes' || history.patient=='Yes') )">
											<td colspan="3" style="padding: 0px;">
												<input type="text" class="form-control input-xs" ng-model="history.comments"  placeholder="Comments for {{ history.title}}" />
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						
					</tbody>
				</table>
			</div>
	</div>
	<div class="panel-footer text-right">
		<button data-target="#patient-history-modal-confirm-history" data-toggle="modal" type="button" class="btn btn-success btn-sm submit" >
			Confirmar historial
		</button>
		<a ng-show="patient.recorded_history" href="/patient/history/{{patient.id}}" class="btn btn-default btn-sm" >
			Cancel
		</a>
	</div>
</div>

