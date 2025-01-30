<div class="panel panel-default custom-widget"  >
	<div class="panel-heading">
		<div class="row">
			<div class="col-xs-6 col-sm-6">
				<label >Historia clínica activa</label>
			</div>
			<div class="col-xs-6 col-sm-6 text-right">
				<a target="_blank" ng-href="/patient/history-active/pdf/{{ data.patient.id }}" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="Imprimir historia clínica actual"> <i class="fa fa-print"></i> </a>
				<button type="button" data-toggle="tooltip" data-placement="bottom" title="Editar historia clínica actual" class="btn btn-success btn-xs" ng-click="action_activehistory.open(data.history_active)" > <i class="fa fa-edit"></i> </a>
			</div>
		</div>
	</div>
	<div class="panel-body"   style="height:170px;">
		<div class="table-responsive">
			<table ng-cloak class="table table-hover-app table-bordered"  >
				<tbody>
					<!--
					<tr ng-show="data.history_active.surgeries">
						<td class="col-md-4">Surgeries</td>
						<td class="col-md-8">{{ data.history_active.surgeries }} </td>
					</tr>
				-->
					<tr ng-show="data.history_active.psa">
						<td class="col-md-4">Antígeno Prostático Específico</td>
						<td class="col-md-8">{{ data.history_active.psa }} </td>
					</tr>
					<tr ng-show="data.history_active.last_influenza">
						<td class="col-md-4">Última influenza</td>
						<td class="col-md-8"> {{ data.history_active.last_influenza }} </td>
					</tr>
					<tr ng-show="data.history_active.last_chlamidia">
						<td class="col-md-4">Última clamidia</td>
						<td class="col-md-8">{{ data.history_active.last_chlamidia }} </td>
					</tr>
					<tr ng-show="data.history_active.last_physical">
						<td class="col-md-4">Última revisión física</td>
						<td class="col-md-8"> {{ data.history_active.last_physical }}</td>
					</tr>
					<tr ng-show="data.history_active.last_sha">
						<td class="col-md-4">Última SHA</td>
						<td class="col-md-8">{{ data.history_active.last_sha }}</td>
					</tr>
					<tr ng-show="data.history_active.last_cholesterol">
						<td class="col-md-4">Último colesteroll</td>
						<td class="col-md-8">{{ data.history_active.last_cholesterol }}</td>
					</tr>
					<tr ng-show="data.history_active.last_fobt">
						<td class="col-md-4">Último FOBT</td>
						<td class="col-md-8">{{ data.history_active.last_fobt }}</td>
					</tr>
					<tr ng-show="data.history_active.last_colonoscopy">
						<td class="col-md-4">Colonoscopia</td>
						<td class="col-md-8">{{ data.history_active.last_colonoscopy }}</td>
					</tr>
					<tr ng-show="data.history_active.last_sig">
						<td class="col-md-4">Último SIG</td>
						<td class="col-md-8">{{ data.history_active.last_sig }}</td>
					</tr>
					<tr ng-show="data.history_active.last_ecg">
						<td class="col-md-4">Último ECG</td>
						<td class="col-md-8">Fecha: <span class="text-opacity">({{ data.history_active.last_ecg }})</span> Normal: <span class="text-opacity">({{ data.history_active.last_ecg_normal }})</span> </td>
					</tr>
					<tr ng-show="data.history_active.last_ppd">
						<td class="col-md-4">Último PPD</td>
						<td class="col-md-8">Fecha: <span class="text-opacity">({{ data.history_active.last_ppd }})</span> Normal: <span class="text-opacity">({{ data.history_active.last_ppd_normal }})</span> </td>
					</tr>
					<tr ng-show="data.history_active.last_tetanous">
						<td class="col-md-4">Vacuna contra el tétanos</td>
						<td class="col-md-8">Fecha: <span class="text-opacity">({{ data.history_active.last_tetanous }})</span> Normal: <span class="text-opacity">({{ data.history_active.last_tetanous_normal }})</span> </td>
					</tr>
					<tr ng-show="data.history_active.last_pneumo">
						<td class="col-md-4">Última vacuna neumocócica</td>
						<td class="col-md-8">Fecha: <span class="text-opacity">({{ data.history_active.last_pneumo }})</span> Normal: <span class="text-opacity">({{ data.history_active.last_pneumo_normal }})</span> </td>
					</tr>
					<tr ng-show="action_activehistory.show_pregnancies()">
						<th colspan="2" class="text-center" style="text-align:center;"> Pregnancies </th>
					</tr>
					<tr ng-show="data.history_active.pregnancy_birth_control">
						<td class="col-md-4">Control natal</td>
						<td class="col-md-8">{{ data.history_active.pregnancy_birth_control }}</td>
					</tr>
					<tr ng-show="data.history_active.pregnancy_last_pap">
						<td class="col-md-4">Último PAP</td>
						<td class="col-md-8">{{ data.history_active.pregnancy_last_pap }} (Normal : {{ data.history_active.last_pap_normal }})</td>
					</tr>
					<tr ng-show="data.history_active.pregnancy_last_mamo">
						<td class="col-md-4">Última mamografía</td>
						<td class="col-md-8">{{ data.history_active.pregnancy_last_mamo }} (Normal : {{ data.history_active.last_mamo_normal }})</td>
					</tr>
					<tr ng-show="data.history_active.pregnancy_count_succesfull">
						<td class="col-md-4">Embarazos exitosos</td>
						<td class="col-md-8">{{ data.history_active.pregnancy_count_succesfull }}</td>
					</tr>
					<tr ng-show="data.history_active.pregnancy_count_cesarean">
						<td class="col-md-4">Cesáreas</td>
						<td class="col-md-8">{{ data.history_active.pregnancy_count_cesarean}}</td>
					</tr>
					<tr ng-show="data.history_active.pregnancy_count_abortions">
						<td class="col-md-4">Abortos/Abortos espontáneos</td>
						<td class="col-md-8">{{ data.history_active.pregnancy_count_abortions }}</td>
					</tr>
					<tr ng-show="action_activehistory.total_pregnancy(data.history_active)>0">
						<td class="col-md-4">Total</td>
						<td class="col-md-8">{{ action_activehistory.total_pregnancy(data.history_active) }}</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>