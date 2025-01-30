
<div class="row form-horizontal" >
	<div class="col-lg-12">
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Nombre </label>
			<div class="col-sm-9">
				<input type="text" ng-model="default.patient_emergency.emergency_name" class="form-control input-sm"   />
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Segundo nombre </label>
			<div class="col-sm-9">
				<input type="text" ng-model="default.patient_emergency.emergency_middle_name" class="form-control input-sm"   />
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Apellidos </label>
			<div class="col-sm-9">
				<input type="text" ng-model="default.patient_emergency.emergency_last_name" class="form-control input-sm"   />
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Tipo de relación	 </label>
			<div class="col-sm-9">
				<input type="text" ng-model="default.patient_emergency.emergency_relationship" class="form-control input-sm" placeholder=""   />
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Genero </label>
			<div class="col-sm-9">
				<select class="form-control input-sm" ng-model="default.patient_emergency.emergency_gender">
					<option value="">No especificado</option>
					<option value="Male">Masculino</option>
					<option value="Female">Femenino</option>
				</select>
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Teléfono </label>
			<div class="col-sm-4">
				<input  data-mask="999 999 9999" placeholder="(lada) numero" maxlengt="20" autocomplete="off" type="tel"  class="form-control input-sm"   ng-model="default.patient_emergency.emergency_phone" >
			</div>
			<label class="col-sm-1 control-label"> Teléfono Alterno </label>
			<div class="col-sm-4">
				<input  data-mask="999 999 9999" placeholder="(lada) numeror" maxlengt="20" autocomplete="off" type="tel"  class="form-control input-sm"   ng-model="default.patient_emergency.emergency_phone_alt" >
			</div>
		</div>
		
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Dirección </label>
			<div class="col-sm-4">
				<input  autocomplete="off" type="text"  class="form-control input-sm"   ng-model="default.patient_emergency.emergency_address" />
			</div>
			<label class="col-sm-1 control-label"> Codigo postal </label>
			<div class="col-sm-4">
				<input placeholder="Autocompleta ciudad/estado"  autocomplete="off" type="text"  class="form-control input-sm"   ng-model="default.patient_emergency.emergency_address_zipcode"  ng-change="changeZipCode.toEmergency(default.patient_emergency.emergency_address_zipcode)" >
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Ciudad </label>
			<div class="col-sm-3">
				<input type="text" ng-model="default.patient_emergency.emergency_address_city" class="form-control input-sm"   />
			</div>
			<label class="col-sm-3 control-label"> Estado </label>
			<div class="col-sm-3">
				<input type="text" ng-model="default.patient_emergency.emergency_address_state" class="form-control input-sm"   />
			</div>
		</div>

	</div>
</div>
<div class="row"> 
	<div class="col-sm-12 text-right well well-sm" style="margin:0px;"> 
		<button type="button" ng-click="action_emergency.submit()" class="btn btn-primary submit"> Actualizar </button> 
	</div> 
</div>