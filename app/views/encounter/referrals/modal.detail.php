<?php echo form_open('/encounter/referral/update/',[
		'class' => 'form-horizontal',
		'ng-submit' => 'action_referral.submit($event)',
		'autocomplete' => 'off'
	]); ?>
		
		<div class="form-group form-group-sm" ng-show="(!default.referral.encounter_id) ? true : false ">
			<label class="col-sm-2 control-label">Seguro</label>
			<div class="col-sm-10">
				<input type="text" class="form-control"  ng-model="default.referral.insurance"  />
			</div>
		</div>
		<div >
			<div class="form-group form-group-sm">
				<label class="col-sm-2 control-label">Especialidad</label>
				<div class="col-sm-4" >
					<input type="text"  class="form-control" ng-model="default.referral.speciality" >
				</div>	
				<label class="col-sm-2 control-label">Servicio</label>
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
			
		</div>
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
			<label class="col-sm-2 control-label">IPA Date Sent</label>
			<div class="col-sm-4">
				<input ng-model="default.referral.date_ipa_sent" class="form-control" type="text" placeholder="" />
			</div>
			<label class="col-sm-2 control-label">IPA Date Recived</label>
			<div class="col-sm-4">
				<input ng-model="default.referral.date_ipa_recived" class="form-control" type="text" placeholder="" />
			</div>
		</div>-->
		<div class="form-group form-group-sm">
			<label class="col-sm-2 control-label">Proveedor Solicitado</label>
			<div class="col-sm-4">
				<input ng-model="default.referral.requested_provider" class="form-control" type="text" placeholder="" />
			</div>
			<!--<label class="col-sm-2 control-label">Date Requested</label>
			<div class="col-sm-4">
				<input ng-model="default.referral.date_requested" class="form-control" type="text" placeholder="" />
			</div>
		</div>
		<div class="form-group form-group-sm"> -->
			<label class="col-sm-2 control-label">Fecha de notificación al paciente</label>
			<div class="col-sm-4">
				<input ng-model="default.referral.date_patient_notify" class="form-control" type="text" placeholder="" />
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-sm-2 control-label">Estatus</label>
			<div class="col-sm-4">
				<select class="form-control input-sm" ng-model="default.referral.status" >
                	<option ng-repeat="(key, value) in availableStatus" value="{{key}}">{{value}}</option>
                </select>
			</div>
			<label class="col-sm-2 control-label">Fecha de cita con especialista:</label>
			<div class="col-sm-4">
				<input ng-model="default.referral.date_specialist_appt" class="form-control" type="text" placeholder="" />
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-sm-2 control-label">Fecha de seguimiento de cita con especialista:</label>
			<div class="col-sm-4">
				<input ng-model="default.referral.date_follow_up_appt" class="form-control" type="text" placeholder="" />
			</div>
			<label class="col-sm-2 control-label">Fecha de consulta de reporte:</label>
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
		<div class="row well well" style="margin-bottom:0px;">
			<div class="col-sm-6 text-left" style="margin-bottom:0px;">
				<button ng-click="action_referral.deleteExternal()" ng-show="default.referral.user_created_nickname!=''" type="button" class="btn btn-danger submit"> Delete </button>
			</div>
			<div class="col-sm-6 text-right" style="margin-bottom:0px;">
				
				<button type="submit" class="btn btn-primary submit"> Save </button>
			</div>
		</div>
	<?php echo form_close(); ?>
	
