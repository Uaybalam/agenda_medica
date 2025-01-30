<div class="panel panel-default custom-widget" >
	<div class="panel-heading">
		<div class="row">
			<div class="col-xs-7 col-sm-7 col-md-7">
				<label >Historial de Salud del Paciente</label>
			</div>
			<div class="col-xs-5 col-sm-5 col-md-5 text-right" >
				<a ng-cloak  ng-show="data.patient.recorded_history=='1'" target="_blank" ng-href="/patient/history/pdf/{{ data.patient.id }}" data-placement="bottom" class="btn btn-warning btn-xs" data-toggle="tooltip" title="Imprimir Historia de Salud del Paciente"> <i class="fa fa-print"></i> </a>
				<a ng-cloak  ng-href="/patient/history/capture/{{ data.patient.id}}" ng-show="data.patient.recorded_history=='0'" class="btn btn-info btn-xs"> Registrar historial </a>
				<a ng-cloak  ng-href="/patient/history/{{ data.patient.id}}" ng-show="data.patient.recorded_history=='1'" class="btn btn-info btn-xs"> Historial </a>
			</div>
		</div>
	</div>
	<div class="panel-body" style="height:170px;">
		<table ng-cloak  class="table table-hover-app table-condensend table-bordered" ng-show="data.patient_history.length" >
			<thead>
				<tr>
					<th style="text-align: left;">Titulo</th>
					<th style="text-align: left;">Paciente</th>
					<th style="text-align: left;">Familia</th>
				</tr>
			</thead>
			<tbody >
				<tr ng-repeat="history in data.patient_history">
					<td>{{ history.title }}</td>
					<td>{{ options[history.patient ]}}</td>
					<td>{{ options[history.family] }}</td>
				</tr>
			</tbody>
		</table>
		<p ng-cloak  ng-show="data.patient_history.length===0 && data.patient.recorded_history=='1'" class="text-success">
			No se registran problemas en el historial
		</p>
		<p ng-cloak  ng-hide="data.patient.recorded_history=='1'" class="text-danger">
			En la historia del paciente no hay enfermedades actuales
		</p>
	</div>
</div>