<style type="text/css">
	.table.table-custom-footable{
		
	}
	.table.table-custom-footable tbody{
		border:1px solid #ecf0f1;
	}
	.table.table-custom-footable tbody tr:nth-child(2) .row-content{
		max-height: 0px;
		overflow: hidden;
		-webkit-transition: all .5s ease;
		-moz-transition: all .5s ease;
		-o-transition: all .5s ease;
		-ms-transition: all .5s ease;
		transition: all .5s ease;
	}
	.table.table-custom-footable tbody tr.active-row .row-content{
		max-height:500px;
	}
</style>
<div class="row" style="font-size:12px;">
	<div class="col-lg-12">
		<div class="panel panel-default  panel-custom" >
			<div class="panel-heading">
				<div class="row">
					<div class="col-sm-6">
						<label>Derivaciones <span class="badge" ng-cloak data-toggle="tooltip" data-placement="right" title="Total referrals">{{ appPagination.total_count.toLocaleString() }}</span></label>
					</div>
					<div class="col-sm-6 text-right">
						<button ng-click="action_referral.openModalCreate();" class="btn btn-success btn-xs"> <i class="fa fa-plus"></i> Agregar nueva</button>
					</div>
				</div>
			</div>
			<div class="panel-body" >
				<table class="table table-custom-footable table-hover" >
					<thead>
						<tr>
							<th colspan="2" class="col-md-3">
								<input type="text"  ng-change="appPagination.getData(1)" class="form-control input-sm"  ng-model="filter.patient" placeholder="Nombre de paciente" />
	                        </th>
	                        <th colspan="4"></th>
						</tr>
						<tr>
							<th class="col-md-2">
								<div class="input-group input-group-sm" title="Patient ID">
	                               <input type="text"  ng-change="appPagination.getData(1)" class="form-control input-sm"  ng-model="filter.patient_id" placeholder="ID de paciente" />
	                                <span class="input-group-btn">
	                                    <a ng-click="appPagination.sortData('patient')" class="btn btn-default btn-sm" >
	                                    <i class="fa " ng-class="appPagination.sortClass('patient')"></i></a>
	                                </span>
	                            </div>
	                        </th>
	                        <th class="col-md-1">
								<div class="input-group input-group-sm" title="Insurance" >
	                               <input type="text"  ng-change="appPagination.getData(1)" class="form-control input-sm"  ng-model="filter.insurance" placeholder="Seguro" />
	                                <span class="input-group-btn">
	                                    <a ng-click="appPagination.sortData('insurance')" class="btn btn-default btn-sm" >
	                                    <i class="fa " ng-class="appPagination.sortClass('insurance')"></i></a>
	                                </span>
	                            </div>
	                        </th>
	                        <th class="col-md-1">
	                        	<div class="input-group input-group-sm" title="Refer Date">
	                               <input type="text"  ng-change="appPagination.getData(1)" class="form-control input-sm"  ng-model="filter.refer_date" placeholder="Fecha" />
	                                <span class="input-group-btn">
	                                    <a ng-click="appPagination.sortData('refer_date')" class="btn btn-default btn-sm" >
	                                    <i class="fa " ng-class="appPagination.sortClass('refer_date')"></i></a>
	                                </span>
	                            </div>
	                        </th>
	                        <th class="col-md-3">
	                        	<input type="text"  ng-change="appPagination.getData(1)" class="form-control input-sm"  ng-model="filter.reason" placeholder="Raźon" />
	                        </th>
	                        <th class="col-md-1">
	                        	<input type="text"  ng-change="appPagination.getData(1)" class="form-control input-sm"  ng-model="filter.acuity" placeholder="Gravedad" />
	                        </th>
	                        <th class="col-md-1">
	                        	<input type="text"  ng-change="appPagination.getData(1)" class="form-control input-sm"  ng-model="filter.webticket" placeholder="Web ticket" />
	                        </th>
	                         <th class="col-md-2">
	                        	<input type="text"  ng-change="appPagination.getData(1)" class="form-control input-sm"  ng-model="filter.speciality" placeholder="Especialidad" />
	                        </th>
	                        <th class="col-md-2">
	                        	<input type="text" class="form-control input-sm"  ng-model="filter.comments" placeholder="Comentarios"  ng-change="appPagination.getData(1)" />
	                        </th>
	                        <th class="col-md-1" style="min-width: 100px;">
	                        	<select class="form-control input-sm" ng-model="filter.status" ng-change="appPagination.getData(1)">
	                        		<option value="ALL">Estatus</option>
	                        		<option ng-repeat="(key, value) in availableStatus" value="{{key}}">{{value}}</option>
	                        	</select>
	                        </th>
	                        <th style="min-width: 70px !important;"></th>
						</tr>
					</thead>
					<tbody ng-cloak dir-paginate="item in appPagination.result_data  | itemsPerPage:appPagination.itemsPerPage"  current-page="appPagination.currentPage" total-items="appPagination.total_count"  >
						<tr>
							<td><a href="/patient/detail/{{item.patient_id}}">{{item.patient_id}} - {{ item.patient }}</a></td>
							<td>{{ item.insurance }}</td>
							<td>
								<span ng-show="item.encounter_id==0">{{item.refer_date}}</span> 
								<a data-toggle="tooltip" title="Encounter {{ item.encounter_id}}" ng-show="item.encounter_id>0" href="/encounter/detail/{{item.encounter_id}}">{{ item.refer_date}}</a>
							</td>
							<td>{{ item.reason }}</td>
							<td>{{ item.acuity == "Routine" ? "Rutina" : "Urgente" }}</td>
							<td>{{ item.webticket}}</td>
							<td>{{ item.speciality}} <span class="label label-info pull-right" ng-show="item.date_app">App Date: {{ item.date_app}}</span></td>
							<td>{{ item.comments }}</td>
							<td>{{ availableStatus[item.status]}}</td>
							<td>
								<button ng-click="item.activeRow=!item.activeRow;" ng-class="item.activeRow ? 'fa-angle-up btn-primary' : 'fa-angle-down btn-default' " class="btn btn-xs fa " title="Collapse" > </button> 
								<button ng-click="action_referral.openModal(item)" class="btn btn-xs btn-success fa fa-pencil" title="Edit" > </button> 
							</td>
						</tr>
						<tr ng-class="item.activeRow ? 'active-row' : ''">
							<td colspan="9">
								<div class="row-content">
									<div class="clearfix" style="margin-top: 10px;"></div>
									<div class="col-md-3">
										<p><b>Diagnostico: </b> {{ item.diagnosis }}</p>
									</div>
									<!--<div class="col-md-3">
										<p><b>DateSentToIPA: </b> {{ item.date_ipa_sent }}</p>
									</div>
									<div class="col-md-3">
										<p><b>DateRequested: </b> {{ item.date_requested }}</p>
									</div>
									<div class="col-md-3">
										<p><b>DateRecvdFromIPA: </b> {{ item.date_ipa_recived}}</p>
									</div>--> 
									
									<div class="col-md-3">
										<p><b>Servicio solicitado: </b> {{ item.service }} </p>
									</div>
									<div class="col-md-3">
										<p><b>Proveedor Solicitado: </b> {{ item.requested_provider }} </p>
									</div>
									<div class="col-md-3">
										<p><b>Fecha de notificación al paciente: </b> {{ item.date_patient_notify }}</p>
									</div>
									<div class="col-md-3">
										<p><b>Fecha de cita con especialista: </b> {{ item.date_specialist_appt }}</p>
									</div> 
									
									<div class="col-md-3">
										<p><b>Fecha de seguimiento de cita con especialista: </b> {{ item.date_follow_up_appt }}</p>
									</div>
									<div class="col-md-3">
										<p><b>Fecha de consulta de reporte: </b> {{ item.date_consultation_report }}</p>
									</div>
									<div class="col-md-6">
										<p><b>Comentarios: </b> {{ item.comments }}</p>
									</div>
									<div class="clearfix"></div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="panel-footer text-right">
				{{ newPageNumber }}
				<dir-pagination-controls 
					max-size="8" 
					direction-links="true" 
					boundary-links="false" 
					on-page-change="appPagination.getData(newPageNumber)" ></dir-pagination-controls>
			</div>
		</div>
	</div>
</div>
