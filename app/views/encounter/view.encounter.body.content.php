<div class="panel-body body-normal">  
	<table class="table table-condensend table-bordered table-details table-hover-app">
		<tbody ng-cloak>
			<tr>
				<th>Motivo de consulta</th>
				<td> {{ data.encounter.chief_complaint }} </td>
			</tr>
			<tr ng-show="data.encounter.current_medications">
				<th>Medicación actual</th>
				<td>{{ data.encounter.current_medications }} </td>
			</tr>
			<tr ng-hide="data.encounter.hide_job">
				<th>Relación con el incidente</th>
				<td>
					<div class="row" ng-hide="data.encounter.condition_employment==''">
						<div class="col-xs-4 col-md-2" ><b>Empleado</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.condition_employment }}</div>
					</div>
					<div class="row" ng-hide="data.encounter.condition_autoaccident==''">
						<div class="col-xs-4 col-md-2" ><b>Accidente automovilistico</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.condition_autoaccident }}</div>
					</div>	
					<div class="row" ng-hide="data.encounter.condition_state==''">
						<div class="col-xs-4 col-md-2" ><b>Estado</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.condition_state }}</div>
					</div>
					<div class="row" ng-hide="data.encounter.condition_other_accident==''">
						<div class="col-xs-4 col-md-2" ><b>Otro accidente</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.condition_other_accident }}</div>
					</div>
				</td>
			</tr>
			<tr ng-show="data.encounter.status==2">
				<th>Firmado</th>
				<td><b>Fecha</b> {{ data.encounter.signed_at_format }}  <b> Proveedor: </b> {{ data.encounter.user_signed }}</td>
			</tr>
			<tr ng-hide="data.encounter.hide_heart">
				<th>Signos vitales cardíacos</th>
				<td>
					<div class="row"  ng-show="data.encounter.heart_pulse" >
						<div class="col-xs-4 col-md-2"><b>Pulso</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.heart_pulse }} <span class="text-opacity"> por minuto </span></div>
					</div>
					<div class="row"  ng-show="data.encounter.heart_respiratory" >
						<div class="col-xs-4 col-md-2" ><b>Frecuencia respiratoria</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.heart_respiratory }} <span class="text-opacity">por minuto</span></div>
					</div> 
					<div class="row"  ng-show="data.encounter.heart_temperature"  >
						<div class="col-xs-4 col-md-2" ><b>Temperatura</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.heart_temperature }} <span class="text-opacity">&deg;F</span></div>
					</div>
					<div class="row"  ng-show="data.encounter.heart_hemoglobin"  >
						<div class="col-xs-4 col-md-2" ><b>Hemoglobina</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.heart_hemoglobin }}</div>
					</div>
					<div class="row"  ng-show="data.encounter.heart_hematocrit"  >
						<div class="col-xs-4 col-md-2" ><b>Hematocrito</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.heart_hematocrit }}</div>
					</div>
					<div class="row" ng-show="data.encounter.heart_head_circ"  >
						<div class="col-xs-4 col-md-2" ><b>Circunferencia de la cabeza</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.heart_head_circ }} <span class="text-opacity">Cm.</span></div>
					</div>
					<div class="row" ng-show="data.encounter.heart_last_menstrual_period"  >
						<div class="col-xs-4 col-md-2" ><b>Ultima Mestruación</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.heart_last_menstrual_period }}</div>
						{{physical.length}}
					</div>
				</td>
			</tr> 
			<tr ng-show="vitals.length > 0">
				<th>Signos vitales firmados por</th>
				<td><b>Fecha</b> {{ vitals[0].date+" "+vitals[0].time }}  <b> Proveedor: </b> {{ vitals[0].user}}</td>
			</tr>
			<tr ng-hide="data.encounter.hide_physical">
				<th>Signos vitales físicos</th>
				<td>
					<div class="row"  ng-show="data.encounter.physical_birth_weight">
						<div class="col-xs-4 col-md-2" ><b>Peso al nacer</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.physical_birth_weight }} <span class="text-opacity"> Kg </span></div>
					</div>
					<div class="row" ng-show="data.encounter.physical_weight">
						<div class="col-xs-4 col-md-2" ><b>Peso</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.physical_weight }} <span class="text-opacity"> Kg </span></div>
					</div>
					<div class="row" ng-show="data.encounter.physical_height">
						<div class="col-xs-4 col-md-2" ><b>Altura</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.physical_height }} <span class="text-opacity"> M. </span></div>
					</div>
					<div class="row" ng-show="data.encounter.physical_bmi > 0">
						<div class="col-xs-4 col-md-2" ><b>IMC</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.physical_bmi }} </div>
					</div>
					<div class="row" ng-show="data.encounter.blood_pressure_sys > 0">
						<div class="col-xs-4 col-md-2" ><b>Presión arterial sistólica</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.blood_pressure_sys }} </div>
					</div>
					<div class="row" ng-show="data.encounter.blood_pressure_dia > 0">
						<div class="col-xs-4 col-md-2" ><b>Presión arterial diastólica</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.blood_pressure_dia }} </div>
					</div>
				</td>
			</tr>
			<tr ng-show="!data.encounter.hide_eye || !data.encounter.hide_audio">
				<th> Ojos y oidos </th>
				<td >
					<div ng-show="data.encounter.eye_questions==0">
						<div class="row" ng-show="data.encounter.eye_withglasses_left">
							<div class="col-xs-4 col-md-2" ><b>Ojo izquierdo con lentes</b></div>
							<div class="col-xs-7 col-md-6" >{{ data.encounter.eye_withglasses_left }}</div>
						</div>
						<div class="row" ng-show="data.encounter.eye_withglasses_right">
							<div class="col-xs-4 col-md-2" ><b>Ojo derecho con lentes</b></div>
							<div class="col-xs-7 col-md-6" >{{ data.encounter.eye_withglasses_right }}</div>
						</div>
						<div class="row" ng-show="data.encounter.eye_withglasses_both">
							<div class="col-xs-4 col-md-2" ><b>Ambos ojos con lentes</b></div>
							<div class="col-xs-7 col-md-6" >{{ data.encounter.eye_withglasses_both }}</div>
						</div>
						<div class="row" ng-show="data.encounter.eye_withoutglasses_left">
							<div class="col-xs-4 col-md-2" ><b>Ojo izquierdo sin lentes</b></div>
							<div class="col-xs-7 col-md-6" >{{ data.encounter.eye_withoutglasses_left }}</div>
						</div>
						<div class="row" ng-show="data.encounter.eye_withoutglasses_right">
							<div class="col-xs-4 col-md-2" ><b>Ojo derecho sin lentes</b></div>
							<div class="col-xs-7 col-md-6" >{{ data.encounter.eye_withoutglasses_right }}</div>
						</div>
						<div class="row" ng-show="data.encounter.eye_withoutglasses_both">
							<div class="col-xs-4 col-md-2" ><b>Ambos ojos sin lentes</b></div>
							<div class="col-xs-7 col-md-6" >{{ data.encounter.eye_withoutglasses_both }}</div>
						</div>
					</div>
					<div ng-show="data.encounter.eye_questions==1">
						<div class="row" ng-show="data.encounter.eye_withglasses_left">
							<div class="col-xs-4 col-md-2" ><b>Ojo izquierdo</b></div>
							<div class="col-xs-7 col-md-6" >{{ data.encounter.eye_withglasses_left }}</div>
						</div>
						<div class="row" ng-show="data.encounter.eye_withglasses_right">
							<div class="col-xs-4 col-md-2" ><b>Ojo derecho</b></div>
							<div class="col-xs-7 col-md-6" >{{ data.encounter.eye_withglasses_right }}</div>
						</div>
						<div class="row" ng-show="data.encounter.eye_withglasses_both">
							<div class="col-xs-4 col-md-2" ><b>Ambos ojos</b></div>
							<div class="col-xs-7 col-md-6" >{{ data.encounter.eye_withglasses_both }}</div>
						</div>
						<div class="row" >
							<div class="col-xs-4 col-md-4" ><b>¿El paciente usa lentes recetados? </b></div>
							<div class="col-xs-7 col-md-6" >{{ data.encounter.eye_prescription_glasses }}</div>
						</div>
						<div class="row" >
							<div class="col-xs-4 col-md-4" ><b>¿Se usaron lentes durante el examen? </b></div>
							<div class="col-xs-7 col-md-6" >{{ data.encounter.eye_worn_during_exam }}</div>
						</div>
					</div>
					<div class="row" ng-show="data.encounter.audio_left_1000!=0">
						<div class="col-xs-4 col-md-2" ><b>Izquierdo 1000</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.audio_left_1000 }}</div>
					</div>
					<div class="row" ng-show="data.encounter.audio_left_2000!=0">
						<div class="col-xs-4 col-md-2" ><b>Izquierdo 2000</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.audio_left_2000 }}</div>
					</div>
					<div class="row" ng-show="data.encounter.audio_left_3000!=0">
						<div class="col-xs-4 col-md-2" ><b>Izquierdo 3000</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.audio_left_3000 }}</div>
					</div>
					<div class="row" ng-show="data.encounter.audio_left_4000!=0">
						<div class="col-xs-4 col-md-2" ><b>Izquierdo 4000</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.audio_left_4000 }}</div>
					</div>
					<div class="row" ng-show="data.encounter.audio_right_1000!=0">
						<div class="col-xs-4 col-md-2" ><b>Derecho 1000</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.audio_right_1000 }}</div>
					</div>
					<div class="row" ng-show="data.encounter.audio_right_2000!=0">
						<div class="col-xs-4 col-md-2" ><b>Derecho 2000</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.audio_right_2000 }}</div>
					</div>
					<div class="row" ng-show="data.encounter.audio_right_3000!=0">
						<div class="col-xs-4 col-md-2" ><b>Derecho 3000</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.audio_right_3000 }}</div>
					</div>
					<div class="row" ng-show="data.encounter.audio_right_4000!=0">
						<div class="col-xs-4 col-md-2" ><b>Derecho 4000</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.audio_right_4000 }}</div>
					</div>
				</td>

			</tr>
			<tr ng-hide="data.encounter.hide_urinalysis">
				<th>Urinarios </th>
				<td>
					<div class="row" ng-show="data.encounter.urinalysis_color" >
						<div class="col-xs-4 col-md-2" ><b>Color</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.urinalysis_color }}</div>
					</div>
					<div class="row" ng-show="data.encounter.urinalysis_specific_gravity" >
						<div class="col-xs-4 col-md-2" ><b>Densidad</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.urinalysis_specific_gravity }}</div>
					</div>
					<div class="row" ng-show="data.encounter.urinalysis_ph" >
						<div class="col-xs-4 col-md-2" ><b>PH</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.urinalysis_ph }}</div>
					</div>
					<div class="row" ng-show="data.encounter.urinalysis_protein" >
						<div class="col-xs-4 col-md-2" ><b>Proteina</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.urinalysis_protein }}</div>
					</div>
					<div class="row" ng-show="data.encounter.urinalysis_glucose" >
						<div class="col-xs-4 col-md-2" ><b>Glucosa</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.urinalysis_glucose }}</div>
					</div>
					<div class="row" ng-show="data.encounter.urinalysis_ketones" >
						<div class="col-xs-4 col-md-2" ><b>Cetonas</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.urinalysis_ketones }}</div>
					</div>
					<div class="row" ng-show="data.encounter.urinalysis_bilirubim" >
						<div class="col-xs-4 col-md-2" ><b>Bilirrubina</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.urinalysis_bilirubim }}</div>
					</div>
					<div class="row" ng-show="data.encounter.urinalysis_blood" >
						<div class="col-xs-4 col-md-2" ><b>Sangre</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.urinalysis_blood }}</div>
					</div>
					<div class="row" ng-show="data.encounter.urinalysis_leuktocytes" >
						<div class="col-xs-4 col-md-2" ><b>Leucocitos</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.urinalysis_leuktocytes }}</div>
					</div>
					<div class="row" ng-show="data.encounter.urinalysis_nitrite" >
						<div class="col-xs-4 col-md-2" ><b>Nitritos</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.urinalysis_nitrite }}</div>
					</div>
					<div class="row" ng-show="data.encounter.urinalysis_human_chorionic_gonadotropin" >
						<div class="col-xs-4 col-md-2" ><b>HCG</b></div>
						<div class="col-xs-7 col-md-6" >{{ data.encounter.urinalysis_human_chorionic_gonadotropin }}</div>
					</div>
				</td>
			</tr>
			<tr ng-show="data.encounter.present_illness_history">
				<th>Historial de enfermedades actuales</th>
				<td>
					{{data.encounter.present_illness_history}}
				</td>
			</tr> 
			<tr ng-show="physical.length > 0">
				<th>Físico llenado por</th>
				<td><b>Fecha</b> {{ physical[0].date+" "+physical[0].time }}  <b> Proveedor: </b> {{ physical[0].user}}</td>
			</tr>
			<tr ng-show="data.encounter_physicalexam.length">
				<th>Examinaciones físicas</th>
				<td>
					<ul class="list" style="padding:0px 15px;">
					<li  ng-repeat="exam in data.encounter_physicalexam">
						<a ng-show="data.encounter.status==1" href="#" ng-click="action_physicalexam.edit( $index )"> <i class="fa fa-edit"> Editar</i></a>
						<b>{{exam.title}}</b>
						<p>{{exam.content}} </p>
					</li></ul>
				</td>
			</tr> 
			<tr ng-show="diagnosis.length > 0">
				<th>Diagnostico llenado por</th>
				<td><b>Fecha</b> {{ diagnosis[0].date+" "+diagnosis[0].time }}  <b> Proveedor: </b> {{ diagnosis[0].user}}</td>
			</tr>
			<tr ng-show="data.encounter_diagnosis.length">
				<th>Diagnostico</th>	
				<td><ul class="list" style="padding:0px 15px;">
					<li ng-repeat="diagnosis in data.encounter_diagnosis">
						<a  ng-show="data.encounter.status==1" href="#" ng-click="action_diagnosis.edit($index)" > <i class="fa fa-edit"> Editar</i></a>
						{{diagnosis.comment}} <b ng-show="diagnosis.chronic==1" class="text-warning"> Chronic</b>
					</li></ul></td>
			</tr>
			<tr ng-show="data.encounter.procedure_patient_education">
				<th>Educación del paciente</th>
				<td>{{ data.encounter.procedure_patient_education }} </td>
			</tr>
			<tr ng-show="data.encounter_medications.length">
				<th>Medicamentos</th>
				<td>
					<ul class="list" style="padding:0px 15px;">
					<li ng-repeat="med in data.encounter_medications">
						<a ng-show="data.encounter.status==1" href="#" ng-click="action_medication.edit($index)"> <i class="fa fa-edit"> Editar</i></a>
						<b>{{med.title}} {{med.dose}} </b> <span class="text-opacity"> ( Cantidad: {{med.amount }} ) <span ng-show="med.refill">Renovación: {{ med.refill }}</span></span> 
						<p >{{med.directions}}  <b class="text-warning" ng-show="med.chronic=='Yes'"> Chronic </b> </p>
					</li></ul>
				</td>
			</tr>
			<tr ng-show="data.encounter_results.length">
				<th>Solicitudes</th>
				<td>
					<ul class="list" style="padding:0px 15px;">
						<li ng-repeat="result in data.encounter_results">
							<a ng-show="data.encounter.status==1" href="#" ng-click="action_results.edit( $index )"> <i class="fa fa-edit"> Editar</i></a>
							<b>{{result.title}}</b> <span class="text-opacity">( {{ result.type_result }} )</span>
							<p>{{result.comments}} </p>
						</li>
					</ul>
				</td>
			</tr>
			<tr ng-show="data.encounter_referrals.length">
				<th>Derivaciones</th>
				<td>
					<ul class="list" style="padding:0px 15px;">
					<li ng-repeat="ref in data.encounter_referrals">
						<a ng-show="data.encounter.status==1" href="#" ng-click="action_referrals.edit($index)"> <i class="fa fa-edit"> Editar</i></a>
						<b>{{ref.speciality}}</b><span class="text-opacity"> ( Servicio: {{ ref.service }}, <span>{{ action_referrals.getStatus(ref.status) }}</span>)</span>
						<p >  {{ref.reason}}  <span ng-class="ref.acuity=='Urgent' ? 'text-danger' : 'text-success'">{{ ref.acuity == "Routine" ? "Rutina" : "Urgente" }}</span></p>
					</li></ul>
				</td>
			</tr>
			<tr ng-show="data.encounter_child.show_basic">
				<th>Examen físico del niño </th>
				<td>
					<div class="row" ng-show="data.encounter_child.referred_to_wic" >
						<div class="col-xs-3 col-md-3"><b>Código Étnico</b></div>
						<div class="col-xs-9 col-md-9" ><b>{{ data.encounter_child.ethnic_code  }}</b> {{ data.settings_ethnic_codes[data.encounter_child.ethnic_code]  }}</div>
					</div>
					<div class="row" ng-show="data.encounter_child.referred_to_wic" >
						<div class="col-xs-3 col-md-3"><b>Derivado a WIC</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.referred_to_wic  }}</div>
					</div>
					<div class="row" ng-show="data.encounter_child.enrolled_in_wic" >
						<div class="col-xs-3 col-md-3"><b>Inscrito en WIC</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.enrolled_in_wic  }}</div>
					</div>
					<div class="row"  ng-show="data.encounter_child.treatment" >
						<div class="col-xs-3 col-md-3"><b>Tratamiento</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.treatment  }}</div>
					</div>
					<div class="row"  ng-show="data.encounter_child.assessment" >
						<div class="col-xs-3 col-md-3"><b>Evaluación</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.assessment  }}</div>
					</div>
					<div class="row" ng-show="data.encounter_child.tb_risk" >
						<div class="col-xs-3 col-md-3"><b>Evaluación de Riesgo de TB</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.tb_risk  }}</div>
					</div>
					<div class="row" ng-show="data.encounter_child.lead_risk" >
						<div class="col-xs-3 col-md-3"><b>Cuestionario de Riesgo de Plomo</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.lead_risk  }}</div>
					</div>
				</td>
			</tr>
			<tr ng-show="data.encounter_child.show_interval_history">
				<th>Historia del intervalo del niño </th>
				<td>
					<div class="row"  ng-show="data.encounter_child.interval_history_diet" >
						<div class="col-xs-3 col-md-3"><b>Dieta</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.interval_history_diet  }}</div>
					</div>
					<div class="row"  ng-show="data.encounter_child.interval_history_illness"  >
						<div class="col-xs-3 col-md-3"><b>Enfermedad</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.interval_history_illness  }}</div>
					</div>
					<div class="row"  ng-show="data.encounter_child.interval_history_problems"  >
						<div class="col-xs-3 col-md-3"><b>Problemas</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.interval_history_problems  }}</div>
					</div>
					<div class="row" ng-show="data.encounter_child.interval_history_immunization" >
						<div class="col-xs-3 col-md-3"><b>Reacción a la inmunización</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.interval_history_immunization  }}</div>
					</div>
					<div class="row"  ng-show="data.encounter_child.interval_history_parental_concerns"  >
						<div class="col-xs-3 col-md-3"><b>Preocupaciones de los padres</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.interval_history_parental_concerns  }}</div>
					</div>
				</td>
			</tr>
			<tr ng-show="data.encounter_child.show_development">
				<th>Desarrollo del niño</th>
				<td>
					<div class="row" ng-show="data.encounter_child.development_result" >
						<div class="col-xs-3 col-md-3"><b>Desarrollo</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.development_result  }}</div>
					</div>
					<div class="row"   ng-show="data.encounter_child.development_options"  >
						<div class="col-xs-3 col-md-3"><b>Opciones</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.development_options  }}</div>
					</div>
					<div class="row"   ng-show="data.encounter_child.development_plan"  >
						<div class="col-xs-3 col-md-3"><b>Plan</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.development_plan  }}</div>
					</div>
					<div class="row"  ng-show="data.encounter_child.educations"  >
						<div class="col-xs-3 col-md-3"><b>Educación</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.educations  }}</div>
					</div>
				</td>
			</tr>
			<tr ng-show="data.encounter_child.show_examination">
				<th>Examen fisico</th>
				<td>
					<div class="row" >
						<div class="col-xs-3 col-md-3"><b>Apariencia general</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.physical_result_general_appearance == "AB" ? "Anormal" : "Normal" }} <span class="text-opacity"> {{ data.encounter_child.physical_comments_general_appearance  }} </span></div>
					</div>
					<div class="row"  >
						<div class="col-xs-3 col-md-3"><b>Nutrición</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.physical_result_nutrition  == "AB" ? "Anormal" : "Normal" }} <span class="text-opacity"> {{ data.encounter_child.physical_comments_nutrition  }} </span></div>
					</div>
					<div class="row"  >
						<div class="col-xs-3 col-md-3"><b>Piel</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.physical_result_skin  == "AB" ? "Anormal" : "Normal" }} <span class="text-opacity"> {{ data.encounter_child.physical_comments_skin  }} </span></div>
					</div>
					<div class="row"  >
						<div class="col-xs-3 col-md-3"><b>Cabeza, cuello y ganglios</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.physical_result_head_neck_nodes  == "AB" ? "Anormal" : "Normal" }} <span class="text-opacity"> {{ data.encounter_child.physical_comments_head_neck_nodes  }} </span></div>
					</div>
					<div class="row"  >
						<div class="col-xs-3 col-md-3"><b>Ojos/reflejo equitativo</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.physical_result_eyes_eq_reflex  == "AB" ? "Anormal" : "Normal" }} <span class="text-opacity"> {{ data.encounter_child.physical_comments_eyes_eq_reflex  }} </span></div>
					</div>
					<div class="row"  >
						<div class="col-xs-3 col-md-3"><b>Oídos/Audición</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.physical_result_ent_hearing  == "AB" ? "Anormal" : "Normal" }} <span class="text-opacity"> {{ data.encounter_child.physical_comments_ent_hearing  }} </span></div>
					</div>
					<div class="row"  >
						<div class="col-xs-3 col-md-3"><b>Boca/Dental</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.physical_result_mouth_dental  == "AB" ? "Anormal" : "Normal" }} <span class="text-opacity"> {{ data.encounter_child.physical_comments_mouth_dental  }} </span></div>
					</div>
					<div class="row"  >
						<div class="col-xs-3 col-md-3"><b>Pecho/Pulmones</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.physical_result_chest_lungs  == "AB" ? "Anormal" : "Normal" }} <span class="text-opacity"> {{ data.encounter_child.physical_comments_chest_lungs  }} </span></div>
					</div>
					<div class="row"  >
						<div class="col-xs-3 col-md-3"><b>Corazón</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.physical_result_heart  == "AB" ? "Anormal" : "Normal" }} <span class="text-opacity"> {{ data.encounter_child.physical_comments_heart  }} </span></div>
					</div>
					<div class="row"  >
						<div class="col-xs-3 col-md-3"><b>Abdomen</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.physical_result_abdomen  == "AB" ? "Anormal" : "Normal" }} <span class="text-opacity"> {{ data.encounter_child.physical_comments_abdomen  }} </span></div>
					</div>
					<div class="row"  >
						<div class="col-xs-3 col-md-3"><b>Genitales externos</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.physical_result_external_genitalia  == "AB" ? "Anormal" : "Normal" }} <span class="text-opacity"> {{ data.encounter_child.physical_comments_external_genitalia  }} </span></div>
					</div>
					<div class="row"  >
						<div class="col-xs-3 col-md-3"><b>Espalda</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.physical_result_back  == "AB" ? "Anormal" : "Normal" }} <span class="text-opacity"> {{ data.encounter_child.physical_comments_back  }} </span></div>
					</div>
					<div class="row"  >
						<div class="col-xs-3 col-md-3"><b>Extremidades/Caderas</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.physical_result_extremities_hips  == "AB" ? "Anormal" : "Normal" }} <span class="text-opacity"> {{ data.encounter_child.physical_comments_extremities_hips  }} </span></div>
					</div>
					<div class="row"  >
						<div class="col-xs-3 col-md-3"><b>Neurológico</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.physical_result_neurological  == "AB" ? "Anormal" : "Normal" }} <span class="text-opacity"> {{ data.encounter_child.physical_comments_neurological  }} </span></div>
					</div>	
					<div class="row"  >
						<div class="col-xs-3 col-md-3"><b>Pulsos femorales</b></div>
						<div class="col-xs-9 col-md-9" >{{ data.encounter_child.physical_result_fem_pulses  == "AB" ? "Anormal" : "Normal" }} <span class="text-opacity"> {{ data.encounter_child.physical_comments_fem_pulses  }} </span></div>
					</div>	
				</td>
			</tr>
			<tr ng-show="data.encounter_child.show_tobacco">
				<th>Evaluación de tabaco en el niño </th>
				<td>
					<div class="row" ng-show="data.encounter_child.tobacco_patient_exposed" >
						<div class="col-xs-8 col-md-8"><b>El paciente está expuesto al humo de tabaco pasivo</b></div>
						<div class="col-xs-4 col-md-4" >{{ data.encounter_child.tobacco_patient_exposed == "Yes" ? "Si" : "No" }}</div>
					</div>
					<div class="row" ng-show="data.encounter_child.tobacco_used_by_patient" >
						<div class="col-xs-8 col-md-8"><b>Uso de tabaco por parte del paciente</b></div>
						<div class="col-xs-4 col-md-4" >{{ data.encounter_child.tobacco_used_by_patient == "Yes" ? "Si" : "No" }}</div>
					</div>
					<div class="row" ng-show="data.encounter_child.tobacco_prevention_referred" >
						<div class="col-xs-8 col-md-8"><b>Asesorado sobre/referido para la prevención/cesación del uso de tabaco</b></div>
						<div class="col-xs-4 col-md-4" >{{ data.encounter_child.tobacco_prevention_referred == "Yes" ? "Si" : "No" }}</div>
					</div>
				</td>
			</tr>
			<tr ng-show="data.encounter_addendums.length">
				<th>Addendums</th>
				<td>
					<ul class="list" style="padding:0px 15px;">
						<li ng-repeat="addendum in data.encounter_addendums">
							<b>{{ addendum.nick_name }}</b><span class="text-opacity"> ( {{ addendum.user }} - {{ addendum.date }} {{ addendum.time }} )</span>
							<p>{{ addendum.notes }} </p>
						</li>
					</ul>
				</td>
			</tr>
	</table>
	
</div>
