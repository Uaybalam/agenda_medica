<div class="row" >
	<div class="col-md-8">
		<div class="panel panel-default custom-widget" >
			<div class="panel-heading" >
				<div class="row" ng-cloak >
					<div class="col-md-6">
						<label>Información basica  ({{ data.user.id }})
							<span class="text-success" ng-show="data.user.status==1">Activo</span>
							<span class="text-muted" ng-show="data.user.status==0">Deshabilitado</span> 
							<span class="text-warning" ng-show="data.user.status==2">Pendiente de activar</span> 
						</label>
					</div>
					<div class="col-md-6 text-right">
						<a 
							ng-show="(data.user.status==2)? true : false;"
							ng-href="/user/manager/{{data.user.id}}/remove" 
							data-toggle="tooltip" data-placement="bottom" title="Remove user"  
							class="btn btn-danger btn-xs" > <i class="fa fa-trash"></i>  
						</a>
						<button ng-click="action_basic.open()" type="button" data-toggle="tooltip" data-placement="bottom" title="Editar información basica" class="btn btn-success btn-xs"> <i class="fa fa-edit"></i></button>
					</div>
				</div>
			</div>
			<div class="panel-body" style="height:250px;">
				<table class="table table-condensend table-hover-app table-bordered" ng-cloak >
					<tbody>
						<tr>
							<th class="col-md-2">Nombre de usuario</th>
							<td class="col-md-3">{{ data.user.nick_name }}</td>
							<th class="col-md-2">Tipo de acceso</th>
							<td class="col-md-3">{{  data.access_type_avalible[data.user.access_type] }}</td>
						</tr>
						<tr>
							<th>Nombres</th>
							<td>{{ data.user.names }}</td>
							<th>Apellidos</th>
							<td>{{ data.user.last_name }}</td>
						</tr>
						<tr>
							<th>Fecha de nacimiento</th>
							<td>{{ data.user.date_of_birth }}</td>
							<th>Genero</th>
							<td>{{ data.user.gender }}</td>
						</tr>
						<tr>
							<th>Estado civil</th>
							<td>{{ data.user.marital_status }}</td>
							<th>Teléfono</th>
							<td>{{ data.user.phone }}</td>
						</tr>
						<tr>
							<th>Información medica</th>
							<td colspan="3">{{ data.user.medical_information }}</td>
						</tr>
						<tr>
							<th>Estatus de empleado</th>
							<td>{{ data.user.employment_status }}</td>
							<th>Fecha de empleo</th>
							<td>{{ data.user.employment_date }}</td>
						</tr>
						<tr>
							<th>Tipo medico</th>
							<td>{{ data.user.medic_type }}</td>  
							<th>Digital signature</th>
							<td>{{ data.user.digital_signature }}</td>
						</tr>
						<tr>
							<th>Email</th>
							<td colspan="3">{{ data.user.email }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-default custom-widget">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-6">
						<label class="control-label" >Activar la verificacion de 2 pasos</label> 
					</div>
					<div class="col-md-6"> 
						<div class="text-right"> 
							<div class="custom-checkbox">
								<label>
							    	<input type="checkbox" ng-model="data.user.active2fa" ng-true-value="1" ng-false-value="0" ng-click="activate2f()"/>
							    	<span class="checkbox"></span>
							  	</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="panel-body" style="height:240px;">
				<div class="text-center" id="content-qr" ng-show="data.user.active2fa">
					<img src="{{data.user.qrCodeUrl}}" id="" width="200">
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-default custom-widget">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-6">
						<label>Dirección</label>
					</div>
					<div class="col-md-6 text-right">
						<button ng-click="action_address.open()" type="button" data-toggle="tooltip" data-placement="bottom" title="Edit address user" class="btn btn-success btn-xs"> <i class="fa fa-edit"></i></button>
					</div>
				</div>
			</div>
			<div class="panel-body" style="height:200px;">
				<table class="table table-condensend table-hover-app table-bordered">
					<tbody>
						<tr>
							<th class="col-md-4">Dirección</th>
							<td class="col-md-8">{{ data.user.address }}</td>
						</tr>
						<tr>
							<th class="col-md-4">Ciudad</th>
							<td class="col-md-8">{{ data.user.address_city }}</td>
						</tr>
						<tr>
							<th class="col-md-4">Estado</th>
							<td class="col-md-8">{{ data.user.address_state }}</td>
						</tr>
						<tr>
							<th class="col-md-4">Codigo postal</th>
							<td class="col-md-8">{{ data.user.address_zipcode }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div> 
	<div class="col-md-4">
		<div class="panel panel-default custom-widget">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-6">
						<label>Contacto de emergencia</label>
					</div>
					<div class="col-md-6 text-right">
						<button ng-click="action_primarycontact.open()" type="button" data-toggle="tooltip" data-placement="bottom" title="Edit Contact Emergency" class="btn btn-success btn-xs"> <i class="fa fa-edit"></i></button>
					</div>
				</div>
			</div>
			<div class="panel-body" style="height:200px;">
				<table class="table table-condensend table-hover-app table-bordered">
					<tbody>
						<tr>
							<th class="col-md-4">Nombre Completo</th>
							<td class="col-md-8">{{ data.user.emergency_contact_name }}</td>
						</tr>
						<tr>
							<th class="col-md-4">Dirección</th>
							<td class="col-md-8">{{ data.user.emergency_contact_full_address }}</td>
						</tr>
						<tr>
							<th class="col-md-4">Teléfono</th>
							<td class="col-md-8">{{ data.user.emergency_contact_phone }}</td>
						</tr>
						<tr>
							<th class="col-md-4">Relación</th>
							<td class="col-md-8">{{ data.user.emergency_contact_relation }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-default custom-widget">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-6">
						<label>Other contact emergency</label>
					</div>
					<div class="col-md-6 text-right">
						<button ng-click="action_secondarycontact.open()" type="button" data-toggle="tooltip" data-placement="bottom" title="Edit other contact emergency" class="btn btn-success btn-xs"> <i class="fa fa-edit"></i></button>
					</div>
				</div>
			</div>
			<div class="panel-body" style="height:200px;">
				<table class="table table-condensend table-hover-app table-bordered">
					<tbody>
						<tr>
							<th class="col-md-4">Nombre Completo</th>
							<td class="col-md-8">{{ data.user.emergency_contact_other_name }}</td>
						</tr>
						<tr>
							<th class="col-md-4">Dirección</th>
							<td class="col-md-8">{{ data.user.emergency_contact_other_full_address }}</td>
						</tr>
						<tr>
							<th class="col-md-4">Teléfono</th>
							<td class="col-md-8">{{ data.user.emergency_contact_other_phone }}</td>
						</tr>
						<tr>
							<th class="col-md-4">Relación</th>
							<td class="col-md-8">{{ data.user.emergency_contact_other_relation }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-default custom-widget">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-6">
						<label>Contacto del doctor</label>
					</div>
					<div class="col-md-6 text-right">
						<button ng-click="action_doctorcontact.open()" type="button" data-toggle="tooltip" data-placement="bottom" title="Edit other contact emergency" class="btn btn-success btn-xs"> <i class="fa fa-edit"></i></button>
					</div>
				</div>
			</div>
			<div class="panel-body" style="height:200px;">
				<table class="table table-condensend table-hover-app table-bordered">
					<tbody>
						<tr>
							<th class="col-md-4">Nombre Completo</th>
							<td class="col-md-8">{{ data.user.emergency_contact_doctor_name }}</td>
						</tr>
						<tr>
							<th class="col-md-4">Dirección</th>
							<td class="col-md-8">{{ data.user.emergency_contact_doctor_address }}</td>
						</tr>
						<tr>
							<th class="col-md-4">Teléfono</th>
							<td class="col-md-8">{{ data.user.emergency_contact_doctor_phone }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>