<div class="panel panel-default  panel-custom" >
	<div class="panel-heading">
		<div class="row">
			<div class="col-sm-6">
				<label>Lista de consultas <span class="badge" ng-cloak data-toggle="tooltip" data-placement="right" title="Total encounters">{{ appPagination.total_count.toLocaleString() }}</span></label>
			</div>
			<div class="col-sm-6 text-right">
				
			</div>
		</div>
	</div>
	<div class="panel-body" >
		<table class="table table-hover table-condensed table-bordered" >
			<thead>
				<tr>
					<th  class="col-xs-1 col-sm-1 col-md-1">
						<div class="input-group input-group-sm">
                           <input type="text"  ng-change="appPagination.getData(1)" class="form-control input-sm"  ng-model="filter.id" placeholder="ID" />
                            <span class="input-group-btn">
                                <a ng-click="appPagination.sortData('id')" class="btn btn-default btn-sm" >
                                <i class="fa " ng-class="appPagination.sortClass('id')"></i></a>
                            </span>
                        </div>
					</th>
					<th  class="col-xs-1 col-sm-1 col-md-1">
						<div class="input-group input-group-sm">
                            <input type="text" ng-change="appPagination.getData(1)" class="form-control input-xs"  ng-model="filter.date" placeholder="Mes-Año" />
                            <span class="input-group-btn">
                                <a ng-click="appPagination.sortData('date')" class="btn btn-default btn-sm" >
                                <i class="fa " ng-class="appPagination.sortClass('date')"></i></a>
                            </span>
                        </div>
					</th>
					<th>
						<i class="fa fa-users" data-toggle="tooltip" title="Pacientes"></i>
					</th>
					
					<th  class="col-xs-2 col-sm-2 col-md-2">
						<div class="input-group input-group-sm">
                           	<input type="text" ng-change="appPagination.getData(1)" class="form-control input-xs"  ng-model="filter.insurance" placeholder="Seguro" />
                            <span class="input-group-btn">
                                <a ng-click="appPagination.sortData('insurance')" class="btn btn-default btn-sm" >
                                <i class="fa " ng-class="appPagination.sortClass('insurance')"></i></a>
                            </span>
                        </div>
					</th>
					<th  class="col-xs-5 col-sm-5 col-md-5">
						<input type="text" ng-change="appPagination.getData(1)" class="form-control input-sm"  ng-model="filter.chief_complaint" placeholder="Motivo de consulta" />
					</th>
					<th  class="col-xs-3 col-sm-3 col-md-3">
						<input type="text" ng-change="appPagination.getData(1)" class="form-control input-sm"  ng-model="filter.diagnosis" placeholder="Diagnostico" />
					</th>
				</tr>
			</thead>
			<tbody>
				<tr ng-show="appPagination.loadingQuery" ng-cloak >
					<td colspan="6" class="text-center">
						<h2>Loading query</h2>
						<img src="/assets/loading.gif" />
					</td>
				</tr>
				<tr ng-cloak dir-paginate="enc in appPagination.result_data  | itemsPerPage:appPagination.itemsPerPage"  current-page="appPagination.currentPage" total-items="appPagination.total_count" >
					<td> <a data-toggle="tooltip" title="Abrir detalle de consulta" ng-href="/encounter/detail/{{enc.id}}">{{enc.id}}</a></td>
					<td>{{enc.date }}</td>
					<td><a ng-href="/patient/chart/{{enc.patient_id}}" class="icon-folder-plus" data-toggle="tooltip" title="{{enc.patient}}"> </a> </td>
					<td>{{enc.insurance_title }} 	</td>
					<td>{{enc.chief_complaint }}</td>
					<td><span class="label"  ng-repeat="diag in enc.diagnosis" style="margin-right: 2px;font-size: 10px;" ng-class="labelDiagnosis(diag.comment)">{{ diag.comment}}</span></td>
				</tr>
				<tr ng-cloak >
					<td ng-show="(!appPagination.total_count && !appPagination.loadingQuery)" class="text-center" colspan="11">
						<h3>Search result not found</h3>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="panel-footer text-right">
		<dir-pagination-controls 
			max-size="8" 
			direction-links="true" 
			boundary-links="false" 
			on-page-change="appPagination.getData(newPageNumber)" ></dir-pagination-controls>
		 <!-- ng-class="diag.comment.match(/filer.diagnosis.*/) ? 'label-success' : 'label-warning'"  -->
	</div>
</div>