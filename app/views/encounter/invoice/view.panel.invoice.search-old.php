<style type="text/css">
	.table-wrapper { 
    overflow-x:scroll;
    overflow-y:visible;
    width:250px;
    margin-left: 120px;
}


td, th {
    padding: 5px 20px;
    width: 100px;
}

th:first-child {
    position: fixed;
    left: 5px
}

</style>

<div class="panel panel-default  panel-custom" >
	<div class="panel-heading">
		<div class="row">
			<div class="col-sm-6">
				<label>Encounters <span class="badge" ng-cloak data-toggle="tooltip" data-placement="right" title="Total encounters">{{ appPagination.total_count.toLocaleString() }}</span></label>
			</div>
			<div class="col-sm-6 text-right">
				<button class="btn btn-success btn-xs"> <i class="fa fa-columns"></i> Columns </button>
			</div>
		</div>
	</div>
	<div class="panel-body" >
		<table class="table table-condensed " >
			<thead>
				<tr>
					<th  class="col-xs-2">
						<div class="input-group input-group-sm">
                            <input type="text" ng-change="appPagination.getData(1)" class="form-control input-xs"  ng-model="filter.patient" placeholder="Patient" />
                            <span class="input-group-btn">
                                <a ng-click="appPagination.sortData('patient')" class="btn btn-default btn-sm" >
                                <i class="fa " ng-class="appPagination.sortClass('patient')"></i></a>
                            </span>
                        </div>
					</th>
					<th  class="col-xs-1">
						<div class="input-group input-group-sm">
                            <input placeholder="PT DOB"  ng-model="filter.patient_dob"  type="text" ng-change="appPagination.getData(1)" class="form-control input-xs"  />
                             <span class="input-group-btn">
                                <a ng-click="appPagination.sortData('patient_dob')" class="btn btn-default btn-sm" >
                                <i class="fa " ng-class="appPagination.sortClass('patient_dob')"></i></a>
                            </span>
                        </div>
					</th>
					<th  class="col-xs-1">
						<div class="input-group input-group-sm">
                            <input placeholder="Visit Type"  ng-model="filter.appt_visit_type"  type="text" ng-change="appPagination.getData(1)" class="form-control input-xs"  />
                        </div>
					</th>
					<th  class="col-xs-1">
						<div class="input-group input-group-sm">
                            <input placeholder="Subtotal" disabled="true" class="form-control input-xs"  />
                        </div>
					</th>
					<th  class="col-xs-1">
						<div class="input-group input-group-sm">
                            <input placeholder="Open Balance" disabled="true"   class="form-control input-xs"  />
                        </div>
					</th>
					<th  class="col-xs-1">
						<div class="input-group input-group-sm">
                            <input placeholder="Discount" disabled="true"   class="form-control input-xs"  />
                        </div>
					</th>
					<th  class="col-xs-1">
						<div class="input-group input-group-sm">
                            <input placeholder="Total" disabled="true"   class="form-control input-xs"  />
                        </div>
					</th>
					<th  class="col-xs-1">
						<div class="input-group input-group-sm">
                            <input placeholder="Paid Amount" disabled="true"   class="form-control input-xs"  />
                        </div>
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
				<tbody ng-cloak dir-paginate="insurance in appPagination.result_data  | itemsPerPage:appPagination.itemsPerPage"  current-page="appPagination.currentPage" total-items="appPagination.total_count" >
					<tr>
						<td> <a data-toggle="tooltip" title="Open patient detail" ng-href="/patient/detail/{{insurance.id}}">{{insurance.patient}}</a></td>
						<td>{{ insurance.patient_dob }}</td>
						<td>{{ insurance.appt_visit_type }} </td>
						<td>{{ insurance.subtotal }} 	</td>
						<td>{{ insurance.open_balance }}</td>
						<td>{{ insurance.discount }}</td>
						<td>{{ insurance.total }}</td>
						<td>{{ insurance.paid }}</td>
					</tr>
					<tr>
						<td colspan="8"  style="padding: 10px 0px;">
							<div class="col-md-1"> <label>Office_visit:</label> {{ insurance.office_visit }}</div>
							<div class="col-md-1"> <label>Laboratories:</label> {{ insurance.laboratories }}</div>
							<div class="col-md-1"> <label>Injections:</label> {{ insurance.injections }}</div>
							<div class="col-md-1"> <label>Medications:</label> {{ insurance.medications }}</div>
							<div class="col-md-1"> <label>Procedures:</label> {{ insurance.procedures }}</div>
							<div class="col-md-1"> <label>Ecg:</label> {{ insurance.ecg }}</div>
							<div class="col-md-1"> <label>Ultrasond:</label> {{ insurance.ultrasound }}</div>
							<div class="col-md-1"> <label>X-Ray:</label> {{ insurance.x_ray }}</div>
							<div class="col-md-1"> <label>Physical:</label> {{ insurance.physical }}</div>
						</td>
					</tr>
				</tbody>
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