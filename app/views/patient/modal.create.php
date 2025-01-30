<?php
	$insurance_plans       = isset($_['insurance_plans']) ? $_['insurance_plans'] : [];
	$settings_how_found_us = isset($_['settings_how_found_us']) ? $_['settings_how_found_us'] : "";
?>
<input type="hidden" value="<?= $settings_how_found_us;?>" id="settings_how_found_us" />

<?php echo form_open('#', [ 
		'autocomplete' => 'off',
		'ng-submit' => 'submit_patient($event)',
	]); ?>
	<div class="form-horizontal">
		<div class="form-group form-group-sm">
			<label class="col-md-2 control-label">Nombre</label>
			<div class="col-md-4">
				<input class="form-control" ng-model="default.patient.name" ng-change="similarPatients()" />
			</div>
			<label class="col-md-2 control-label">Segundo Nombre</label>
			<div class="col-md-4">
				<input class="form-control" ng-model="default.patient.middle_name" ng-change="similarPatients()" />
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-md-2 control-label">Apellido</label>
			<div class="col-md-4">
				<input class="form-control" ng-model="default.patient.last_name" ng-change="similarPatients()" />
			</div>
			<label class="col-sm-2 control-label"> Genero </label>
			<div class="col-sm-4">
				<select ng-model="default.patient.gender" class="form-control">
					<option value="Male">Masculino</option>
					<option value="Female">Femenino</option>
				</select>
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-2 control-label"> Teléfono </label>
			<div class="col-sm-4">
				<input data-mask="999 999 9999" placeholder="code + number" maxlengt="20" autocomplete="off" type="tel"  class="form-control"  ng-model="default.patient.phone" >
			</div>
		<!--	<label class="col-sm-2 control-label"> Phone Memo</label>
			<div class="col-sm-4">
				<input type="text"  class="form-control"  ng-model="default.patient.phone_memo" >
			</div>-->
		</div> 
		<div class="form-group form-group-sm" >
			<label class="col-sm-2 control-label">Fecha de nacimiento</label>
			<div class="col-sm-4">
				<input placeholder="month / day / year" type="text" class="form-control create-datepicker"  ng-model="default.patient.date_of_birth"  ng-change="similarPatients()" />
			</div>
			
			<label class="col-sm-2 control-label">¿Cómo nos encontró?</label>
			<div class="col-sm-4" >
				<input type="text"  class="form-control" ng-model="default.patient.how_found_us" >
			</div>	
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-2 control-label"> Nombre de Seguro</label>
			<div class="col-sm-4">
				<select class="form-control input-sm" ng-model="default.patient.insurance_primary_plan_name" >
					<option value="">--Sin seguro--</option>
					<?php foreach ($insurance_plans as $key => $value) : ?> 
						<option value="<?= $value['name']; ?>"><?= $value['name']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<label class="col-sm-2 control-label"> Numero de Seguro</label>
			<div class="col-sm-4">
				<input placeholder="" type="text" class="form-control input-sm"  ng-model="default.patient.insurance_primary_identify"  />
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-sm-4 control-label"> Necesita un interprete</label>
			<div class="col-sm-2">
				<label class="radio">
					<input  type="radio" value="Yes" ng-model="default.patient.interpreter_needed"> 
					<span>Si</span>
				</label>
				<label class="radio">
					<input  type="radio" value="No" ng-model="default.patient.interpreter_needed"> 
					<span>No</span> 
				</label>
			</div>
		</div>
		<!--<div class="form-group form-group-sm">
			<label class="col-sm-4 control-label"> Was advance directive offered</label>
			<div class="col-sm-2">
				<label class="radio">
					<input  type="radio" value="Yes" ng-model="default.patient.advanced_directive_offered" > 
					<span>Yes</span>
				</label>
				<label class="radio">
					<input  type="radio" value="No" ng-model="default.patient.advanced_directive_offered" > 
					<span>No</span> 
				</label>
			</div>
			<label class="col-sm-2 control-label">Taken</label>
			<div class="col-sm-4">
				<label class="radio">
					<input  type="radio" value="Yes" ng-model="default.patient.advanced_directive_taken" > 
					<span>Yes</span>
				</label>
				<label class="radio">
					<input  type="radio" value="No" ng-model="default.patient.advanced_directive_taken" > 
					<span>No</span> 
				</label>
			</div>
		</div> -->
		<div ng-show="default.patientList.length>0">
			<p class="text-warning" ng-repeat="item in default.patientList">
			Paciente similar creado el <b>{{ ngHelper.humanDate(item.create_at) }}</b> con ID <b><a data-toggle="tooltip" title="Demographics" target="_blank" href="/patient/detail/{{item.id}}">{{ item.id }}</a></b> y numero de teléfono <b>{{item.phone}}</b>
			</p>
		</div>
	</div>
	<div class="row" >
		<div class="col-lg-12 text-right well well-sm" style="margin-bottom:0px !important;">
			<button type="submit" class="btn btn-primary"> Guardar <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> </button>
		</div>
	</div>
</form>