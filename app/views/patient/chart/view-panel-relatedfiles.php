<div class="panel panel-default custom-widget" id="view-panel-relatedfiles">
	<div class="panel-heading">
		<div class="row">
			<div class="col-xs-6 col-md-6">
				<label for="">Archivos relacionados <span ng-cloak class="badge" data-placement="right" data-toggle="tooltip" title="Total de archivos"> {{ related_files_result.length }}</span></label>
			</div>
			<div class="col-xs-6 col-md-6 text-right">
				<button  data-toggle="tooltip" data-placement="bottom" title="Descargar archivos" target="_blank" ng-click="action_file.download_all(related_files_result)" class="btn btn-warning btn-xs"> <i class="fa fa-print"></i> <span ng-cloak class="badge" data-placement="right" data-toggle="tooltip" title="Total de archivos"> {{ related_files_result.length }}</span></button>
				<button  data-toggle="tooltip" data-placement="bottom" title="Agregar archivos" target="_blank" ng-click="action_file.open('#patient-chart-modal-related-file');" class="btn btn-success btn-xs"> <i class="fa fa-plus"></i> </button>

			</div>
		</div>
	</div>
	<div class="panel-body"  >
		<table ng-cloak class="table table-hover-app table-condensend table-bordered" style="font-size:12px;">
			<tbody>
				<tr class="well">
					<td class="col-md-8"><input placeholder="Titulo" class="form-control input-xs" type="text" ng-model="default.filterTitle.title" /></td>
					<td class="col-md-4">
						<select class="form-control input-xs" ng-model="default.filter.related_file_type"  >
							<option value="0" selected="true" >Todos los tipos</option>
							<option ng-repeat="(key, val) in data.catalog_related_file_types">{{val }}</option>
						</select>
					</td>
				</tr>
				<tr ng-repeat="file in data.related_files | filter:default.filterTitle  | filter:action_file.special_filter as related_files_result ">
					<td>
						<span class="label label-default">{{ ngHelper.normalDate(file.create_at)}}</span>
						<a href="#" ng-click="action_file.preview($event, file)"> {{ file.title }} </a>
					</td>
					<td>{{ file.type }}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>