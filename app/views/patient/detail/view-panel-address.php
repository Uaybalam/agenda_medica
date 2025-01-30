<div class="panel panel-default custom-widget" ng-cloak > 
	<div class="panel-heading"> 
		<div class="row"> 
			<div class="col-xs-6 col-md-6"> 
				<label for="">Dirección</label> 
			</div> 
			<div class="col-xs-6 col-md-6 text-right"> 
				<button title="Editar dirección" data-placement="bottom" data-toggle="tooltip" ng-click="action_address.open()"  class="btn btn-success btn-xs"> <i class="fa fa-pencil"></i> </button> 
			</div> 
		</div> 
	</div>
	<div class="panel-body" style="height:110px;font-size:12px;"> 
		<div class="col-lg-12">
			<table class="table table-condensend table-hover-app table-bordered">
				<tr>
					<th class="col-xs-4 col-md-4" ng-class="!data.patient.address ? 'text-danger' : '' ">Dirección</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ data.patient.address }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4" ng-class="!data.patient.address_zipcode ? 'text-danger' : '' ">Codigo Postal</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ data.patient.address_zipcode }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4" ng-class="!data.patient.address_city ? 'text-danger' : '' ">Ciudad</th>
					<td class="col-xs-4 col-md-4">{{ data.patient.address_city  }}</td>
					<th class="col-xs-4 col-md-2" ng-class="!data.patient.address_state ? 'text-danger' : '' ">Estado</th>
					<td class="col-xs-4 col-md-2">{{ data.patient.address_state }}</td>
				</tr>
			</table>
		</div>
	</div>
</div>