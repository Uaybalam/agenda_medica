<div class="row">  
	<div class="col-lg-6">
		<div class="form-group form-group-sm">
			<label class="col-lg-4 control-label">Tipo de factura</label>
			<div class="col-lg-8" style="font-size:12px;">
				<label style="margin-right:3px;" ng-repeat="(key, value) in data.plan_types" class="radio">
					<input ng-disabled="data.not_edit" type="radio" value="{{key}}" ng-model="data.billing.plan_type" name="type_medicare"/>
					<span>{{ value }}</span>
				</label>
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-lg-4 control-label"> <i class="fa fa-question-circle-o" data-toggle="tooltip" title="6. RELACIÓN DEL PACIENTE CON EL ASEGURADO" ></i> Relacion del paciente</label>
			<div class="col-lg-8" style="font-size:12px;padding-top:5px;">
				<label style="margin-right:3px;" class="radio" ng-repeat="(key, value) in data.patient_relationship">
					<input  ng-disabled="data.not_edit" type="radio" value="{{key}}" ng-model="data.billing.patient_relationship" name="relationship_to_insured" >
					<span>{{ value }}</span>
				</label>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<label class="col-lg-4 control-label">  <i class="fa fa-question-circle-o" data-toggle="tooltip" title="5. DIRECCIÓN DEL PACIENTE (Número y Calle)" ></i> Dirección del paciente</label>
			<div class="col-lg-8">
				<input ng-model="data.billing.patient_address" type="text" ng-readonly="data.not_edit" placeholder="Numero, Calle" class="form-control input-sm" />
			</div>
		</div>

		<div class="form-group form-group-sm">
			<label class="col-lg-3 control-label">Ciudad</label>
			<div class="col-lg-3">
				<input type="text" ng-readonly="data.not_edit" ng-model="data.billing.patient_city" class="form-control input-sm" />
			</div>
			<label class="col-lg-3 control-label">Estado</label>
			<div class="col-lg-3">
				<input type="text" ng-readonly="data.not_edit" ng-model="data.billing.patient_state" class="form-control input-sm" />
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-lg-3 control-label">Codigo Postal</label>
			<div class="col-lg-3">
				<input type="text" ng-readonly="data.not_edit" ng-model="data.billing.patient_zipcode" class="form-control input-sm" />
			</div>
			<label class="col-lg-3 control-label">Teléfono</label>
			<div class="col-lg-3">
				<input type="text" ng-readonly="data.not_edit" ng-model="data.billing.patient_telephone" class="form-control input-sm" placeholder="(Lada) numero" />
			</div>
		</div>
		
		<div class="form-group form-group-sm">
			<label class="col-lg-6 control-label">¿Hay otro beneficiario del plan de salud?</label>
			<div class="col-lg-6">
				<label class="radio" >
					<input ng-disabled="data.not_edit" type="radio" value="Yes" ng-model="data.billing.other_benefit_plan" name="other_benefit_plan"/>
					<span>Si</span>
				</label>
				<label class="radio" >
					<input ng-change="change_benefit_plan()" ng-disabled="data.not_edit" type="radio" value="No" ng-model="data.billing.other_benefit_plan" name="other_benefit_plan"/>
					<span>No</span>
				</label>
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-lg-4 control-label"> <i class="fa fa-question-circle-o" data-toggle="tooltip" title="9. NOMBRE DEL OTRO ASEGURADO (Apellido, Nombre, Inicial del Segundo Nombre)" ></i> Nombre del otro asegurado</label>
			<div class="col-lg-8">
				<div class="form-input-group">
					<input   ng-model="data.billing.insured_other_last_name" class="form-control input-sm input-col-3" placeholder="Apellido" type="text" ng-readonly="data.not_edit || data.billing.other_benefit_plan==='No' " />
					<input   ng-model="data.billing.insured_other_first_name" class="form-control input-sm input-col-3" placeholder="Nombre" type="text" ng-readonly="data.not_edit || data.billing.other_benefit_plan==='No' " />
					<input   ng-model="data.billing.insured_other_middle_initial" class="form-control input-sm input-col-3" placeholder="Segundo nombre" type="text" ng-readonly="data.not_edit || data.billing.other_benefit_plan==='No' " />
				</div>
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-lg-4 control-label"> <i class="fa fa-question-circle-o" data-toggle="tooltip" title="9.a NÚMERO DE PÓLIZA O NÚMERO DE GRUPO DEL OTRO ASEGURADO" ></i> Póliza del otro asegurado</label>
			<div class="col-lg-8">
				<input  ng-model="data.billing.insured_other_policy" class="form-control input-sm" type="text" ng-readonly="data.not_edit || data.billing.other_benefit_plan==='No' " placeholder="Número de póliza o grupo" />
			</div>
		</div>
		<!--
		<div class="form-group form-group-sm">
			<label class="col-lg-4 control-label"> 
				<i class="fa fa-question-circle-o" data-toggle="tooltip" title="9.d INSURANCE PLAN MAME OR PROGRAM NAME" ></i> Insurance plan name
			</label>
			<div class="col-lg-8">
				<select ng-model="data.billing.insured_other_insurance_plan_name"  class="form-control input-sm" ng-readonly="data.not_edit || data.billing.other_benefit_plan==='No' " placeholder="Or program name">
					<?php foreach ($_['setting_bill_insurance_plans'] as $ins) : ?>
						<option value="<?= $ins?>"><?= $ins?></option>
					<?php endforeach;?>
				</select>
			</div>
		</div>
		-->
		
	</div>
	<div class="col-lg-6">
		<div class="form-group form-group-sm">
			<label class="col-lg-4 control-label text-danger"> Nombre de seguro </label>
			<div class="col-lg-8">
				<select class="form-control form-control-sm" ng-model="data.billing.insurance_title" >
					<?php foreach ($_['options_insurances'] as $key => $value) : ?>
						<option value="<?= $value['name']?>" ><?= $value['name']?></option>
					<?php endforeach;?>
				</select>
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-lg-4 control-label text-danger"> Numero de seguro </label>
			<div class="col-lg-8">
				<input ng-model="data.billing.insurance_number" type="text" ng-readonly="data.not_edit" placeholder="Insurance" class="form-control input-sm" />
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-lg-4 control-label"> <i class="fa fa-question-circle-o" data-toggle="tooltip" title="4. NOMBRE DEL ASEGURADO (Apellido, Nombre, Segundo Nombre)" ></i> Nombre de asegurado</label>
			<div class="col-lg-8">
				<div class="form-input-group">
					<input  ng-model="data.billing.insured_last_name" class="form-control input-sm input-col-3" placeholder="Apellido" type="text" ng-readonly="data.not_edit" />
					<input  ng-model="data.billing.insured_first_name" class="form-control input-sm input-col-3" placeholder="Nombre" type="text" ng-readonly="data.not_edit" />
					<input  ng-model="data.billing.insured_middle_initial" class="form-control input-sm input-col-3" placeholder="Segundo nombre" type="text" ng-readonly="data.not_edit" />
				</div>
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-lg-4 control-label"> <i class="fa fa-question-circle-o" data-toggle="tooltip" title="7. DIRECCIÓN DEL ASEGURADO (Número, Calle)" ></i> Dirección del seguro</label>
			<div class="col-lg-8">
				<input ng-model="data.billing.insured_address" type="text" ng-readonly="data.not_edit" placeholder="Numero, calle" class="form-control input-sm" />
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-lg-3 control-label">Ciudad</label>
			<div class="col-lg-3">
				<input type="text" ng-readonly="data.not_edit" ng-model="data.billing.insured_city" class="form-control input-sm" />
			</div>
			<label class="col-lg-3 control-label">Estado</label>
			<div class="col-lg-3">
				<input type="text" ng-readonly="data.not_edit" ng-model="data.billing.insured_state" class="form-control input-sm" />
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-lg-3 control-label">Código Postal</label>
			<div class="col-lg-3">
				<input type="text" ng-readonly="data.not_edit" ng-model="data.billing.insured_zipcode" class="form-control input-sm" />
			</div>
			<label class="col-lg-3 control-label">Teléfono</label>
			<div class="col-lg-3">
				<input type="text" ng-readonly="data.not_edit" ng-model="data.billing.insured_telephone" class="form-control input-sm" placeholder="(lada) numero" />
			</div>
		</div>


		
		<div class="form-group form-group-sm">
			<label class="col-lg-12 control-label" style="text-align: center;"> <i class="fa fa-question-circle-o" data-toggle="tooltip" title="10. LA CONDICIÓN DEL PACIENTE ESTÁ RELACIONADA CON" ></i> Paciente con condición relacionada a</label>
			<label class="col-lg-4 control-label"> <i class="fa fa-question-circle-o" data-toggle="tooltip" title="10.a  EMPLEADO? (Actual o Previo)" ></i> Empleado</label>
			<div class="col-lg-8" style="padding-top:5px;">
				<label class="radio " >
					<input ng-disabled="data.not_edit" type="radio" value="Yes" ng-model="data.billing.patient_condition_employment" name="condition_employment"/>
					<span>Si</span>
				</label>
				<label class="radio" >
					<input ng-disabled="data.not_edit" type="radio" value="No" ng-model="data.billing.patient_condition_employment" name="condition_employment"/>
					<span>No</span>
				</label>
			</div>
			<label class="col-lg-4 control-label"><i class="fa fa-question-circle-o" data-toggle="tooltip" title="10.b ¿ACCIDENTE AUTOMOVILISTICO?" ></i> Accidente automovilistico</label>
			<div class="col-lg-3"  style="padding-top:5px;" >
				<label class="radio" >
					<input ng-disabled="data.not_edit" type="radio" value="Yes" ng-model="data.billing.patient_condition_autoaccident" name="condition_autoaccident"/>
					<span>Si</span>
				</label>
				<label class="radio" >
					<input ng-disabled="data.not_edit" type="radio" value="No" ng-model="data.billing.patient_condition_autoaccident" name="condition_autoaccident"/>
					<span>No</span>
				</label>
			</div>
			<div class="col-lg-5">
				<input type="text" ng-readonly="data.not_edit" ng-model="data.billing.patient_condition_autoaccident_place" class="form-control input-sm" placeholder="Lugar/Estado" />
			</div>
			<div class="clearfix"></div>
			<label class="col-lg-4 control-label"> <i class="fa fa-question-circle-o" data-toggle="tooltip" title="10.c ¿OTRO ACCIDENTE?" ></i>  Otro accidente</label>
			<div class="col-lg-3"  style="padding-top:5px;" >
				<label class="radio" >
					<input ng-disabled="data.not_edit" type="radio" value="Yes" ng-model="data.billing.patient_condition_otheraccident" name="condition_otheraccident"/>
					<span>Si</span>
				</label>
				<label class="radio" >
					<input ng-disabled="data.not_edit" type="radio" value="No" ng-model="data.billing.patient_condition_otheraccident" name="condition_otheraccident"/>
					<span>No</span>
				</label>
			</div>
			<div class="clearfix"></div>
			<label class="col-lg-4 control-label"> <i class="fa fa-question-circle-o" data-toggle="tooltip" title="10.d CÓDIGOS DE RECLAMACIÓN (Designados por la NUCC)" ></i> Códigos de reclamación</label>
			<div class="col-lg-8"  style="padding-top:5px;" >
				<input type="text" ng-readonly="data.not_edit" ng-model="data.billing.patient_condition_claimcodes" class="form-control input-sm" placeholder="Designated by NUCC" />
			</div>
		</div>
	</div>
</div>