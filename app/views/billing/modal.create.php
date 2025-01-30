<?php echo form_open('/encounter/createBill/',[
		'class' => 'form-horizontal',
		'autocomplete' => 'off',
		'ng-submit' => 'submitCreateBilling($event)'
	]); ?>
		<div class="form-group" >
			<label class="col-sm-3 control-label">Plan de seguro</label>
			<div class="col-sm-9" >
				<input type="text" class="form-control" ng-model="default.billing.insurance_plan" >
			</div>
		</div>
		<div class="form-group" >
			<label class="col-sm-3 control-label">Numero de seguro</label>
			<div class="col-sm-9" >
				<input type="text" class="form-control" ng-model="default.billing.insurance_id" >
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Pin de usuario</label>
			<div class="col-sm-9">	
				<input type="text" style="-webkit-text-security: disc;" placholder="required confirm with password"  class="form-control input-password" ng-model="default.billing.pin" >
				<span class="help-block"> Tu puedes editar el PIN en <a href="/user/profile"> Perfil </a > </span>
			</div>
		</div>
		<div class="row" style="margin-bottom:0px;">
			<div class="col-lg-12 text-right well well-sm" style="margin-bottom:0px;">
				<button type="submit" class="btn btn-primary submit" > Crear factura </button>
			</div>
		</div>
	<?php echo form_close(); ?>
