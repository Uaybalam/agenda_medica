<?php

	$settings_how_found_us = isset($_['settings_how_found_us']) ? $_['settings_how_found_us'] : "";
?>
<input type="hidden" value="<?= $settings_how_found_us;?>" id="settings_how_found_us" />

<?php echo form_open('#', [
		'class' => 'form-horizontal',
		'autocomplete' => 'off',
		'ng-submit' => 'submit_patient($event)',
	]); ?>
	<div class="form-group form-group-sm">
		<label class="col-md-2 control-label">Nombre</label>
		<div class="col-md-4">
			<input class="form-control" ng-model="default.patient.name" ng-change="similarPatients()" />
		</div>
		<label class="col-md-2 control-label">Segundo nombre</label>
		<div class="col-md-4">
			<input class="form-control" ng-model="default.patient.middle_name" ng-change="similarPatients()" />
		</div>
		
	</div>


	<div class="form-group form-group-sm" >
		<label class="col-md-2 control-label">Apellidos</label>
		<div class="col-md-4">
			<input class="form-control" ng-model="default.patient.last_name" ng-change="similarPatients()" />
		</div>
		<label class="col-sm-2 control-label"> Fecha de nacimiento </label>
		<div class="col-sm-4">
			<input placeholder="mes / dia / año" type="text" class="form-control create-datepicker"  ng-model="default.patient.date_of_birth" ng-change="similarPatients()"  />
		</div>
	</div>
	
	<div class="form-group form-group-sm" >
		<label class="col-sm-2 control-label">¿Como nos encontraron?</label>
		<div class="col-sm-4" >
			<input type="text"  class="form-control" ng-model="default.patient.how_found_us" >
		</div>
		<label class="col-sm-2 control-label"> Teléfono </label>
		<div class="col-sm-4">
			<input data-mask="999 999 9999" placeholder="lada + numero" maxlengt="20" autocomplete="off" type="tel"  class="form-control"  ng-model="default.patient.phone" >
		</div>
		
	</div>
	<div class="form-group form-group-sm" >
		<div class="col-sm-1"></div>
		<div class="col-sm-10" >
			<p><i><b>Nota:</b> Esta es información básica sobre el paciente. Cuando el paciente <u>llegue</u>, es necesario completar los campos requeridos.</i></p>
		</div>
	</div>

	<div ng-show="default.patientList.length>0">
		 <p class="text-warning" ng-repeat="item in default.patientList">
		 	Paciente similar creado <b>{{ ngHelper.humanDate(item.create_at) }}</b> con identificador <b><a data-toggle="tooltip" title="Demograficos" target="_blank" href="/patient/detail/{{item.id}}">{{ item.id }}</a></b> y numero de teléfono <b>{{item.phone}}</b>
		 </p>
	</div>

	<div class="row" style="margin-bottom:0px;">
		<div class="col-lg-12 text-right  well well-sm" style="margin-bottom:0px;">
			<button type="submit" class="btn btn-primary"> Guardar <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> </button>
		</div>
	</div>
</form>