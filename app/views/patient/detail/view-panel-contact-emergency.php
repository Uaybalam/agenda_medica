<div class="panel panel-default custom-widget" ng-cloak > 
	<div class="panel-heading"> 
		<div class="row"> 
			<div class="col-xs-6 col-md-6"> 
				<label for="">Contacto de emergencia</label> 
			</div> 
			<div class="col-xs-6 col-md-6 text-right"> 
				<button title="Editar contacto de emergencia" data-placement="bottom" data-toggle="tooltip" ng-click="action_emergency.open()"  class="btn btn-success btn-xs"> <i class="fa fa-pencil"></i> </button> 
			</div> 
		</div> 
	</div> 
	<div class="panel-body" style="height:240px;font-size:12px;"> 
		<div class="col-lg-12">
			

			<table class="table table-condensend table-hover-app table-bordered">
				<tr>
					<th class="col-xs-4 col-md-4">Nombre</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ data.patient.emergency_name }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4">Segundo Nombre</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ data.patient.emergency_middle_name }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4">Apellidos</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ data.patient.emergency_last_name }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4">Tipo de relación</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ data.patient.emergency_relationship }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4">Genero</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ data.patient.emergency_gender }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4">Teléfono</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ data.patient.emergency_phone }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4">Teléfono alterno</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ data.patient.emergency_phone_alt }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4">Ciudad</th>
					<td class="col-xs-4 col-md-4">{{ data.patient.emergency_address_city }}</td>
					<th class="col-xs-4 col-md-4">Estado</th>
					<td class="col-xs-4 col-md-4">{{ data.patient.emergency_address_state }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4">Direccion</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ data.patient.emergency_address }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4">Codigo postal</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ data.patient.emergency_address_zipcode }}</td>
				</tr>
			</table>
			
		</div>
	</div>
</div>