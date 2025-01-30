<div id="tab-chart" class="tab-pane fade">
	<table  class="table table-bordered table-condensed table-hover">
		<thead>
			<th style="min-width: 80px;"></th>
			<th class="col-md-2">
				<div class="input-group input-group-sm">
					<input type="text" ng-model="chart_filter.created_at" ng-change="pagination.chart.getData(1)"  class="form-control" placeholder="Fecha de subida" />
                    <span class="input-group-btn">
                        <a ng-click="pagination.chart.sortData('created_at')" class="btn btn-default btn-sm" >
                        	<i class="fa " ng-class="pagination.chart.sortClass('created_at')"></i>
                        </a>
                    </span>
                </div>
			</th>
			<th class="col-md-2">
				<input type="text" ng-model="chart_filter.patient"  ng-change="pagination.chart.getData(1)" class="form-control input-sm" placeholder="Paciente" />
			</th>
			<th class="col-md-2">
				<input type="text" ng-model="chart_filter.type_str"  ng-change="pagination.chart.getData(1)" class="form-control input-sm" placeholder="Tipo de documento" />
			</th>
			<th class="col-md-3">
				<input type="text" ng-model="chart_filter.title" ng-change="pagination.chart.getData(1)" class="form-control input-sm" placeholder="Titulo de documento" />
			</th>
			<th>Cargado por</th>
		</thead>	
		<tbody ng-cloak > 
			<tr  dir-paginate="result in pagination.chart.result_data | orderBy:sortKey:reverse | itemsPerPage:15" total-items="pagination.chart.total_count" pagination-id="pagination_patientchart">
				<td class="text-center">	
					<button data-toggle="tooltip" title="Vista previa y Completar" class="btn btn-success btn-xs" ng-click="action_check_document.open( result, 'chart' )" ><i class="fa fa-eye"></i> Revisar </button>
				</td>
				<td> <i class="fa fa-clock-o" data-toggle="tooltip" title="{{ngHelper.formatDate(result.create_at)}}"></i> {{ ngHelper.normalDate(result.create_at) }} </td>
				<td> <i data-toggle="tooltip" title="Fecha de nacimiento {{ result.patient_dob}}" class="fa fa-calendar"></i> <a ng-href="/patient/detail/{{ result.patient_id }}"> {{ result.patient }}   </a>  </td>
				<td>{{ result.type }}</td>
				<td>{{ result.title }}</td>
				<td>{{ result.created_by }}</td>
			</tr>
		</tbody>
	</table>

	<div class="text-right">
		<dir-pagination-controls 
			max-size="8" 
			direction-links="true" 
			boundary-links="false" 
			on-page-change="pagination.chart.getData(newPageNumber)"  pagination-id="pagination_patientchart"  ></dir-pagination-controls>
	</div>
</div>