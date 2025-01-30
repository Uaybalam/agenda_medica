<div class="row form-horizontal" >
	<div class="col-md-6">
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Nombre <span ng-show="!default.patient_about.name" class="text-danger">*</span> </label>
			<div class="col-sm-9">
				<input type="text" ng-model="default.patient_about.name" class="form-control input-sm"   />
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Segundo Nombre </label>
			<div class="col-sm-9">
				<input type="text" ng-model="default.patient_about.middle_name" class="form-control input-sm"   />
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Apellidos <span ng-show="!default.patient_about.last_name" class="text-danger">*</span></label>
			<div class="col-sm-9">
				<input type="text" ng-model="default.patient_about.last_name" class="form-control input-sm"   />
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Genero   </label>
			<div class="col-sm-4">
				<div class="btn-group">
    				<label class="btn btn-default btn-sm" ng-class="(default.patient_about.gender == 'Male') ? 'active' : ''">
        				<input type="radio" class="hide" ng-model="default.patient_about.gender" value="Male" >Masculino 
    				</label>
    				<label class="btn btn-default btn-sm" ng-class="(default.patient_about.gender == 'Female') ? 'active' : ''">
    					<input type="radio" class="hide" ng-model="default.patient_about.gender" value="Female" >Femenino 
    				</label>
				</div>
			</div>
			<label class="col-sm-2 control-label"> Estado civil </label>
			<div class="col-sm-3">
				<select class="form-control input-sm" ng-model="default.patient_about.marital_status">
					<option ng-repeat="(key , value) in data.settings_marital_status" value="{{key}}">{{ value }}</option>
				</select>
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Teléfono </label>
			<div class="col-sm-3">
				<input  data-mask="999 999 9999" placeholder="(Code) number" maxlengt="20" autocomplete="off" type="tel"  class="form-control input-sm"   ng-model="default.patient_about.phone" >
			</div>
			<div class="col-sm-6">
				<div class="input-group input-group-sm"> 
					<span class="input-group-addon" >Descripción</span> 
					<input class="form-control input-sm"  ng-model="default.patient_about.phone_memo"  > 
				</div>
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Teléfono alterno </label>
			<div class="col-sm-3">
				<input   ng-model="default.patient_about.phone_alt" data-mask="999 999 9999" placeholder="(Lada) Numero" maxlengt="20" autocomplete="off" type="tel"  class="form-control input-sm"  >
			</div>
			<div class="col-sm-6">
				<div class="input-group input-group-sm"> 
					<span class="input-group-addon" >Descripción</span> 
					<input  ng-model="default.patient_about.phone_alt_memo" class="form-control input-sm"  > 
				</div>
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-sm-3 control-label"> Fecha de nacimiento </label>
			<div class="col-sm-9">
				<input  ng-model="default.patient_about.date_of_birth" placeholder="mes / día / año" type="text" class="form-control input-sm create-datepicker"  />
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group form-group-sm">
			<label class="col-sm-3 control-label">¿Como nos encontraste?<span ng-show="!default.patient_about.how_found_us" class="text-danger">*</span></label>
			<div class="col-sm-9">
				<input type="text" ng-model="default.patient_about.how_found_us" class="form-control input-sm" />
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-sm-3 control-label"> Email </label>
			<div class="col-sm-9">
				<input type="text" ng-model="default.patient_about.email" autocomplete="off" class="form-control input-sm"  placeholder="email@domain.com" />
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-sm-3 control-label"> Etnicidad </label>
			<div class="col-sm-9">
				<input type="text" ng-model="default.patient_about.ethnicity" class="form-control input-sm " />
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-sm-3 control-label"> Tipo Sanguineo	 </label>
			<div class="col-sm-9">
				<input type="text" ng-model="default.patient_about.blood_type" class="form-control input-sm"  />
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label">Idioma</label>	
			<div class="col-sm-9">
				<input type="text" ng-model="default.patient_about.language" class="form-control input-sm" />
				<span class="help-block">El primer idioma debe ser el nativo</span>
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label">Descuento de empresa</label>	
			<div class="col-sm-9">
				<input type="text" ng-model="default.patient_about.discount_type" class="form-control input-sm" />
				<span class="help-block">Compañia o relacion del descuento</span>
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-sm-9 control-label">¿Necesita un interprete? <span class="text-danger" ng-show="!default.patient_about.interpreter_needed">*</span></label>
			<div class="col-sm-3"> 
				<select class="form-control input-sm" ng-model="default.patient_about.interpreter_needed">
					<option value="Yes">Si</option>
					<option value="No">No</option>
				</select>
			</div>
		</div>
		<!--<div class="form-group form-group-sm">
			<label class="col-sm-9 control-label">Was advance directive offered <span class="text-danger" ng-show="!default.patient_about.advanced_directive_offered">*</span></label>
			<div class="col-sm-3"> 
				<select class="form-control input-sm" ng-model="default.patient_about.advanced_directive_offered">
					<option value="Yes">Yes</option>
					<option value="No">No</option>
				</select>
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-sm-9 control-label">Directive taken <span class="text-danger" ng-show="!default.patient_about.advanced_directive_taken">*</span></label>
			<div class="col-sm-3"> 
				<select class="form-control input-sm" ng-model="default.patient_about.advanced_directive_taken" >
					<option value="Yes">Yes</option>
					<option value="No">No</option>
				</select>
			</div>
		</div> -->
	</div>
</div>
<div class="row"> 
	<div class="col-sm-12 text-right well well-sm" style="margin:0px;"> 
		<button type="button" ng-click="action_about.submit()" class="btn btn-primary submit"> Actualizar </button> 
	</div> 
</div>