<div class="panel panel-default custom-widget"  > 
	<div class="panel-heading"> 
		<div class="row"> 
			<div class="col-xs-6 col-md-6"> 
				<label for="">Tuberculosis</label> 
			</div> 
			<div class="col-xs-6 col-md-6 text-right"> 
				<a title="Imprimir prueba de Tuberculosis" data-placement="bottom" data-toggle="tooltip" ng-href="/patient/tuberculosis/{{data.patient.id}}/pdf"  class="btn btn-warning btn-xs" target="_blank"> <i class="fa fa-print"></i> </a> 
				
				<button title="Editar prueba de tuberculosis" data-placement="bottom" data-toggle="tooltip" ng-click="action_tuberculosis.open()"  class="btn btn-success btn-xs"> <i class="fa fa-pencil"></i> </button> 
			</div> 
		</div> 
	</div> 
	<div class="panel-body" style="height:170px;font-size:12px;"> 
		<div class="col-lg-12" ng-cloak>
			<table class="table table-hover-app table-bordered">
				<tr>	
					<th class="col-xs-3 col-md-3">Tipo</th>
					<td class="col-xs-8 col-md-8" colspan="3">{{ data.patient_tuberculosis.type }}</td>
				</tr>
				<tr>	
					<th class="col-xs-3 col-md-3">Resultado</th>
					<td class="col-xs-3 col-md-3" >{{ data.patient_tuberculosis.result }}</td>
					<th class="col-xs-3 col-md-3">Tamaño</th>
					<td class="col-xs-3 col-md-3" >{{ data.patient_tuberculosis.size }}</td>
				</tr>
				<tr>	
					<th class="col-xs-3 col-md-3">Fecha</th>
					<td class="col-xs-3 col-md-3" >{{ data.patient_tuberculosis.date }}</td>
					<th class="col-xs-3 col-md-3">Induration</th>
					<td class="col-xs-3 col-md-3" >{{ data.patient_tuberculosis.induration }}</td>
				</tr>
				<tr>	
					<th class="col-xs-3 col-md-3">Revisado por</th>
					<td class="col-xs-3 col-md-3" >{{ data.patient_tuberculosis.read_by }}</td>
					<th class="col-xs-3 col-md-3">Fecha de revisión</th>
					<td class="col-xs-3 col-md-3" >{{ data.patient_tuberculosis.date_read }}</td>
				</tr>
				<tr>	
					<th class="col-xs-3 col-md-3">Riesgo</th>
					<td class="col-xs-3 col-md-3" >{{ data.patient_tuberculosis.risk_assessment }}</td>
					<th class="col-xs-3 col-md-3">RX de tórax</th>
					<td class="col-xs-3 col-md-3" >{{ data.patient_tuberculosis.chest_x_ray }}</td>
				</tr>
				<tr>	
					<th class="col-xs-3 col-md-3">Inicio de tratamiento</th>
					<td class="col-xs-3 col-md-3" >{{ data.patient_tuberculosis.treatment_start_date }}</td>
					<th class="col-xs-3 col-md-3">Fin de tratamiento</th>
					<td class="col-xs-3 col-md-3" >{{ data.patient_tuberculosis.treatment_end_date }}</td>
				</tr>
			</table>
		</div>
	</div>
</div>