<div class="panel panel-default custom-widget" ng-cloak > 
	<div class="panel-heading"> 
		<div class="row"> 
			<div class="col-xs-5 col-md-5" > <label >Seguro secundario</label> </div> 
			<div class="col-xs-7 col-md-7 text-right"  > 
				<div class="custom-group-radio">
					<label >
						<input type="radio" value="1" ng-model="data.patient.insurance_secondary_status" name="patient_insurance_secondary_status" >
						<span class="small start" ng-click="action_insurance_secondary.toggle_status(1)"   >Habilitar</span>
					</label>
					<label >
						<input type="radio" value="0" ng-model="data.patient.insurance_secondary_status" name="patient_insurance_secondary_status" >
						<span class="small end" ng-click="action_insurance_secondary.toggle_status(0)" >Deshabilitar</span>
					</label>
				</div>
				<button  title="Editar seguro secundario" data-placement="bottom" data-toggle="tooltip" ng-click="action_insurance_secondary.open()"  class="btn btn-success btn-xs"> <i class="fa fa-pencil"></i> </button> 
			</div> 
		</div> 
	</div> 
	<div class="panel-body" style="height:85px;font-size:12px;" > 
		<div class="col-lg-12">
			<table class="table table-hover-app table-bordered">
				<tr>	
					<th class="col-xs-4 col-md-4">Nombre de seguro</th>
					<td class="col-xs-8 col-md-8" >{{ data.patient.insurance_secondary_plan_name }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4">Numero de seguro</th>
					<td class="col-xs-8 col-md-8" >{{ data.patient.insurance_secondary_identify }}</td>
				</tr>
				<tr>
					<th class="col-xs-4 col-md-4">Notas</th>
					<td class="col-xs-8 col-md-8" >{{ data.patient.insurance_secondary_notes }}</td>
				</tr>
			</table>
		</div>
	</div>
</div>