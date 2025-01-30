<div class="row" >
	<div class="col-md-8">
		<div class="panel panel-default panel-custom" >
			<div class="panel-heading" >
				<div class="row"> 
					<div class="col-sm-8"> 
						<label>Crear cita</label> 
					</div> 
					<div class="col-sm-4 text-right"> 
						<button ng-click="open_modal()" title="Add new patient" data-toggle="tooltip" data-placement="bottom" class="btn btn-success btn-xs" type="button"> 
							<i class="fa fa-user-plus"></i> Crear paciente
						</button>
					</div> 
				</div>
			</div>
			
			<div class="panel-body " style="height: auto;">
				<div class="col-lg-12 form-horizontal" ng-cloak>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label control-label-sm">Tipo</label>
						<div class="col-sm-8">
							<div class="btn-group btn-group-sm">
								<label class="btn btn-sm btn-default"  	ng-click="action_appt.change_type_appt(0);action_appt.click_type_appointment()" ng-class="default.appt.type_appointment==0 ? 'active' : ''">Cita</label>
								<label class="btn btn-sm btn-default" 	ng-click="action_appt.change_type_appt(1)" ng-class="default.appt.type_appointment==1 ? 'active' : ''">Sin cita</label>
							</div>
						</div>
					</div>

					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Fecha</label>
						<div class="col-sm-4">
							<div class="input-group pull-right">
								<span data-toggle="tooltip" data-placement="top" title="Previous day" class="input-group-addon input-group-addon-link" style="padding:0px 10px;" ng-click="action_appt.setBackDay()"><i class="fa fa-arrow-left"></i></span>
								<input ng-change="action_appt.change_date()" type="text"  class="form-control input-sm create-datepicker" ng-model="default.appt.date" placeholder="month / day / year" >
								<span data-toggle="tooltip" data-placement="top" title="Next day" class="input-group-addon input-group-addon-link" style="padding:0px 10px;" ng-click="action_appt.setNextDay()"><i class="fa fa-arrow-right"></i></span>
								<span data-toggle="tooltip" data-placement="top" title="Today" class="input-group-addon input-group-addon-link" style="padding:0px 10px;" ng-click="action_appt.setToday()"><i class="fa fa-calendar"></i></span>
								
							</div>
						</div>
						<label class="col-sm-1 control-label">Hora</label>
						<div class="col-sm-4">
							<select ng-change="action_appt.change_custom_time()" ng-model="default.appt.hour" class="form-control input-sm">
								<option value=""  disabled="disabled" selected="selected">Hour</option>
								<option ng-repeat="hour in default.time.hours" value="{{hour}}">{{hour}}</option> 
							</select>
						</div>  
					</div> 

					<div class="form-group form-group-sm" >
						<label class="col-sm-3 control-label"> Paciente </label>
						<div class="col-sm-9">
							<select  ng-model="default.appt.patient_id" ng-change="change_patient()" id="patient_id" class="form-control input-sm" style="width:100%;" >
								<?php if($_['patient']) : ?> 
									<option value="<?= $_['patient']->id ?>" selected="selected">
										<?= $_['patient']->name.' '.$_['patient']->middle_name.' '.$_['patient']->last_name; ?>,
										<span class="text-opacity"><?= $_['patient']->date_of_birth ?></span>
										
									</option>
								<?php else : ?>
									<option value="0" disabled="true" selected="selected">Ingrese 1 o más caracteres</option>
								<?php endif; ?>
							</select>
							<span class="help-block">Filtra por nombres y fecha de nacimiento, Ejemplo <b>Paul <b>:</b> 11/13/1987</b></span>
						</div>
					</div>
					<div class="well well-sm" ng-show="default.appt.patient_id" >
						<div class="form-group form-group-sm">
							<label class="col-sm-3 control-label">Plan de seguro</label>
							<div class="col-sm-2">
								<input type="text" readonly="true"  ng-model="default.demographics.patient.insurance_primary_plan_name" class="form-control input-sm" >
							</div>
							<label class="col-sm-2 control-label">Numero de seguro</label>
							<div class="col-sm-5">
								<input type="text" readonly="true"  ng-model="default.demographics.patient.insurance_primary_identify" class="form-control input-sm" >
							</div>

							<div ng-show="default.demographics.patient.warnings.length>0">
								<label class="col-sm-3 control-label">Alertas</label>
								<div class="col-sm-9">
									<table class="table table-bordered table-condensed table-hover"> 
										<tr ng-repeat="warning in default.demographics.patient.warnings | filter: { status: '0' }" >
											<td class="warning">
												{{ lapse_time(warning.create_at) }}, <b>Por:</b> {{warning.user_create }} </span><br>
												<b> {{ warning.description }}</b>
											</td>
										</tr>
									</table> 
								</div>
							</div>
						</div>

						
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Codigo</label>
						<div class="col-sm-9">
							<input type="text"  ng-model="default.appt.code" class="form-control input-sm" >
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Tipo de seguro</label>
						<div class="col-sm-9">
							<input type="text"  ng-model="default.appt.insurance_type" class="form-control input-sm" >
						</div>
					</div>
					
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Tipo de visita</label>
						<div class="col-sm-9">
							<div class="btn-group btn-group-sm">
								<label class="btn btn-default btn-sm"  
									ng-repeat="(key, value) in visit_types "
									ng-class="default.appt.visit_type==value ? 'active' : ''"
									ng-click="default.appt.visit_type=value" value="{{ value}}" > {{ value}}
								</label>
							</div>
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Notas</label>
						<div class="col-sm-9">
							<textarea rows="3" ng-model="default.appt.notes" class="form-control input-sm" placeholder="Razon por la que se agendo"></textarea>
						</div>
					</div>

					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label"></label>
						<div class="col-sm-9">
							<button class="btn btn-primary submit" ng-click="action_appt.submit()" type="button"> Guardar <i class="fa fa-arrow-circle-right"></i></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-default panel-custom" >
			<div class="panel-heading" >
				<label>Citas en la fecha <u ng-cloak > {{default.appt.date}} </u> <span ng-cloak data-placement="right" title="Total by date" data-toggle="tooltip" class="badge">{{ (data.appointments | filter:filterStatus ).length }}</span></label>
			</div>
			<div class="panel-body text-center" >
				<h3 ng-show="!data.appointments.length" class="text-warning text-center">No hay citas disponibles para este día</h3>
				
				<div ng-cloak ng-repeat="appt in data.appointments | filter:{ status: '!8'}  | orderBy:'full_date_sort'" style="margin:2px;" ng-class="appt.status<0 ? 'inline-block' : 'inline-block'" >
					<div ng-show="appt.status<0">
						<button ng-click="action_appt.change_time(appt)" type="button" class="btn btn-xs" ng-class="appt.choosen_time===true ? 'btn-warning' : 'btn-success'">{{ appt.time }} Disponible </button>
					</div>
					<div ng-show="appt.status>=0">
						<a  ng-href="/appointment/detail/{{ appt.id }}" class="btn btn-xs btn-info"> {{ appt.time }} {{ appt.patient}} <span class="text-white">{{ appt.visit_type }} </span> </a> 
					</div>
				</div>
			</div>
		</div>
	</div>
</div>