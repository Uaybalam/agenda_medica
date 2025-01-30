<div class="panel panel-default custom-widget" ng-cloak > 
	<div class="panel-heading"> 
		<div class="row"> 
			<div class="col-md-6"> 
				<label for="">Seguro</label> 
			</div> 
			<div class="col-md-6 text-right"> 
				<button  class="btn btn-success btn-xs"> <i class="fa fa-pencil"></i> </button> 
			</div> 
		</div> 
	</div> 
	<div class="panel-body" > 
		<table class="table table-bordered"> 
			<tbody>
				<tr>
					<th colspan="2" class="col-md-6 text-center well" >Principal</th>
					<th colspan="2" class="col-md-6 text-center well" >Secundario</th>
				</tr>
				<tr>
					<th class="col-md-2 text-right well" >Estatus</th>
					<td class="col-md-4"> <button class="btn btn-primary btn-xs">Activo</button> </td>
					<th class="col-md-2 text-right well" >Estatus</th>
					<td class="col-md-4"> <button class="btn btn-default btn-xs">Inactivo</button> </td>
				</tr>
				<tr>
					<th class="col-md-2 text-right well" >Nombre de seguro</th>
					<td class="col-md-4"> {{ data.patient.insurance_primary_string }} </td>
					<th class="col-md-2 text-right well" >Nombre de seguro</th>
					<td class="col-md-4">{{ data.patient.insurance_secondary_string }}</td>
				</tr>
				<tr>
					<th class="col-md-2 text-right well" >Numero de seguro</th>
					<td class="col-md-4">{{ data.patient.insurance_primary_identify }}</td>
					<th class="col-md-2 text-right well" >Numero de seguro</th>
					<td class="col-md-4">{{ data.patient.insurance_secondary_identify }}</td>
				</tr>
				<tr>
					<th class="col-md-2 text-right well" >Notas</th>
					<td class="col-md-4">{{ data.patient.insurance_primary_notes }}</td>
					<th class="col-md-2 text-right well" >Notas</th>
					<td class="col-md-4">{{ data.patient.insurance_secondary_notes }}</td>
				</tr>
			</tbody> 
		</table> 
	</div>
</div>