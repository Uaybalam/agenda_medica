<?php echo form_open('/appointment/arrival/',[
		'class' => 'form-horizontal',
		'ng-submit' => 'action_appointment.submit($event)',
		'autocomplete' => 'off'
	]); ?>
	<div class="row">
		<div class="col-lg-12" >
			<label >Paciente </label>
		</div>
		<div class="col-lg-12" >
			<input type="text" 
				disabled="disabled"
				class="form-control"
				ng-model="default.appointment.patient" />
		</div>
	</div>
	<div class="row" style="margin-bottom:0px;margin-top:20px;">
		<div class="col-lg-12 text-right well well-sm" style="margin-bottom:0px;">
			<button type="submit" class="btn btn-primary submit" > Si </button>
		</div>
	</div>
<?php echo form_close(); ?>
