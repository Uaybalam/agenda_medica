<?php echo form_open('/encounter/update/education/',[
		'class' => 'form-horizontal',
		'autocomplete' => 'off',
		'ng-submit' => 'action_education.submit($event)'
	]); ?>
		<div class="row">
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Educación del paciente</label>
				<div class="col-sm-9">
					<table class="table table-striped table-condensed">
						<tr ng-repeat="educations in data.catalog_educations" ><td style="margin:0px;padding:0px;">
							<label> 
								<input class="trigger-pt-educations" ng-click="action_education.toggle(educations.name, action_education.data)"
									type="checkbox"
									ng-false-value="" 
									ng-true-value="educations.name" 
									ng-checked="action_education.data.indexOf(educations.name) > -1"
								> {{ educations.name }} </label>
						</td></tr>
					</table>
				</div>
			</div>
		</div>
		<div class="row" style="margin-bottom:0px;">
			<div class="col-lg-12 text-right well well-sm" style="margin-bottom:0px;">
				<a href="/settings" class="btn btn-success"> Agregar </a>
				<button type="submit" class="btn btn-primary"> Guardar </button>
			</div>
		</div>
	<?php echo form_close(); ?>
	
	