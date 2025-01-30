<?php echo form_open('/encounter/update/illness/',[
		'class' => 'form-horizontal',
		'autocomplete' => 'off',
		'ng-submit' => 'action_illness.submit($event)'
	]); ?>
		<div class="form-horizontal">
			<div class="form-group">
				<label class="col-sm-3 control-label">Presente historial de enfermedades</label>
				<div class="col-sm-9">
					<textarea ng-model="default.encounter.present_illness_history" class="form-control" rows="3"></textarea>
				</div>
			</div>
		</div>
		<div class="row" style="margin-bottom:0px;">
			<div class="col-lg-12 text-right well well-sm" style="margin-bottom:0px;">
				<button type="submit" class="btn btn-primary"> Enviar </button>
			</div>
		</div>
	<?php echo form_close(); ?>
	
	