<div class="row form-horizontal">

	<div class="col-lg-12" >
		<div class="form-group">
			<label class="col-md-3 control-label">Fecha</label>
			<div class="col-md-6">
				<input class="form-control" type="text" readonly="true" ng-model="default.communicate.date" />
			</div>
		</div>
		<table class="table table-condensed table-bordered table-hover" style="font-size:12px;">
			<thead>
				<tr>
					<th class="col-md-2 well">Hora</th>
					<th class="col-md-5 well">Paciente</th>
					<th class="col-md-5 well">Tipo de visita</th>
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat="apt in data.appointments">
					<td class="col-md-2">{{ apt.time}}</td>
					<td class="col-md-5">{{ apt.patient}}</td>
					<td class="col-md-5">{{ apt.visit_type }}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>