<?php echo form_open('/encounter/update/vitals/',[
	'class' => 'form-horizontal',
	'ng-submit' => 'action_vitals.submit($event)',
	'autocomplete' => 'off'
]); ?> 
	<div class="form-horizontal">
		<div class="col-sm-3">
			<ul class="nav nav-pills nav-stacked" >
				<li class="active"><a data-toggle="tab" href="#vitals-physical" > <i class="fa fa-male"></i> Físico</a></li>
				<li><a data-toggle="tab" href="#vitals-eyes-audio" > <i class="fa fa-eye"></i>  Ojos/oídos </a></li>
				<li><a data-toggle="tab" href="#vitals-urinalysis" > <i class="fa fa-medkit"></i>  Uroanálisis </a></li>
				<li><a data-toggle="tab" href="#vitals-basic" > <i class="fa fa-h-square"></i> Motivo de consulta </a></li>
			</ul>
		</div>
		<div class="col-sm-9">
			<div class="tab-content">
				<div id="vitals-physical" class="tab-pane fade in active">
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Altura</label>
						<div class="col-sm-3">
							<div class="input-group input-group-sm" > 
								<input   type="number" step="0.01" class="form-control"  ng-change="action_vitals.calc_bmi()" ng-model="default.encounter.physical_height" >
								<span class="input-group-addon"> M. </span> 
							</div>
						</div>
						<label class="col-sm-3 control-label">Peso</label>
						<div class="col-sm-3">
							<div class="input-group input-group-sm" > 
								<input   type="number" step="0.01" class="form-control" ng-change="action_vitals.calc_bmi()" ng-model="default.encounter.physical_weight" >
								<span class="input-group-addon"> Kg </span> 
							</div>
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">IMC</label>
						<div class="col-sm-9">
							<div class="input-group input-group-sm" > 
								<input type="number" step="0.01" class="form-control" readonly="readonly" ng-model="default.encounter.physical_bmi">
								<span class="input-group-addon"> % </span> 
							</div>
							<span  ng-class="dinamicValues(default.encounter.physical_bmi,default.statement.bmi_class)">{{ dinamicValues(default.encounter.physical_bmi,default.statement.bmi_text) }}</span>
						</div>	
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Temperatura</label>
						<div class="col-sm-9">
							<div class="input-group input-group-sm"> 
								<input type="number"  step="0.01" class="form-control" ng-model="default.encounter.heart_temperature" >
								<span class="input-group-addon">&deg;F</span> 
							</div>	
							<span  ng-class="dinamicValues(default.encounter.heart_temperature, default.statement.temp_class ) ">
								{{ dinamicValues(default.encounter.heart_temperature, default.statement.temp_text ) }}
							</span>
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Presión arterial sistólica</label>
						<div class="col-sm-3">
							<input type="number" class="form-control" ng-model="default.encounter.blood_pressure_sys" >
							<span  ng-class="dinamicValues(default.encounter.blood_pressure_sys,default.statement.bp_sys_class)">{{ dinamicValues(default.encounter.blood_pressure_sys,default.statement.bp_sys_text) }}</span>
						</div>
						<label class="col-sm-3 control-label">Presión arterial diastólica</label>
						<div class="col-sm-3">
							<input type="number" class="form-control" ng-model="default.encounter.blood_pressure_dia" >
							<span  ng-class="dinamicValues(default.encounter.blood_pressure_dia,default.statement.bp_dia_class)">
								{{ dinamicValues(default.encounter.blood_pressure_dia,default.statement.bp_dia_text) }}
							</span>
						</div>
					</div>

					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Pulso</label>
						<div class="col-sm-3">
							<div class="input-group input-group-sm"> 	
								<input type="text" class="form-control" ng-model="default.encounter.heart_pulse" >
								<span class="input-group-addon input-group-addon-sm">min </span> 
							</div>
						</div>
						<label class="col-sm-3 control-label">Frecuencia respiratoria</label>
						<div class="col-sm-3">
							<div class="input-group input-group-sm"> 
								<input type="text" class="form-control" ng-model="default.encounter.heart_respiratory" >
								<span class="input-group-addon input-group-addon-sm">min</span> 
							</div>
						</div>
					</div>


					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Hemoglobina</label>
						<div class="col-sm-3">
							<input type="number" step="0.01" class="form-control" ng-model="default.encounter.heart_hemoglobin" >
						</div>
						<label class="col-sm-3 control-label">Hematocrito</label>
						<div class="col-sm-3">
							<input type="number" step="0.01" class="form-control" ng-model="default.encounter.heart_hematocrit" >
						</div>
					</div>
					<div class="form-group form-group-sm" ng-hide="data.patient.gender == 'Male'">
						<label class="col-sm-3 control-label">Ultima Mestruación</label>
						<div class="col-sm-9">
							<input type="text" ng-model="default.encounter.heart_last_menstrual_period" class="form-control" >
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Peso al nacer</label>
						<div class="col-sm-3">
							<div class="input-group input-group-sm" > 
								<input type="number" step="0.01" class="form-control" ng-model="default.encounter.physical_birth_weight" >
								<span class="input-group-addon"> Kg </span> 
							</div>
						</div>
						<label class="col-sm-3 control-label">Circunferencia de la cabeza</label>
						<div class="col-sm-3">
							<div class="input-group input-group-sm" > 
								<input  type="number" step="0.01" class="form-control" ng-model="default.encounter.heart_head_circ" >
								<span class="input-group-addon"> Cm </span> 
							</div>
						</div>
					</div>
				</div>
				
				<div id="vitals-basic" class="tab-pane fade ">
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Medicación actual</label>
						<div class="col-sm-9">
							<textarea rows="3"  class="form-control" ng-model="default.encounter.current_medications"></textarea>
						</div>
					</div>	
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Motivo de consulta</label>
						<div class="col-sm-9">
							<textarea rows="6"  class="form-control" ng-model="default.encounter.chief_complaint"></textarea>
							<div class="pull-right" style="margin-top: 8px;">
								<!-- <button type="button" class="btn btn-info btn-xs" ng-click="action_vitals.include_ins()">Preguntas INS</button>-->
							</div>
						</div>
					</div>
					<div class="col-lg-12">
						<h3 class="text-center">¿La consulta de hoy está relacionada con?</h3>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label"> Empleo </label>
						<div class="col-sm-3">
							<select ng-model="default.encounter.condition_employment" class="form-control" >
								<option value="">Sin verificar</option>
								<option value="Yes">Si</option>
								<option value="No">No</option>
							</select>
						</div>
						<label class="col-sm-3 control-label"> Accidente automovilistico </label>
						<div class="col-sm-3">
							<select ng-model="default.encounter.condition_autoaccident"  class="form-control"  >
								<option value="">Sin verificar</option>
								<option value="Yes">Si</option>
								<option value="No">No</option>
							</select>
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label"> Estado </label>
						<div class="col-sm-3">
							<input type="text" class="form-control" ng-model="default.encounter.condition_state" />
						</div>
						<label class="col-sm-3 control-label"> Otro accidente </label>
						<div class="col-sm-3">
							<select ng-model="default.encounter.condition_other_accident"  class="form-control"   >
								<option value="">Sin verificar</option>
								<option value="Yes">Si</option>
								<option value="No">No</option>
							</select>
						</div>
					</div>
					<div class="form-group form-group-sm" >
						<label class="col-sm-3 control-label"></label>
						<div class="col-sm-9">
							<label>
								<input type="checkbox"  ng-true-value="1" ng-false-value="0"  ng-model="default.encounter.has_insurance"  />
								Usar seguro  
							</label>
						</div>
					</div>
					<div  ng-show="default.encounter.has_insurance==1" >
						<div class="form-group form-group-sm">
							<label class="col-sm-3 control-label">Seguros</label>
							<div class="col-sm-9">
								<label ng-repeat="insurance in data.patient.my_insurances" style="margin-right: 10px;" >
									<input type="radio" 
										ng-model="default.encounter.insurance_radio"
										ng-value="insurance" 
										/> {{ insurance }}
								 
								</label>
								<p ng-show="!data.patient.my_insurances.length" class="text-warning">Paciente sin seguros activos</p>
							</div>
						</div>
						
					</div>
				</div>

				<div id="vitals-eyes-audio" class="tab-pane fade">
					<label class="col-lg-12 text-center">Ojos</label>
					<table class="table">
						<tbody>
							<tr>
								<!--
								<th class="col-md-3 text-right" style="padding-top:20px;">With glasses</th>
								-->
								<td class="col-md-4">
									<input  ng-model="default.encounter.eye_withglasses_left"  type="text" class="form-control input-sm" >
									<span class="help-block">Izquierdo</span>
								</td>
								<td class="col-md-4">
									<input  ng-model="default.encounter.eye_withglasses_right"  type="text" class="form-control input-sm" >
									<span class="help-block">Derecho</span>
								</td>
								<td class="col-md-4">
									<input  ng-model="default.encounter.eye_withglasses_both"  type="text" class="form-control input-sm" >
									<span class="help-block">Ambos</span>
								</td>
							</tr>
							<tr>
								<th class="col-md-8 text-right" style="padding-right:20px;padding-top:15px;"  colspan="2">
									¿El paciente usa lentes recetados?
								</th>
								<td class="col-md-2 " >
									<div class="form-group form-group-sm">
										<select ng-model="default.encounter.eye_prescription_glasses" class="form-control" >
											<option value=""></option>
											<option value="Yes">Si</option>
											<option value="No">No</option>
										</select>
									</div>
								</td>
							</tr>
							<tr>
								<th class="col-md-8 text-right" style="padding-right:20px;padding-top:15px;" colspan="2" >
									¿Se usaron lentes durante el examen?
								</th>
								<td class="col-md-2">
									<div class="form-group form-group-sm">
										<select ng-model="default.encounter.eye_worn_during_exam" class="form-control" >
											<option value=""></option>
											<option value="Yes">Si</option>
											<option value="No">No</option>
										</select>
									</div>
								</td>
							</tr>
							<!--
							<tr>
								<th class="col-md-3 text-right" style="padding-top:20px;">Without glasses</th>
								<td class="col-md-2">
									<input  ng-model="default.encounter.eye_withoutglasses_left"  type="text" class="form-control input-sm" >
									<span class="help-block">Left</span>
								</td>
								<td class="col-md-2">
									<input  ng-model="default.encounter.eye_withoutglasses_right"  type="text" class="form-control input-sm" >
									<span class="help-block">Right</span>
								</td>
								<td class="col-md-5">
									<input  ng-model="default.encounter.eye_withoutglasses_both"  type="text" class="form-control input-sm" >
									<span class="help-block">Both</span>
								</td>
							</tr>
						-->
							<!--
							<tr>
								<th class="col-md-3 text-right" style="padding-top:20px;">With glasses</th>
								<td class="col-md-2">
									<input  ng-model="default.encounter.eye_withglasses_left"  type="text" class="form-control input-sm" >
									<span class="help-block">Left</span>
								</td>
								<td class="col-md-2">
									<input  ng-model="default.encounter.eye_withglasses_right"  type="text" class="form-control input-sm" >
									<span class="help-block">Right</span>
								</td>
								<td class="col-md-5">
									<input  ng-model="default.encounter.eye_withglasses_both"  type="text" class="form-control input-sm" >
									<span class="help-block">Both</span>
								</td>
							</tr>
						-->
						</tbody>
					</table>
					
					<label class="col-lg-12 text-center">Oido</label>
					<table class="table">
						<tbody>
							<tr>
								<th class="col-md-3 text-right" style="padding-top:20px;">Izquierdo</th>
								<td class="col-md-2">
									<input  ng-model="default.encounter.audio_left_1000"  type="number" class="form-control input-sm" >
									<span class="help-block">1000</span>
								</td>
								<td class="col-md-2">
									<input  ng-model="default.encounter.audio_left_2000"  type="number" class="form-control input-sm" >
									<span class="help-block">2000</span>
								</td>
								<td class="col-md-2">
									<input  ng-model="default.encounter.audio_left_3000"  type="number" class="form-control input-sm" >
									<span class="help-block">3000</span>
								</td>
								<td class="col-md-2">
									<input  ng-model="default.encounter.audio_left_4000"  type="number" class="form-control input-sm" >
									<span class="help-block">4000</span>
								</td>
							</tr>
							<tr>
								<th class="col-md-3 text-right" style="padding-top:20px;">Derecho</th>
								<td class="col-md-2">
									<input  ng-model="default.encounter.audio_right_1000"  type="number" class="form-control input-sm" >
									<span class="help-block">1000</span>
								</td>
								<td class="col-md-2">
									<input  ng-model="default.encounter.audio_right_2000"  type="number" class="form-control input-sm" >
									<span class="help-block">2000</span>
								</td>
								<td class="col-md-2">
									<input  ng-model="default.encounter.audio_right_3000"  type="number" class="form-control input-sm" >
									<span class="help-block">3000</span>
								</td>
								<td class="col-md-2">
									<input  ng-model="default.encounter.audio_right_4000"  type="number" class="form-control input-sm" >
									<span class="help-block">4000</span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<div id="vitals-urinalysis" class="tab-pane fade">
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label"> Color </label>
						<div class="col-sm-3">
							<input type="text" class="form-control" ng-model="default.encounter.urinalysis_color"  />
						</div>
						<label class="col-sm-3 control-label"> Densidad </label>
						<div class="col-sm-3">
							<input type="text" class="form-control" ng-model="default.encounter.urinalysis_specific_gravity"  />
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label"> PH </label>
						<div class="col-sm-3">
							<input type="text" class="form-control" ng-model="default.encounter.urinalysis_ph" />
						</div>
						<label class="col-sm-3 control-label"> Proteina </label>
						<div class="col-sm-3">
							<input type="text" class="form-control" ng-model="default.encounter.urinalysis_protein" />
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label"> Glucosa </label>
						<div class="col-sm-3">
							<input type="text" class="form-control" ng-model="default.encounter.urinalysis_glucose" />
						</div>
						<label class="col-sm-3 control-label"> Cetonas </label>
						<div class="col-sm-3">
							<input type="text" class="form-control" ng-model="default.encounter.urinalysis_ketones" />
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label"> Bilirrubina </label>
						<div class="col-sm-3">
							<input type="text" class="form-control" ng-model="default.encounter.urinalysis_bilirubim" />
						</div>
						<label class="col-sm-3 control-label"> Sangre </label>
						<div class="col-sm-3">
							<input type="text" class="form-control" ng-model="default.encounter.urinalysis_blood" />
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label"> Leucocitos </label>
						<div class="col-sm-3">
							<input type="text" class="form-control" ng-model="default.encounter.urinalysis_leuktocytes" />
						</div>
						<label class="col-sm-3 control-label"> Nitritos </label>
						<div class="col-sm-3">
							<input type="text" class="form-control" ng-model="default.encounter.urinalysis_nitrite" />
						</div>
					</div>
					<div class="form-group form-group-sm" ng-hide="data.patient.gender == 'Male'">
						<label class="col-sm-3 control-label"> HGC </label>
						<div class="col-sm-3">
							<select ng-model="default.encounter.urinalysis_human_chorionic_gonadotropin"  class="form-control" >
								<option value="">Sin verificar</option>
								<option value="Positive">Positivo</option>
								<option value="Negative">Negativo</option>
							</select>
						</div>
					</div>
				</div>
			</div>	
		</div>
	</div>
	<div class="row" >
		<div class="col-sm-12  text-right well well-sm" style="margin:0px;">
			<button type="submit" class="btn btn-primary submit"> Guardar </button>
		</div>
	</div>
</form>