<?php echo form_open('/appointment/chartup/',[
		'class' => 'form-horizontal',
		'ng-submit' => 'action_appointment.submit($event)',
		'autocomplete' => 'off'
	]); ?>
	<div class="row">
		<div class="col-lg-12" >
			<div class="form-group">
				<label class="col-md-3 control-label"> Paciente </label>
				<div class="col-md-9">
					<input 
						type="text" 
						disabled="disabled"
						class="form-control"
						ng-model="default.appointment.patient" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"> Tipo de visita </label>
				<div class="col-md-9">
					<input 
						type="text" 
						disabled="disabled"
						class="form-control"
						ng-model="default.appointment.visit_type" />
				</div>
			</div>
		</div>

	</div>
	<div class="row well well" style="margin-bottom:0px;margin-top:20px;">
		<div class="col-lg-12 text-right" style="margin-bottom:0px;">
			<button type="submit" class="btn btn-primary submit" > Si </button>
		</div>
	</div>
<?php echo form_close(); ?>
