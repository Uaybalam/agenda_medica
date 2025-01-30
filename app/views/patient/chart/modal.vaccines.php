
<table class="table table-bordered table-condensed table-hover" style="font-size:12px;">
	<caption class="text-right">
		<a target="_blank" ng-href="/patient/vaccine/pdf/{{ data.patient.id }}" class="btn btn-warning btn-xs"> <i class="fa fa-print"></i></a>
	</caption>
	<thead>
		<tr>
			<th class="col-md-3">Vacuna</th>
			<th class="text-center">#</th>
			<th class="col-md-1" style="min-width: 90px;">Fecha de aplicación</th>
			<th class="col-md-1">Número de fabricación y lote</th>
			<th class="col-md-1">Fecha de vencimiento</th>
			<th class="col-md-1">Fecha de Declaración de Información sobre Vacunas</th>
			<th class="col-md-1">Subtitulo</th>
			<th class="col-md-1">Sitio</th>
			<th class="col-md-2">Administrado por</th>
			<th class="text-center">Interno</th>
		</tr>
	</thead>
	<tbody ng-repeat="vaccine in data.vaccines_data ">
		<tr>
			<td><input ng-change="action_vaccines.autosave($index)" placeholder="Others" ng-model="vaccine.title" class="form-control input-xs" type="text" ng-readonly="vaccine.edit_title ? false : true" />
			</td>
			<td class="text-center">{{ vaccine.number }}</td>
			<td> <input ng-change="action_vaccines.autosave($index)" ng-model="data.vaccines_data[$index].date_given" class="form-control input-xs create-datepicker-vaccines" type="text" /></td>
			<td> <input ng-change="action_vaccines.autosave($index)" ng-model="data.vaccines_data[$index].code" class="form-control input-xs" type="text"  /></td>
			<td><input ng-change="action_vaccines.autosave($index)" ng-model="data.vaccines_data[$index].exp_date" class="form-control input-xs" type="text"  /></td>
			<td><input ng-change="action_vaccines.autosave($index)" ng-model="data.vaccines_data[$index].vis_date" class="form-control input-xs" type="text"  /></td>
			<td>
				<select ng-show="action_vaccines.has_subtitle(vaccine.title)" ng-change="action_vaccines.autosave($index)" ng-model="data.vaccines_data[$index].subtitle" class="form-control input-sm">
					<option value="">No Seleccionada</option>
					<option  ng-repeat="(key, value) in action_vaccines.get_subtitles(vaccine.title)" value="{{ value }}">{{ value }}</option>
				</select>
			</td>

			<td> <input ng-change="action_vaccines.autosave($index)" ng-model="data.vaccines_data[$index].site"class="form-control input-xs" type="text"/></td>
			
			<td> <input placeholder="Oficina de doctor o clinica" ng-disabled="data.vaccines_data[$index].intern=='Yes'" ng-change="action_vaccines.autosave($index)" ng-model="data.vaccines_data[$index].administered_by" class="form-control input-xs" type="text"/></td>
			<td> 
				<label 
					class="btn btn-default btn-xs" 
					ng-model="data.vaccines_data[$index].intern"
					ng-class="data.vaccines_data[$index].intern=='Yes' ? 'active' : ''" 
					ng-click="action_vaccines.change_intern($index)"
				> Si </label>
			</td>
		</tr>
	</tbody>
	
</table>
