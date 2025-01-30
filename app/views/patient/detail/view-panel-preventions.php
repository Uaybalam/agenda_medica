<div class="panel panel-default custom-widget">
	<div class="panel-heading">
		<div class="row">
			<div class="col-md-6">
				<label for="">Prevenciones</label>
			</div>
			<div class="col-md-6 text-right">
				<button title="Editar prevenciones" data-placement="bottom" data-toggle="tooltip" ng-click="action_preventions.open()"  class="btn btn-success btn-xs"> <i class="fa fa-pencil"></i> </button> 
			</div>
		</div>
	</div>
	<div class="panel-body" ng-cloak  style="height:110px;font-size:12px;" >
		<div class="col-lg-12">
			<table class="table table-hover-app table-bordered">
				<tr>	
					<th class="col-xs-4 col-md-4">Alergias</th>
					<td class="col-xs-8 col-md-8" >
						<span style="margin-right:2px;" ng-repeat="name in action_preventions.arr_allergies()"
						class= "label"
						ng-class="(name==='NKDA') ? 'label-success' : 'label-danger'" >{{ name }}</span>
					</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4">Alcohol</th>
					<td class="col-xs-8 col-md-8" >{{ data.patient.prevention_alcohol }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4">Medicamentos</th>
					<td class="col-xs-8 col-md-8" >{{ data.patient.prevention_drugs }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4">Tabaco</th>
					<td class="col-xs-8 col-md-8" >{{ data.patient.prevention_tobacco }}</td>
				</tr>
			</table>
		</div>
		
	</div>
</div>