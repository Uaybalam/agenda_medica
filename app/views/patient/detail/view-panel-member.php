<div class="panel panel-default custom-widget" ng-cloak > 
	<div class="panel-heading"> 
		<div class="row"> 
			<div class="col-xs-6 col-md-6"> 
				<label for="">Membresia</label> 
			</div> 
			<div class="col-xs-6 col-md-6 text-right"> 
				<button title="Editar membresia" data-placement="bottom" data-toggle="tooltip" ng-click="action_member.open()"  class="btn btn-success btn-xs"> <i class="fa fa-pencil"></i> </button> 
			</div> 
		</div> 
	</div> 
	<div class="panel-body" style="height:110px;font-size:12px;"> 
		<div class="col-lg-12">
			<table class="table table-hover-app table-bordered">
				<tr>	
					<th class="col-xs-4 col-md-4">Nombre</th>
					<td class="col-xs-8 col-md-8" >{{ data.patient.membership_name }}</td>
				</tr>
				<tr>	
					<th class="col-xs-4 col-md-4">Fecha de expiración</th>
					<td class="col-xs-8 col-md-8" >{{ data.patient.membership_date }}</td>
				</tr>
				<tr>	
					<th class="col-xs-4 col-md-4">Tipo</th>
					<td class="col-xs-8 col-md-8" >{{ data.patient.membership_type }}</td>
				</tr>
				<tr>	
					<th class="col-xs-4 col-md-4">Notas</th>
					<td class="col-xs-8 col-md-8" >{{ data.patient.membership_notes }}</td>
				</tr>
			</table>
		</div>
	</div>
</div>