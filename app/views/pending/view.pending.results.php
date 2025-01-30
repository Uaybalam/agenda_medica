
<div class="panel panel-default panel-custom" >
	<div class="panel-heading">
		<div class="row">
			<div class="col-xs-6 col-md-6">
				<label>Resultados <span ng-cloak  data-placement="right" data-toggle="tooltip" title="Total de Resultados" class="badge">{{ appPagination.total_count.toLocaleString() }}</span></label>
			</div>
		</div>
	</div>
	<div class="panel-body" >
		<table  class="table table-bordered table-condensed table-hover">
			<thead>
				<th style="min-width: 80px;"></th>
				<th style="min-width: 80px;">
					<div class="input-group input-group-sm">
						<input type="text" ng-model="filter.created_at" ng-change="appPagination.getData(1)"  class="form-control" placeholder="Fecha de consulta" />
                        <span class="input-group-btn">
                            <a ng-click="appPagination.sortData('created_at')" class="btn btn-default btn-sm" >
                            	<i class="fa " ng-class="appPagination.sortClass('created_at')"></i>
                            </a>
                        </span>
                    </div>
				</th>
				<th class="col-md-3">
					<input type="text" ng-model="filter.patient"  ng-change="appPagination.getData(1)" class="form-control input-sm " placeholder="Patient" />
				</th>
				<th class="col-md-2">
					<select class="form-control input-sm"   ng-model="filter.type" ng-change="appPagination.getData(1)">
						<option value="" >Todos los tipos</option>
						<option ng-repeat="type in data.availible_types" value="{{type}}">{{ type }}</option>
					</select>
				</th>
				<th class="col-md-2">
					<div class="form-group form-grpup-sm" style="margin:0px;">
						<div class="btn-group btn-group-sm"  style="width:100% !important;">
				            <button title="Filtrar por estatus" data-toggle="dropdown" style="width:100% !important;" class="btn btn-sm btn-default dropdown-toggle" data-placeholder="Todos"> Filtrar por estatus <span class="caret"></span></button>
			            	<ul class="dropdown-menu"> 
				              	<li ng-repeat="sta in data.status_result">
				                	<input ng-checked="sta.checked" type="checkbox" id="apt-status-{{sta.id}}" value="{{sta.id}} " ng-click="onChangeStatus(sta)" >
				               	 	<label for="apt-status-{{sta.id}}"> {{sta.name}} </label>
				              	</li> 
			           		</ul>
		          		</div>
		          	</div>
				</th>
				
				<th class="col-md-3">
					<input type="text" ng-model="filter.title" ng-change="appPagination.getData(1)" class="form-control input-sm" placeholder="Lab Title" />
				</th>
			</thead>	
			<tbody ng-cloak > 
				<tr  dir-paginate="result in appPagination.result_data | orderBy:sortKey:reverse | itemsPerPage:appPagination.itemsPerPage" total-items="appPagination.total_count">
					<td class="text-center">
						<button class="btn btn-success btn-xs" ng-click="action_results.open(result , $index)" ><i class="fa fa-edit"></i></button>
						<button class="btn btn-success btn-xs" ng-click="action_contact.open(result , $index)" ><i class="fa fa-comments-o"></i></button>
					</td>
					<td> <a ng-href="/encounter/request/{{result.encounter_id}}">{{ result.created_at }}</a></td>
					<td> <small data-toggle="tooltip" title="{{ printYears(result.date_of_birth)}}">({{ result.date_of_birth }})</small>  <a ng-href="/patient/detail/{{ result.patient_id }}"> {{ result.patient }}   </a> </td>
					<td>{{ result.type_result }}</td>
					<td>{{ nameStatus( result.status) }}</td>
					<td>{{ result.title }}</td>
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