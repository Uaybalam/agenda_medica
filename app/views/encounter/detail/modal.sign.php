<?php echo form_open('/encounter/sign/',[
		'class' => 'form-horizontal',
		'ng-submit' => 'action_sign.submit($event)',
		'autocomplete' => 'off'
	]); ?>
		<div class="row" style="margin-bottom:20px;">
			<label class="col-sm-12">Siguiente cita</label>
			<div class="col-sm-12" >
				<input type="text" class="form-control" placeholder="Ejemplo: 1 Semana" ng-model="default.sign.next_appointment" >
			</div>
		</div>
		<div class="row" style="margin-bottom:20px;">
			<label class="col-sm-12">Ingresa tu pin</label>
			<div class="col-sm-12" >
				<input type="text" class="form-control input-password" ng-model="default.sign.pin" >
				<span class="help-block"> Tu puedes editar el PIN en <a href="/user/profile"> Perfil </a > </span>
			</div>
		</div>	
		<div class="row" style="margin-bottom:0px;">
			<div class="col-lg-12 text-right well well-sm" style="margin-bottom:0px;">
				<button type="submit" class="btn btn-primary submit" > Confirmar firma</button>
			</div>
		</div>
	<?php echo form_close(); ?>
