<div class="panel panel-default custom-widget" ng-cloak > 
	<div class="panel-heading"> 
		<div class="row"> 
			<div class="col-xs-6 col-md-6"> 
				<label for="">Entidad Responsable</label> 
			</div> 
			<div class="col-xs-6 col-md-6 text-right"> 
				<div class="custom-group-radio">
					<label >
						<input type="radio" value="Yes" ng-model="data.patient.responsible_self" name="patient_responsible_self" >
						<span class="small start" ng-click="action_responsible.toggle_self('Yes')"  >Yo</span>
					</label>
					<label >
						<input type="radio" value="No" ng-model="data.patient.responsible_self" name="patient_responsible_self" >
						<span class="small end" ng-click="action_responsible.toggle_self('No')" >Otro</span>
					</label>
				</div>
				<button title="Editar responsable" data-placement="bottom" data-toggle="tooltip" ng-click="action_responsible.open()" class="btn btn-success btn-xs"> <i class="fa fa-pencil"></i> </button> 
			</div> 
		</div> 
	</div> 
	<div class="panel-body" style="height:240px;font-size:12px;">
		<div class="col-lg-12">
			<table class="table table-condensend table-hover-app table-bordered">
				<tr>
					<th class="col-xs-4 col-md-4" >Nombre</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ data.patient.responsible_name }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4" >Segundo nombre</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ data.patient.responsible_middle_name }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4" >Apellidos</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ data.patient.responsible_last_name }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4" >Tipo de relación	</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ data.patient.responsible_relationship }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4" >Genero</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ data.patient.responsible_gender }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4" >Teléfono</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ data.patient.responsible_phone }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4" >Teléfono alterno</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ data.patient.responsible_phone_alt }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4">Ciudad</th>
					<td class="col-xs-4 col-md-4">{{ data.patient.responsible_address_city }}</td>
					<th class="col-xs-4 col-md-4">Estado</th>
					<td class="col-xs-4 col-md-4">{{ data.patient.responsible_address_state }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4">Dirección</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ data.patient.responsible_address }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4">Codigo postal</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ data.patient.responsible_address_zipcode }}</td>
				</tr>
				
			</table>
		</div>
	</div>
</div>