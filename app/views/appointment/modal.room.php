<?php echo form_open('/appointment/room/',[
		'class' => 'form-horizontal',
		'ng-submit' => 'action_appointment.submit($event)',
		'autocomplete' => 'off'
	]); ?>
	<div class="row">
		<div class="col-lg-12" >
			<input type="text"
				class="form-control"
				placeholder="Agregar nombre o numero de cuarto"
				ng-model="default.appointment.room" />
		</div>
	</div>
	<div class="row well well" style="margin-bottom:0px;margin-top:20px;">
		<div class="col-lg-12 text-right" style="margin-bottom:0px;">
			<button type="submit" class="btn btn-primary submit" > Asignar Cuarto</button>
		</div>
	</div>
<?php echo form_close(); ?>
