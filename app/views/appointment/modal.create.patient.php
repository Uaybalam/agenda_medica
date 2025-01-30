
<?php echo form_open('#', [
		'class' => 'form-horizontal',
		'autocomplete' => 'off',
		'ng-submit' => 'submit_patient($event)',
	]); ?>
	<div class="form-group form-group-sm">
		<label class="col-md-2 control-label">Name</label>
		<div class="col-md-4">
			<input class="form-control input-sm" ng-model="default.patient.name" />
		</div>
		<label class="col-md-2 control-label">Middle name</label>
		<div class="col-md-4">
			<input class="form-control input-sm" ng-model="default.patient.middle_name" />
		</div>
	</div>

	<div class="form-group form-group-sm" >
		<label class="col-md-2 control-label">Last name</label>
		<div class="col-md-4">
			<input class="form-control input-sm" ng-model="default.patient.last_name" />
		</div>
		<label class="col-sm-2 control-label"> Gender </label>
		<div class="col-sm-4">
			<select ng-model="default.patient.gender" class="form-control input-sm">
				<option value="Male">Male</option>
				<option value="Female">Female</option>
			</select>
		</div>
	</div>

	<div class="form-group form-group-sm" >
		<label class="col-sm-2 control-label"> Phone </label>
		<div class="col-sm-4">
			<input data-mask="999 999 9999" placeholder="code + number" maxlengt="20" autocomplete="off" type="tel"  class="form-control input-sm"  ng-model="default.patient.phone" >
		</div>
		<label class="col-sm-2 control-label"> Date of birth </label>
		<div class="col-sm-4">
			<input placeholder="month / day / year" type="text" class="form-control input-sm create-datepicker"  ng-model="default.patient.date_of_birth"  />
		</div>
	</div>

	<div class="form-group form-group-sm" >
		<label class="col-sm-2 control-label"> Insurance name </label>
		<div class="col-sm-4">
			<select class="form-control input-sm" ng-model="default.patient.insurance_primary_plan_name" >
				<option value="">--Without insurance--</option>
				<option ng-repeat="insurance in insurance_plans" value="{{insurance.title}}">{{ insurance.title }}</option>
			</select>
		</div>
		<label class="col-sm-2 control-label"> Insurance ID </label>
		<div class="col-sm-4">
			<input placeholder="" type="text" class="form-control input-sm"  ng-model="default.patient.insurance_primary_identify"  />
		</div>
	</div>

	<div class="form-group" style="margin:0px;" >
		<div class="col-lg-12 text-right well well-sm">
			<button type="submit" class="btn btn-primary submit"> Submit <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> </button>
		</div>
	</div>
</form>