
<div class="row" >
	<div class="col-lg-6">
		<div class="form-group form-group-sm">
			<label class="col-md-4 control-label"> <i class="fa fa-question-circle-o" data-toggle="tooltip" title="16. FECHAS EN QUE EL PACIENTE NO PUDO TRABAJAR EN LA OCUPACIÓN ACTUAL"></i>  Paciente incapaz de trabajar</label>
			<div class="col-md-8">
				<div class="create-datepicker-range input-daterange input-group" id="datepicker">
				    <input ng-model="data.billing.date_patient_work_from" readonly="true" type="text" ng-disabled="data.not_edit" class="input-sm form-control">
				    <span class="input-group-addon" style="font-size:12px;">a</span>
				    <input  ng-model="data.billing.date_patient_work_to" readonly="true" type="text" ng-disabled="data.not_edit" class="input-sm form-control">
				</div>
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-md-4 control-label"> <i class="fa fa-question-circle-o" data-toggle="tooltip" title=""></i>
				Tipo de proveedor
			</label>
			<div class="col-md-8">
				<label class="radio " >
					<input value="1" ng-model="data.billing.type_provider" type="radio" name="type_provider"/>
					<span>Seleccionar proveedor </span>
				</label>
				<label class="radio" >
					<input value="0" ng-model="data.billing.type_provider" type="radio" name="type_provider"/>
					<span>Administrador</span>
				</label>
				<br>
				<div ng-show="data.billing.type_provider==1">
					<select style="width: 100%;" class="form-control" ng-model="data.billing.select_provider" >
						<option value="|">Seleccionar proveedor</option>
						<?php foreach ($_['providers'] as $item) : 
							$selectProvider = $item['digital_signature'].'|'.$item['medic_npi'];
							?>
							<option value="<?= $selectProvider?>"><?php echo $item['digital_signature'].', '. $item['medic_npi']; ?></option>	
						<?php endforeach; ?>
					</select>
				</div>
				<div ng-show="data.billing.type_provider==0" ng-cloak>
					<p><b>Nombre: </b>{{ data.providerManager.signature }} <b>NPI:</b> {{data.providerManager.npi}} </p>
				</div>
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-md-4 control-label"> <i class="fa fa-question-circle-o" data-toggle="tooltip" title="19. INFORMACIÓN ADICIONAL DE LA RECLAMACIÓN (Designada por NUCC)"></i> Reclamación adicional</label>
			<div class="col-md-8">
				<input type="text" ng-readonly="data.not_edit" ng-model="data.billing.aditional_claim" class="form-control input-sm" placeholder="Designated by NUCC" />
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-lg-12 control-label" style="text-align: center; margin-bottom: 5px;"> <i class="fa fa-question-circle-o" data-toggle="tooltip" title="21. DIAGNÓSTICO O NATURALEZA DE LA ENFERMEDAD O LESIÓN"></i> Diagnóstico o naturaleza de la enfermedad o lesión</label>
			<div class="col-md-6"><input type="text" ng-readonly="data.not_edit"  ng-model="data.billing.diagnosis_illness_a" class="form-control input-sm" placeholder="A"></div>
			<div class="col-md-6"><input type="text" ng-readonly="data.not_edit"  ng-model="data.billing.diagnosis_illness_b" class="form-control input-sm" placeholder="B"></div>
			<div class="col-md-6"><input type="text" ng-readonly="data.not_edit"  ng-model="data.billing.diagnosis_illness_c" class="form-control input-sm" placeholder="C"></div>
			<div class="col-md-6"><input type="text" ng-readonly="data.not_edit"  ng-model="data.billing.diagnosis_illness_d" class="form-control input-sm" placeholder="D"></div>
			<div class="col-md-6"><input type="text" ng-readonly="data.not_edit"  ng-model="data.billing.diagnosis_illness_e" class="form-control input-sm" placeholder="E"></div>
			<div class="col-md-6"><input type="text" ng-readonly="data.not_edit"  ng-model="data.billing.diagnosis_illness_f" class="form-control input-sm" placeholder="F"></div>
			<div class="col-md-6"><input type="text" ng-readonly="data.not_edit"  ng-model="data.billing.diagnosis_illness_g" class="form-control input-sm" placeholder="G"></div>
			<div class="col-md-6"><input type="text" ng-readonly="data.not_edit"  ng-model="data.billing.diagnosis_illness_h" class="form-control input-sm" placeholder="H"></div>
			<div class="col-md-6"><input type="text" ng-readonly="data.not_edit"  ng-model="data.billing.diagnosis_illness_i" class="form-control input-sm" placeholder="I"></div>
			<div class="col-md-6"><input type="text" ng-readonly="data.not_edit"  ng-model="data.billing.diagnosis_illness_j" class="form-control input-sm" placeholder="J"></div>
			<div class="col-md-6"><input type="text" ng-readonly="data.not_edit"  ng-model="data.billing.diagnosis_illness_k" class="form-control input-sm" placeholder="K"></div>
			<div class="col-md-6"><input type="text" ng-readonly="data.not_edit"  ng-model="data.billing.diagnosis_illness_l" class="form-control input-sm" placeholder="L"></div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="form-group form-group-sm">
			<label class="col-md-4 control-label"> <i class="fa fa-question-circle-o" data-toggle="tooltip" title="20. LABORATORIO EXTERNO?"></i>  Laboratorio externo</label>
			<div class="col-md-8">
				<label class="radio " >
					<input value="Yes" ng-model="data.billing.outside_lab" ng-disabled="data.not_edit" type="radio" name="outside_lab"/>
					<span>Si</span>
				</label>
				<label class="radio" >
					<input value="No" ng-model="data.billing.outside_lab" ng-disabled="data.not_edit" type="radio" name="outside_lab"/>
					<span>No</span>
				</label>
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-md-4 control-label"> <i class="fa fa-question-circle-o" data-toggle="tooltip" title="20. $ CARGOS"></i>  Tarifa de laboratorio externo</label>
			<div class="col-md-8">
				<input type="text" ng-readonly="data.not_edit || data.billing.outside_lab==='No' " ng-model="data.billing.outside_lab_fee" class="form-control input-sm" placeholder="$ 0.0" />
			</div>
		</div>
		
		<div class="form-group form-group-sm">
			<label class="col-md-4 control-label"> <i class="fa fa-question-circle-o" data-toggle="tooltip" title="22. CODIGO DE REENVÍO"></i> Código de reenvío</label>
			<div class="col-md-8">
				<input type="text" ng-readonly="data.not_edit" class="form-control input-sm" ng-model="data.billing.resubmission_code" />
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-md-4 control-label">Número de referencia original</label>
			<div class="col-md-8">
				<input type="text" ng-readonly="data.not_edit" class="form-control input-sm" ng-model="data.billing.original_ref_no" />
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-md-4 control-label"> <i class="fa fa-question-circle-o" data-toggle="tooltip" title="23. NÚMERO DE AUTORIZACION PREVIA"></i>  Autorización previa</label>
			<div class="col-md-8">
				<input type="text" ng-readonly="data.not_edit" class="form-control input-sm" ng-model="data.billing.authorization_number" />
			</div>
		</div>
	</div>
</div>