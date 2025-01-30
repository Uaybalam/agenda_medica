<div id="tab-results" class="tab-pane fade in active">
	<table  class="table table-bordered table-condensed table-hover">
		<thead>
			<th style="min-width: 80px;"></th>
			<th class="col-md-2">
				<div class="input-group input-group-sm">
					<input type="text" ng-model="result_filter.created_at" ng-change="pagination.results.getData(1)"  class="form-control" placeholder="Fecha de consulta" />
                    <span class="input-group-btn">
                        <a ng-click="pagination.results.sortData('created_at')" class="btn btn-default btn-sm" >
                        	<i class="fa " ng-class="pagination.results.sortClass('created_at')"></i>
                        </a>
                    </span>
                </div>
			</th>
			<th class="col-md-2">
				<input type="text" ng-model="result_filter.patient"  ng-change="pagination.results.getData(1)" class="form-control input-sm" placeholder="Paciente" />
			</th>
			<th class="col-md-2">
				<input type="text" ng-model="result_filter.type_str"  ng-change="pagination.results.getData(1)" class="form-control input-sm" placeholder="Tipo de documento" />
			</th>
			<th class="col-md-3">
				<input type="text" ng-model="result_filter.title" ng-change="pagination.results.getData(1)" class="form-control input-sm" placeholder="Titulo" />
			</th>
			<th>Recibido Por</th>
			<th>Recibido El</th>
		</thead>	
		<tbody ng-cloak > 
			<tr  dir-paginate="result in pagination.results.result_data | orderBy:sortKey:reverse | itemsPerPage:15" total-items="pagination.results.total_count" pagination-id="pagination_results">
				<td class="text-center">	
					<!--
					<button class="btn btn-success btn-xs" ng-click="action_results.open(result , $index)" ><i class="fa fa-edit"></i></button>
					<button class="btn btn-success btn-xs" ng-click="action_contact.open(result , $index)" ><i class="fa fa-comments-o"></i></button>
					-->
					<button data-toggle="tooltip" title="Preview and Done" class="btn btn-success btn-xs" ng-click="action_check_document.open( result, 'results' )" ><i class="fa fa-eye"></i> Check </button>
				</td>

				<td> <a ng-href="/encounter/request/{{result.encounter_id}}">{{ result.created_at }}</a></td>
				<td> <i data-toggle="tooltip" title="Fecha de nacimiento {{ result.date_of_birth}}" class="fa fa-calendar"></i> <a ng-href="/patient/detail/{{ result.patient_id }}"> {{ result.patient }}   </a>  </td>
				<td>{{ result.type_result }}</td>
				<td>{{ result.title }}</td>
				<td>{{ result.recive_nickname }}</td>
				<td>{{ ngHelper.formatDate(result.recive_date) }}</td>
			</tr>
		</tbody>
	</table>

	<div class="text-right">
		<dir-pagination-controls 
			max-size="8" 
			direction-links="true" 
			boundary-links="false" 
			on-page-change="pagination.results.getData(newPageNumber)"  pagination-id="pagination_results"  ></dir-pagination-controls>
	</div>
</div>