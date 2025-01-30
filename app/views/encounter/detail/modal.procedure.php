<?php echo form_open('/encounter/update/procedure/',[
		'class' => 'form-horizontal',
		'autocomplete' => 'off',
		'ng-submit' => 'action_procedure.submit($event)'
	]); ?>
		<div class="row">
			<div class="form-group">
				<label class="col-sm-3 control-label">Procedures</label>
				<div class="col-sm-9">
					<input type="text"  class="form-control" ng-model="default.encounter.procedure_text">
				</div>
			</div>
		</div>
		<div class="row well well" style="margin-bottom:0px;">
			<div class="col-lg-12 text-right" style="margin-bottom:0px;">
				<button type="submit" class="btn btn-primary"> Submit </button>
			</div>
		</div>
	<?php echo form_close(); ?>
	
	