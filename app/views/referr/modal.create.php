<?php echo form_open('/encounter/referral/create/',[
		'class' => 'form-horizontal',
		'ng-submit' => 'action_referral.submitCreate($event)',
		'autocomplete' => 'off'
	]); ?>
			<!-- -->
			<div class="form-group form-group-sm">
				<label class="col-sm-2 control-label">Paciente</label>
				<div class="col-sm-10">
					<select  ng-model="default.referral.patient_id"  id="search-patient" class="form-control input-sm" style="width:100%;" >
						<option value="0" disabled="true" selected="selected">Ingrese 1 o más caracteres</option>
					</select>
				</div>
			</div>

			<div class="form-group form-group-sm">
				<label class="col-sm-2 control-label">Seguro</label>
				<div class="col-sm-10">
					<input type="text" class="form-control"  ng-model="default.referral.insurance"  />
				</div>
			</div>

			<div class="form-group form-group-sm">
				<label class="col-sm-2 control-label">Especialidad</label>
				<div class="col-sm-4" >
					<input type="text"  class="form-control" ng-model="default.referral.speciality" >
				</div>	
				<label class="col-sm-2 control-label">Servicios</label>
				<div class="col-sm-4">
					<input type="text" class="form-control"  ng-model="default.referral.service"  />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-sm-2 control-label">Razón</label>
				<div class="col-sm-4">
					<textarea placeholder="Razón" ng-model="default.referral.reason" rows="1" class="form-control"></textarea>
				</div>
				<label class="col-sm-2 control-label">Fecha de derivación</label>
				<div class="col-sm-4">
					<input ng-model="default.referral.refer_date" class="form-control create-datepicker" type="text" placeholder="" />
				</div>
			</div>
			<!-- -->
			<div class="form-group form-group-sm">
				<label class="col-sm-2 control-label">Gravedad</label>
				<div class="col-sm-4">
					<input ng-model="default.referral.acuity" class="form-control" type="text" placeholder="Rutina, Urgente" />
				</div>
				<label class="col-sm-2 control-label">Diagnostico</label>
				<div class="col-sm-4">
					<input ng-model="default.referral.diagnosis" class="form-control" type="text" placeholder="" />
				</div>
			</div>
			<!--<div class="form-group form-group-sm">
				<label class="col-sm-2 control-label">Fecha de envío de IPA</label>
				<div class="col-sm-4">
					<input ng-model="default.referral.date_ipa_sent" class="form-control" type="text" placeholder="" />
				</div>
				<label class="col-sm-2 control-label">Fecha de recepción de IPA</label>
				<div class="col-sm-4">
					<input ng-model="default.referral.date_ipa_recived" class="form-control" type="text" placeholder="" />
				</div>
			</div>-->
			<div class="form-group form-group-sm">
				<label class="col-sm-2 control-label">Solicitud de provedor</label>
				<div class="col-sm-4">
					<input ng-model="default.referral.requested_provider" class="form-control" type="text" placeholder="" />
				</div>
				<label class="col-sm-2 control-label">Fecha de solicitud</label>
				<div class="col-sm-4">
					<input ng-model="default.referral.date_requested" class="form-control" type="text" placeholder="" />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-sm-2 control-label">Fecha de notificación al paciente</label>
				<div class="col-sm-4">
					<input ng-model="default.referral.date_patient_notify" class="form-control" type="text" placeholder="" />
				</div>
				<label class="col-sm-2 control-label">Estatus</label>
				<div class="col-sm-4">
					<select class="form-control input-sm" ng-model="default.referral.status" >
                    	<option ng-repeat="(key, value) in availableStatus" value="{{key}}">{{value}}</option>
                    </select>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-sm-2 control-label">Fecha de cita con el especialista</label>
				<div class="col-sm-4">
					<input ng-model="default.referral.date_specialist_appt" class="form-control" type="text" placeholder="" />
				</div>
				<label class="col-sm-2 control-label">Seguimiento de la cita con el especialista en la fecha:</label>
				<div class="col-sm-4">
					<input ng-model="default.referral.date_follow_up_appt" class="form-control" type="text" placeholder="" />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-sm-2 control-label">Fecha del informe de la consulta:</label>
				<div class="col-sm-4">
					<input ng-model="default.referral.date_consultation_report" class="form-control" type="text" placeholder="" />
				</div> 
			</div>
			<div class="form-group form-group-sm"> 
				<label class="col-sm-2 control-label">Comentarios</label>
				<div class="col-sm-10">
					<textarea class="form-control" ng-model="default.referral.comments"></textarea>
				</div>
			</div>
		<div class="row" style="margin-bottom:0px;">
			<div class="col-lg-12 text-right well well-sm" style="margin-bottom:0px;">
				<button type="submit" class="btn btn-primary submit"> Guardar </button>
			</div>
		</div>
	<?php echo form_close(); ?>
	
