<?php echo form_open('/preventions/update/',[
		'class' => 'form-horizontal',
		'ng-submit' => 'action_preventions.stopSubmit($event)',
		'autocomplete' => 'off'
	]); ?>
	<div class="col-lg-12">
		<div class="row form-horizontal">
			<div class="form-group">
				<label class="col-sm-3 control-label">Alergias</label>
				<div class="col-sm-9" >	
					<input type="text"  class="form-control" ng-value="default.preventions.allergies" ng-model="default.preventions.allergies" >
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Alcohol</label>
				<div class="col-sm-9" >
					<input type="text"  class="form-control" ng-model="default.preventions.alcohol" >
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Medicamentos</label>
				<div class="col-sm-9" >
					<input type="text"  class="form-control" ng-model="default.preventions.drugs" >
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Tabaco</label>
				<div class="col-sm-9" >
					<input type="text"  class="form-control" ng-model="default.preventions.tobacco" >
				</div>
			</div>
		</div>
	</div>	
		<div class="row" style="margin-bottom:0px;">
			<div class="col-lg-12 text-right  well well-sm" style="margin-bottom:0px;">
				<button type="button" ng-click="action_preventions.submit($event)" class="btn btn-primary submit" > Actualizar </button>
			</div>
		</div>
	<?php echo form_close(); ?>
