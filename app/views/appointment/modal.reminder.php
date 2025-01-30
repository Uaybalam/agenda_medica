<?php echo form_open('/appointment/reminder/',[
		'class' => 'form-horizontal',
		'ng-submit' => 'action_appointment.submit($event)',
		'autocomplete' => 'off'
	]); ?>
	<div class="row">
		<div class="col-lg-12">
			<div class="form-group form-group-sm" >
				<label class="col-md-3 control-label">Paciente</label>
				<div class="col-md-4">
					<input type="text" readonly="true" class="form-control input-sm" ng-model="default.appointment.patient" />
				</div>
				<label class="col-md-3 control-label">Fecha de nacimiento</label>
				<div class="col-md-2">
					<input type="text" readonly="true" class="form-control input-sm" ng-model="default.appointment.date_of_birth" />
				</div>
			</div>
			<div class="form-group form-group-sm" >
				<label class="col-md-3 control-label">Teléfono</label>
				<div class="col-md-3">
					<input type="text" readonly="true" class="form-control input-sm" ng-model="default.appointment.phone" />
				</div>
				<label class="col-md-3 control-label">Teléfono memo</label>
				<div class="col-md-3">
					<input type="text" readonly="true" class="form-control input-sm" ng-model="default.appointment.phone_memo" />
				</div>
			</div>
			<div class="form-group form-group-sm" >
				<label class="col-md-3 control-label">Teléfono alterno</label>
				<div class="col-md-3">
					<input type="text" readonly="true" class="form-control input-sm" ng-model="default.appointment.phone_alt" />
				</div>
				<label class="col-md-3 control-label">Teléfono alterno memo</label>
				<div class="col-md-3">
					<input type="text" readonly="true" class="form-control input-sm" ng-model="default.appointment.phone_alt_memo" />
				</div>
			</div>
			<div class="form-group form-group-sm" >
				<label class="col-md-3 control-label">Idioma</label>
				<div class="col-md-9">
					<input type="text" readonly="true" class="form-control input-sm" ng-model="default.appointment.language" />
				</div>
			</div>
			<hr>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">¿Confirmo?</label>
				<div class="col-md-9">
					<div class="btn-group btn-group-sm">
						<label class="btn btn-xs btn-default" ng-click="default.appointment.confirm=1" ng-class="default.appointment.confirm==1 ? 'active' : ''">Si</label>
						<label class="btn btn-xs btn-default" ng-click="default.appointment.confirm=0" ng-class="default.appointment.confirm==0 ? 'active' : ''">No</label>
					</div>
				</div>
			</div>
			<div class="form-group form-group-sm" ng-hide="default.appointment.confirm==1">
				<label class="col-md-3 control-label">Mensaje</label>
				<div class="col-md-9">
					<input type="text"
					class="form-control input-sm"
					placeholder=""
					ng-model="default.appointment.reminder_message" />
				</div>
			</div>
			<div class="form-group form-group-sm" ng-show="default.last_communications.length">
				<label class="col-md-3 control-label">Ultimos recordatorios</label>
				<div class="col-md-9">
					<span class="label label-info" ng-repeat="reminder in default.last_communications" style="margin-right:3px;">
						{{ reminder.notes.replace("Llamar al paciente para la cita "+default.appointment.id+", " ,"")  }}
					</span>
				</div>
			</div>
			
		</div>
	</div>
	<div class="row well well" style="margin-bottom:0px;margin-top:20px;">
		<div class="col-lg-12 text-right" style="margin-bottom:0px;">
			<button type="submit" class="btn btn-primary submit" > Enviar </button>
		</div>
	</div>
<?php echo form_close(); ?>
