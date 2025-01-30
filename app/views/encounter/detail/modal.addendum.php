<?php echo form_open('/encounter/addendum/create/',[
		'class' => 'form-horizontal',
		'ng-submit' => 'action_addendum.submit($event)',
		'autocomplete' => 'off'
	]); ?>
		
		<div class="row">
			<div class="form-group">
				<label class="col-sm-3 control-label">Notas</label>
				<div class="col-sm-9">
					<textarea ng-model="default.addendum.notes"  class="form-control" placeholder="Notas addendum"  rows="6"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Pin de usuario</label>
				<div class="col-sm-9">	
					<input type="text" style="-webkit-text-security: disc;" placholder="required confirm with password"  class="form-control input-password" ng-model="default.addendum.password" >
					<span class="help-block"> Tu puedes editar el PIN en <a href="/user/profile"> Perfil </a > </span>
				</div>
			</div>
		</div>
		<div class="row" style="margin-bottom:0px;">
			<div class="col-lg-12 text-right  well well-sm" style="margin-bottom:0px;">
				<button type="submit" class="btn btn-primary submit"> Agregar addendum </button>
			</div>
		</div>
	<?php echo form_close(); ?>
	
