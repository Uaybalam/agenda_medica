
<div class="panel panel-default panel-custom" >
	<div class="panel-heading">
		<div class="row">
			<div class="col-xs-6 col-md-6">
				<label>Comunicaciones pendientes<span ng-cloak data-toggle="tooltip" data-placement="right" title="Total de comunicaciones pendientes" class="badge">{{ paginate.contact.total_count}}</span></label>
				<div class="custom-group-radio">
					<label>
						<input type="radio" ng-change="paginate.contact.getData(1)" ng-model="filter.status" value="0" name="status_contact_pt" >
						<span class="small start" ng-click="">Pendiente</span>
					</label>
					<label>
						<input type="radio" ng-change="paginate.contact.getData(1)" ng-model="filter.status" value="1" name="status_contact_pt" >
						<span class="small end" ng-click="">Completas</span>
					</label>
				</div>
			</div>
			<div class="col-xs-6 col-md-6">
				<!-- required buttons ? -->
			</div>
		</div>
	</div>
	<div class="panel-body">
		<table  class="table table-bordered table-condensed table-hover">
			<thead>
				<tr>
					<th ></th>
					<th ></th>
					<th class="col-md-2">
						<div class="input-group input-group-sm">
							<input type="text" ng-model="filter.created_at"  ng-change="paginate.contact.getData(1)" class="form-control" placeholder="Creado el" />
                            <span class="input-group-btn">
                                <a ng-click="paginate.contact.sortData('created_at')" class="btn btn-default btn-sm" >
                                	<i class="fa " ng-class="paginate.contact.sortClass('created_at')"></i>
                                </a>
                            </span>
                        </div>
					</th>
					<th class="col-md-2">
						<input type="text" ng-model="filter.created_by" ng-change="paginate.contact.getData(1)" class="form-control input-sm" placeholder="Creado por" />
					</th>
					<th class="col-md-4">
						<input type="text" ng-model="filter.patient" ng-change="paginate.contact.getData(1)" class="form-control input-sm" placeholder="Paciente" />
					</th>
					<th class="col-md-4">
						<input type="text" ng-model="filter.reason"  ng-change="paginate.contact.getData(1)" class="form-control input-sm" placeholder="Razón por la cual se debe llamar"  />
					</th>
				</tr>
			</thead>
			<tbody ng-cloak >
				<tr  dir-paginate="contact in paginate.contact.result_data  | orderBy:sortKey:reverse | itemsPerPage:paginate.contact.itemsPerPage"  current-page="paginate.contact.currentPage" total-items="paginate.contact.total_count">
					<td>
						<a class="btn btn-xs btn-info" href="/patient/related-files/open/{{contact.related_file_id}}" target="_blank" ng-show="contact.related_file_id>0" >
							<i class="fa fa-file"></i>
						</a>
					</td>
					<td>
						<button class="btn btn-success btn-xs" ng-click="action_communicate.open(contact)">
							<i class="fa fa-plus"></i> Agregar nota
						</button>
					</td>
					<td> <i class="fa fa-clock-o" 
									data-toggle="tooltip" 
									title="{{ ngHelper.formatDate(contact.create_at) }}" ></i> {{  ngHelper.humanDate( contact.create_at ) }}</td>
					<td> {{ contact.user_nick_name}} </td>
					<td> 
						<i data-toggle="tooltip" title="Fecha de nacimiento {{ contact.patient_dob}}" class="fa fa-calendar"></i> <a ng-href="/patient/detail/{{contact.patient_id}}">{{contact.patient_full_name}}</a> 
						
						<a href="/appointment/detail/{{ contact.closest_appointment_id}}" data-toggle="tooltip" title="Cita más cercana" class="label pull-right" ng-class="contact.closest_appointment_class">{{ contact.closest_appointment_date  }}</a>
					</td>
					<td>{{ contact.reason }}  </td>
				</tr>
				
			</tbody>
		</table>
	</div>
	<div class="panel-footer text-right">
		<dir-pagination-controls 
			max-size="8" 
			direction-links="true" 
			boundary-links="false" 
			on-page-change="paginate.contact.getData(newPageNumber)" ></dir-pagination-controls>
		
	</div>
</div>


