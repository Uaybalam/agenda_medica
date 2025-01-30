<style type="text/css">
	.item-hover{
		display: none;
	}
	.element-hover:hover .item-hover{
		display:inline-block;
	}
</style>
<div class="row" style="font-size:12px;" >
	<div class="col-lg-12">
		<div class="panel panel-default  panel-custom" >
			<div class="panel-heading">
				<div class="row">
					<div class="col-sm-6">

						<label>Lista de archivos relacionados <span class="badge" ng-cloak data-toggle="tooltip" data-placement="right" title="Total de archivos">{{ appPagination.total_count.toLocaleString() }}</span></label>
					</div>
					<div class="col-sm-6 text-right">
						<a href="<?= site_url('patient/related-files/docsDeleted')?>" class="btn btn-xs btn-danger" style="float:right;">Documentos eliminados</a>
					</div>
				</div>
			</div>
			<div class="panel-body" >
				<table class="table table-condensed table-bordered table-hover" >
					<thead>
						<tr>
							<th  class="col-xs-1 text-center"></th>
							<th  class="col-xs-2">
								<div class="input-group input-group-sm">
	                               <input type="text"  ng-change="appPagination.getData(1)" class="form-control input-sm"  ng-model="filter.created_at" placeholder="Creado el" />
	                                <span class="input-group-btn">
	                                    <a ng-click="appPagination.sortData('created_at')" class="btn btn-default btn-sm" >
	                                    <i class="fa " ng-class="appPagination.sortClass('created_at')"></i></a>
	                                </span>
	                            </div>
							</th>
							<th  class="col-xs-3">
								<input type="text" ng-change="appPagination.getData(1)" class="form-control input-sm"  ng-model="filter.title" placeholder="Titulo" />
							</th>
							<th  class="col-xs-3">
								<input type="text" ng-change="appPagination.getData(1)" class="form-control input-sm"  ng-model="filter.patient" placeholder="Paciente" />
							</th>
							<th  class="col-xs-1">
								<input type="text" ng-change="appPagination.getData(1)" class="form-control input-sm"  ng-model="filter.type" placeholder="Tipo" />
							</th>
							<th  class="col-xs-2">
								<input type="text" ng-change="appPagination.getData(1)" class="form-control input-sm"  ng-model="filter.created_by" placeholder="Creado por" />
							</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-show="appPagination.loadingQuery" ng-cloak >
							<td colspan="7" class="text-center">
								<h2>Loading query</h2>
								<img src="/assets/loading.gif" />
							</td>
						</tr>
						<tr  ng-cloak 
							dir-paginate="file in appPagination.result_data  | itemsPerPage:appPagination.itemsPerPage"  
							current-page="appPagination.currentPage" 
							total-items="appPagination.total_count"
							class="element-hover" />
							<td >
								<button ng-click="open(file)" type="button" data-toggle="tooltip" title="Eliminar" class="btn btn-xs btn-danger item-hover"> <i class="fa fa-trash"></i> </button>
								<button ng-click="openPreview(file)" type="button" data-toggle="tooltip" title="Vista previa" class="btn btn-xs btn-success item-hover"> <i class="fa fa-eye"></i> </button>
							</td>
							<td>{{ ngHelper.normalDate(file.create_at) }} <i data-toggle="tooltip" class="fa fa-clock-o" title="{{ ngHelper.formatDate(file.create_at)}}"></i></td>
							<td>{{ file.title }}</td>
							<td> <i class="fa fa-calendar" data-toggle="tooltip" title="Fecha de nacimiento: {{ file.patient_dob }}"></i> <a ng-href="/patient/detail/{{file.patient_id}}">{{ file.patient }}</a> </td>
							<td>{{ file.type }}</td>
							<td>{{ file.created_by }}</td>
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
			</div>
		</div>
	</div>
</div>
