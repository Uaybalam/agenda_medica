<div class="panel panel-default custom-widget" >
	<div class="panel-heading" >
		<div class="row">
			<div class="col-xs-4 col-sm-4">
				<label for="">Demograficos del paciente ({{ data.patient.id }})</label>
			</div>
			<div class="col-xs-8 col-sm-8 text-right">
				<a data-toggle="tooltip" title="Imprimir demograficos" data-placement="bottom"  class="btn btn-warning btn-xs" target="_blank" ng-href="/patient/pdf/{{data.patient.id}}" > <i class="fa fa-print"></i></a>
				<a data-toggle="tooltip" title="Demograficos del paciente" data-placement="bottom" class="btn btn-info btn-xs" ng-href="/patient/detail/{{data.patient.id}}"  ><i class="fa fa-user "></i> </a>
				<button data-toggle="tooltip" title="Agregar un alerta" data-placement="bottom" ng-click="action_warning.open()" class="btn btn-success btn-xs" ><i class="fa fa-exclamation-triangle"></i> </button>
				<button ng-click="action_vaccines.open()" class="btn btn-success btn-xs" > Vacunas </button>
				
			</div>
		</div>
	</div>
	<div class="panel-body"  style="height:156px;overflow:auto;">
	
		
		<div class="row no-margin" style="margin:0px;" >
			<div class="col-md-6">
				<table ng-cloak  class="table table-hover-app table-condensend table-bordered">
					<tbody>
						<tr>
							<th class="col-xs-5 col-md-5">Paciente</th>
							<td class="col-xs-7 col-md-7">{{ data.patient.name +' '+data.patient.middle_name+' '+data.patient.last_name}}</td>
						</tr>
						<tr>
							<th >Edad</th>
							<td >{{ data.patient.age }}</td>
						</tr>	
						<tr>
							<th >Fecha de nacimiento</th>
							<td >{{ data.patient.date_of_birth }}</td>
						</tr>
						<tr>
							<th>Seguro</th>
							<td>{{ data.patient.insurance_string }}</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-6">
				<table ng-cloak  class="table table-hover-app table-condensend table-bordered">
					<tbody>
						<tr>	
							<th class="col-xs-3 col-md-3">Alergias</th>
							<td class="col-xs-9 col-md-9"> 
								<span style="margin:2px;"  ng-repeat="name in data.patient.prevention_allergies.split(',')"
									class="label" ng-class="name==='NKDA' ? 'label-success' : 'label-danger'">{{ name }}</span>
								</td>
						</tr>
						<tr>
							<th >Alcohol</th>
							<td > {{ data.patient.prevention_alcohol }} </td>
						</tr>
						<tr>
							<th >Medicamentos</th>
							<td >{{ data.patient.prevention_drugs }}</td>
						</tr>
						<tr>
							<th >Tabaco</th>
							<td >{{ data.patient.prevention_tobacco }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	
		
		<div class="row"  style="margin:0px;padding: 5px 20px">
			<blockquote class="blockquote-danger"  ng-repeat="warning in data.warnings">
				<button 
					ng-show="warning.status==0 || warning.status==3" 
					ng-click="action_warning.remove(warning, $index)" 
					class="btn btn-xs btn-danger" 
					data-toggle="tooltip"
					title="Remover alerta"> 
				<i class="fa fa-trash"></i></button>
				<button ng-show="(warning.status==2 || warning.status==3) && ['root','admin','medic'].indexOf('<?= $this->current_user->access_type; ?>')>=0 " 
					ng-click="action_warning.reply(warning, $index)" 
					class="btn btn-xs btn-danger" 
					data-toggle="tooltip" 
					title="Responder mensaje"> <i class="fa fa-reply-all"></i> </button>
					&nbsp; 
				<span class="text-danger"> 
					<i class="fa fa-user-o" 
									data-toggle="tooltip" 
									data-original-title="Created by {{ warning.user_create }}" ></i>
					<i class="fa fa-clock-o" 
									data-toggle="tooltip" 
									data-original-title="Creada el {{  ngHelper.formatDate(warning.create_at) }}" ></i>
					{{ warning.description }}
				</span>
				<footer ng-show="warning.request_reply==1 && warning.description_reply!=''">
					<i class="fa fa-user-o" 
									data-toggle="tooltip" 
									data-original-title="Creado por {{ warning.user_reply }}" ></i>
					<i class="fa fa-clock-o" 
									data-toggle="tooltip" 
									data-original-title="Creada el {{  ngHelper.formatDate(warning.update_at) }}" ></i>
					{{ warning.description_reply }}
				</footer>
			</blockquote>	
		
		</div>
		
		
	</div>
</div>