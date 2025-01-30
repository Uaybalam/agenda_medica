<style type="text/css">
	.chose-patient{
		background-color:#4D4D52;
	}
	.chose-patient td{
		color: #FFF;
	}
</style>
<div class="row" style="font-size:12px;" >
	<div class="col-lg-12">
		<div class="panel panel-default  panel-custom" >
			<div class="panel-heading">
				<div class="row">
					<div class="col-sm-6">
						<label>Pacientes <span class="badge" ng-cloak data-toggle="tooltip" data-placement="right" title="Total patients">{{ appPagination.total_count.toLocaleString() }}</span></label>
					</div>
					<div class="col-sm-6 text-right">
						<!-- 
						<?php if($this->current_user->access_type == "admin" || $this->current_user->access_type == "root"){ 
							echo '<a 
									data-toggle="tooltip" 
									data-placement="bottom"
									target="_blank" 
									ng-href="/patient/importCsv"
									title="Print patient csv" 
									class="btn btn-warning btn-xs" 
									type="button" > <i class="fa fa-print"></i> Print CSV
								</a>';
						}
						?>
						
						<a 
							data-toggle="tooltip" 
							data-placement="bottom"
							target="_blank" 
							ng-href="/patient/printpdf/?{{data_filter_patients()}}"
							title="Print patient list" 
							class="btn btn-warning btn-xs" 
							type="button" > <i class="fa fa-print"></i> Print
						</a> -->
						<button 
							data-toggle="tooltip" 
							data-placement="bottom"
							ng-click="open_modal()"
							title="Agregar Paciente" 
							class="btn btn-success btn-xs" 
							type="button" > <i class="fa fa-user-plus"></i>
						</button>
					</div>
				</div>
			</div>
			<div class="panel-body" >
				<table class="table table-condensed table-bordered" >
					<thead>
						<tr>
							<th  class="col-xs-1 col-sm-1 col-md-1 text-center">
								
							</th>
							<th  class="col-xs-1 col-sm-1 col-md-1">
								<div class="input-group input-group-sm">
	                               <input type="text"  ng-change="appPagination.getData(1)" class="form-control input-sm"  ng-model="filter.id" placeholder="ID" />
	                                <span class="input-group-btn">
	                                    <a ng-click="appPagination.sortData('id')" class="btn btn-default btn-sm" >
	                                    <i class="fa " ng-class="appPagination.sortClass('id')"></i></a>
	                                </span>
	                            </div>
							</th>
							<th  class="col-xs-2 col-sm-2 col-md-2">
								<div class="input-group input-group-sm">
	                                <input type="text" ng-change="appPagination.getData(1)" class="form-control input-xs"  ng-model="filter.names" placeholder="Nombres" />
	                                <span class="input-group-btn">
	                                    <a ng-click="appPagination.sortData('names')" class="btn btn-default btn-sm" >
	                                    <i class="fa " ng-class="appPagination.sortClass('names')"></i></a>
	                                </span>
	                            </div>
							</th>
							<th  class="col-xs-2 col-sm-2 col-md-2">
									<input type="text" ng-change="appPagination.getData(1)" class="form-control input-sm"  ng-model="filter.last_name" placeholder="Apellido" />
								<!--
								<div class="input-group input-group-sm">
	                               	<input type="text" ng-change="appPagination.getData(1)" class="form-control input-xs"  ng-model="filter.last_name" placeholder="Last name" />
	                                <span class="input-group-btn">
	                                    <a ng-click="appPagination.sortData('last_name')" class="btn btn-default btn-sm" >
	                                    <i class="fa " ng-class="appPagination.sortClass('last_name')"></i></a>
	                                </span>
	                            </div>
	                        	-->
							</th>
							<th  class="col-xs-1 col-sm-1 col-md-1">
								<input type="text" ng-change="appPagination.getData(1)" class="form-control input-sm"  ng-model="filter.date_of_birth" placeholder="Fecha de nacimiento" />
								<!--
								<div class="input-group input-group-sm">
	                               	<input type="text" ng-change="appPagination.getData(1)" class="form-control input-xs"  ng-model="filter.date_of_birth" placeholder="DOB" />
	                                <span class="input-group-btn">
	                                    <a ng-click="appPagination.sortData('date_of_birth')" class="btn btn-default btn-sm" >
	                                    <i class="fa " ng-class="appPagination.sortClass('date_of_birth')"></i></a>
	                                </span>
	                            </div>
	                        -->
							</th>
							<th  class="col-xs-1 col-sm-1 col-md-1">
								<input type="text" class="form-control input-sm"  ng-change="appPagination.getData(1)" ng-model="filter.phone" placeholder="Teléfono" />
								<!--
								<select class="form-control input-sm" ng-change="appPagination.getData(1)"  ng-model="filter.gender" >
									<option value="">Gender</option>
									<option value="Male">Male</option>
									<option value="Female">Female</option>
								</select>
								-->
							</th>
							<th  class="col-xs-1 col-sm-1 col-md-1">
								<input type="text"  ng-change="appPagination.getData(1)" ng-model="filter.insurance"  class="form-control input-sm"  placeholder="Seguro" />
							</th>
							<th  class="col-xs-1 col-sm-1 col-md-1">
								<input type="text" readonly="true" class="form-control input-sm"  placeholder="Edad" />
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
						<tr class="ng-cloak" ng-show="appPagination.result_data.length==0 && !appPagination.loadingQuery">
							<td colspan="7" class="text-center" style="padding-bottom: 22px;"> 
								<h3>Sin pacientes </h3>
								<button class="btn btn-success" ng-click="add_patient_not_found(filter)"> Agregar Paciente </button>
							</td>
						</tr>
						<tr  ng-cloak dir-paginate="patient in appPagination.result_data  | itemsPerPage:appPagination.itemsPerPage"  current-page="appPagination.currentPage" total-items="appPagination.total_count" ng-class="(patient.focusPatient) ? 'chose-patient' : ''" id="patient-id-{{patient.id}}" ng-click="choseClick(patient)"  ng-dblclick="redirectDemographics(patient)" >
							<td>
								<div class="dropdown">
									<button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
										Acciones <span class="caret"></span>
									</button>
									<ul class="dropdown-menu" >
										<li><a ng-href="/appointment/create/?patient_id={{ patient.id }}"><i class="fa fa-calendar-plus-o "></i> Crear Cita</a></li>
										<li><a ng-href="/patient/detail/{{ patient.id }}/" > <i class="fa fa-edit"></i> Demograficos</a></li>
										<li><a ng-href="/patient/chart/{{ patient.id }}"  ><i class="icon-folder-plus "></i> Historia Clínica</a></li>
										<li><a ng-href="/patient/appointments/{{ patient.id }}"><i class="fa fa-book"></i> Citas</a> </li>
										<li role="separator" class="divider"></li>
										<li><a  ng-href="#" ng-click="action_communicate.open(patient)" type="button"   ><i class="fa fa-plus "></i> Agregar Nota </a></li>
									</ul>
								</div>
							</td>
							<td class="text-right">
								<a ng-href="/patient/detail/{{patient.id}}" class="btn btn-xs btn-default">{{patient.id}}</a>
								<span ng-show="patient.discount_type!=''" class="label label-warning">{{ patient.discount_type}}</span>
							</td>
							<td>{{patient.names }} <a href="/appointment/detail/{{ patient.closest_appointment_id}}" data-toggle="tooltip" title="Closest appointment" class="label pull-right" ng-class="patient.closest_appointment_class">{{ patient.closest_appointment_date  }}</a></td>
							<td>{{patient.last_name}} <span  data-toggle="tooltip" title="Genero" class="label label-default pull-right">{{ (patient.gender == "Male" ? "Masculino" : "Femenino")}}</span>  </td>
							<td class="text-center">{{patient.date_of_birth}}</td>
							<td class="text-center">{{ ngHelper.parsePhone(patient.phone) }}</td>
							<td><label ng-class="(patient.insurance_primary_plan_name=='CASH') ? 'text-success' : ''" >{{patient.insurance_primary_plan_name}}</label></td>
							<td>{{patient.age}}</td>
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
