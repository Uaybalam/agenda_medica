<style type="text/css">
	.bordercontent{
		border:1px solid #2c3e50;
	}	
</style>
<div class="panel-body body-normal">
	<div class="row">
		<div class="col-md-6">
			<div class="bordercontent">
				<table class="table table-condensend table-bordered table-details table-hover-app"">
					<tr >
						<th>Firma de proveedor</th>
						<td><b>Fecha:</b> {{ ngHelper.normalDate(data.encounter.signed_at) }}  <b> Proveedor: </b> {{ data.encounter.user_signed }}</td>
					</tr>
					<tr>
						<th>Motivo de consulta</th>
						<td>{{ data.encounter.chief_complaint }}</td>
					</tr>
					<tr >
						<th>Medicación actual</th>
						<td>{{ data.encounter.current_medications }} </td>
					</tr>
					<tr >
						<th>Historia de la enfermedad actua </th>
						<td>
							{{data.encounter.present_illness_history}}
						</td>
					</tr>
					<tr>
						<th>Educación del paciente</th>
						<td> {{ data.encounter.procedure_patient_education}}</td>
					</tr>
					<tr>
						<th> <span  ng-class="data.documents.result_data.length>0 ? 'text-danger' : 'text-default'">Documentos</span> </th>
						<td> 
							<p ng-repeat="file in data.documents.result_data">
								<a href="#" ng-click="previewDocument(file)" >Preview</a> 
								<b>{{ file.title}}</b> <small>{{ file.type }}</small>
							</p>
						</td>
					</tr>
					<tr>
						<th> Diagnostico <span class="badge">{{ data.encounter_diagnosis.length}}</span></th>
						<td>
							<ul class="list" style="padding:0px 15px;">
								<li ng-repeat="diagnosis in data.encounter_diagnosis">
								<a  ng-show="data.encounter.status==1" href="#" ng-click="action_diagnosis.edit($index)" > <i class="fa fa-edit"> Editar</i></a>
								{{diagnosis.comment}}<span ng-show="diagnosis.chronic==1" class="text-opacity">(Diagnosis chronic)</span>
								</li>
							</ul>
						</td>
					</tr>
				</table>
			</div>
			
		</div>
		<div class="col-md-6">
			<ul class="nav nav-pills nav-justified">
				<li  class="active"><a data-toggle="tab" href="#tab-vitals">Signos vitales</a></li>
  				<li><a data-toggle="tab" href="#tab-physicalexam">Examen fisico</a></li>
  				<li><a data-toggle="tab" href="#tab-child">Pediatrico</a></li>
  				<li><a data-toggle="tab" href="#tab-childExam">Examinación del niño</a></li>
			</ul>
			<div class="tab-content bordercontent">
				<div id="tab-vitals" class="tab-pane active">
					<div class="row">
						<div class="col-md-6">
							<div class="well well-sm text-center" style="margin:4px 0px;"><b>Signos vitales</b></div>
							<table class="table table-condensend table-bordered table-details table-hover-app">
								<tr  >
									<th>Pulso</th>
									<td>{{ data.encounter.heart_pulse }} <span class="text-opacity"> per min </span></td>
								</tr>
								<tr >
									<th>Frencuencia respiratoria</th>
									<td>{{ data.encounter.heart_respiratory }} <span class="text-opacity"> per min </span></td>
								</tr>
								<tr >
									<th>Temperatura</th>
									<td>{{ data.encounter.heart_temperature }} <span class="text-opacity"> &deg;F</span></td>
								</tr>
								<tr >
									<th>Hemoglobina</th>
									<td>{{ data.encounter.heart_hemoglobin }} </td>
								</tr>
								<tr >
									<th>Hematocrito</th>
									<td>{{ data.encounter.heart_hematocrit }} </td>
								</tr>
								<tr >
									<th>Circunferencia de la cabeza</th>
									<td>{{ data.encounter.heart_head_circ }} </td>
								</tr>
								<tr >
									<th>Ultima Mestruación</th>
									<td>{{ data.encounter.heart_last_menstrual_period }} </td>
								</tr>
								<tr >
									<th>Peso al nacer</th>
									<td>{{ data.encounter.physical_birth_weight }} <span class="text-opacity"> lb </span></td>
								</tr>
								<tr >
									<th><span >Peso</span></th>
									<td>{{ data.encounter.physical_weight }} <span class="text-opacity"> lb </span></td>
								</tr>
								<tr >
									<th>Altura</th>
									<td>{{ data.encounter.physical_height }} <span class="text-opacity"> In. </span></td>
								</tr>
								<tr >
									<th>IMC</th>
									<td>{{ data.encounter.physical_bmi }}</td>
								</tr>
								<tr >
									<th>Presión arterial sistólica</th>
									<td>{{ data.encounter.blood_pressure_sys }}</td>
								</tr>
								<tr >
									<th>Presión arterial diastólica</th>
									<td>{{ data.encounter.blood_pressure_dia }}</td>
								</tr>
							</table>
						</div>
						<div class="col-md-6">
							<div class="well well-sm text-center" style="margin:4px 0px;"><b>Urinailysis</b></div>
							<table class="table table-condensend table-bordered table-details table-hover-app">
								<tr>
									<th>Color</th>
									<td>{{ data.encounter.urinalysis_color }}</td>
								</tr>
								<tr>
									<th>Densidad</th>
									<td>{{ data.encounter.urinalysis_specific_gravity }}</td>
								</tr>
								<tr>
									<th>PH</th>
									<td>{{ data.encounter.urinalysis_ph }}</td>
								</tr>
								<tr>
									<th>Proteina</th>
									<td>{{ data.encounter.urinalysis_protein }}</td>
								</tr>
								<tr>
									<th>Glucosa</th>
									<td>{{ data.encounter.urinalysis_glucose }}</td>
								</tr>
								<tr>
									<th>Cetonas</th>
									<td>{{ data.encounter.urinalysis_bilirubim }}</td>
								</tr>
								<tr>
									<th>Bilirrubina</th>
									<td>{{ data.encounter.urinalysis_ketones }}</td>
								</tr>
								<tr>
									<th>Sangre</th>
									<td>{{ data.encounter.urinalysis_blood }}</td>
								</tr>
								<tr>
									<th>Leucocitos</th>
									<td>{{ data.encounter.urinalysis_leuktocytes }}</td>
								</tr>
								<tr>
									<th>Nitritos</th>
									<td>{{ data.encounter.urinalysis_nitrite }}</td>
								</tr>
								<tr>
									<th > <span class="text-danger"> <b>HCG</b></span></th>
									<td>{{ data.encounter.urinalysis_human_chorionic_gonadotropin }}</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<div id="tab-physicalexam" class="tab-pane fade">
					<div class="well well-sm text-center" style="margin:4px 0px;"><b>Examen Fisico <span class="badge">{{ data.encounter_physicalexam.length}}</span></b></div>
					<ul class="list" style="padding:0px 20px;">
					<li  ng-repeat="exam in data.encounter_physicalexam">
						<a ng-show="data.encounter.status==1" href="#" ng-click="action_physicalexam.edit( $index )"> <i class="fa fa-edit"> Editar </i></a>
						<b>{{exam.title}}</b>
						<p>{{exam.content}} </p>
					</li></ul>
				</div>
				<div id="tab-child" class="tab-pane fade"> 
					<div class="row">
						<div class="col-md-6">
							<div class="well well-sm text-center" style="margin:4px 0px;"><b>Examen físico del niño</b></div>
							<table class="table table-condensend table-bordered table-details table-hover-app">
								<tr  >
									<th>Código Étnico</th>
									<td>{{ data.encounter_child.ethnic_code  }} </td>
								</tr>
								<tr  >
									<th>Derivado a WIC</th>
									<td>{{ data.encounter_child.referred_to_wic  }} </td>
								</tr>
								<tr  >
									<th>Inscrito en WIC</th>
									<td>{{ data.encounter_child.enrolled_in_wic  }} </td>
								</tr>
								<tr  >
									<th>Tratamiento</th>
									<td>{{ data.encounter_child.treatment  }} </td>
								</tr>
								<tr  >
									<th>Evaluación</th>
									<td>{{ data.encounter_child.assessment  }} </td>
								</tr>
								<tr  >
									<th>Evaluación de Riesgo de TB</th>
									<td>{{ data.encounter_child.tb_risk  }} </td>
								</tr>
								<tr  >
									<th>Cuestionario de Riesgo de Plomo</th>
									<td>{{ data.encounter_child.lead_risk  }} </td>
								</tr>
							</table>
						</div>
						<div class="col-md-6">
							<div class="well well-sm text-center" style="margin:4px 0px;"><b>Historia del intervalo del niño</b></div>
							<table class="table table-condensend table-bordered table-details table-hover-app">
								<tr  >
									<th>Dieta</th>
									<td>{{ data.encounter_child.interval_history_diet  }} </td>
								</tr>
								<tr  >
									<th>Enfermedad</th>
									<td>{{ data.encounter_child.interval_history_illness  }} </td>
								</tr>
								<tr  >
									<th>Problemas</th>
									<td>{{ data.encounter_child.interval_history_problems  }} </td>
								</tr>
								<tr  >
									<th>Reacción a la inmunización</th>
									<td>{{ data.encounter_child.interval_history_immunization  }} </td>
								</tr>
								<tr  >
									<th>Preocupaciones de los padres</th>
									<td>{{ data.encounter_child.interval_history_parental_concerns  }} </td>
								</tr>
							</table>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="well well-sm text-center" style="margin:4px 0px;"><b>Desarrollo del niño </b></div>
							
							<table class="table table-condensend table-bordered table-details table-hover-app">
								<tr  >
									<th>Tipo</th>
									<td>{{ data.encounter_child.development_result  }} </td>
								</tr>
								<tr  >
									<th>Pruebas</th>
									<td>{{ data.encounter_child.development_options  }} </td>
								</tr>
								<tr  >
									<th>Plan</th>
									<td>{{ data.encounter_child.development_plan  }} </td>
								</tr>
								<tr  >
									<th>Educación</th>
									<td>{{ data.encounter_child.educations  }} </td>
								</tr>
							</table>
						</div>
						<div class="col-md-6">
							<div class="well well-sm text-center" style="margin:4px 0px;"><b>Evaluación de tabaco en el niño</b></div>
							<table class="table table-condensend table-bordered table-details table-hover-app">
								<tr>
									<th>El paciente está expuesto al humo de tabaco pasivo</th>
									<td>{{ data.encounter_child.tobacco_patient_exposed  }}</td>
								</tr>
								<tr>
									<th>Uso de tabaco por parte del paciente</th>
									<td>{{ data.encounter_child.tobacco_used_by_patient  }}</td>
								</tr>
								<tr>
									<th>Asesorado sobre/referido para la prevención/cesación del uso de tabaco</th>
									<td>{{ data.encounter_child.tobacco_prevention_referred  }}</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<div id="tab-childExam" class="tab-pane fade">
					<div class="well well-sm text-center" style="margin:4px 0px;"><b>Examen fisico	</b></div>
					<table class="table table-condensend table-bordered table-details table-hover-app">
						<tr  >
							<th>Apariencia general</th>
							<td>{{ data.encounter_child.physical_result_general_appearance == "N" ? "Normal" : "Anormal" }}  <span class="text-opacity"> {{ data.encounter_child.physical_comments_general_appearance  }} </span></td>
						</tr>
						<tr  >
							<th>Nutrición</th>
							<td>{{ data.encounter_child.physical_result_nutrition == "N" ? "Normal" : "Anormal" }}  <span class="text-opacity"> {{ data.encounter_child.physical_comments_nutrition  }} </span></td>
						</tr>
						<tr  >
							<th>Piel</th>
							<td>{{ data.encounter_child.physical_result_skin == "N" ? "Normal" : "Anormal" }}  <span class="text-opacity"> {{ data.encounter_child.physical_comments_skin  }} </span></td>
						</tr>
						<tr  >
							<th>Cabeza, cuello y ganglios</th>
							<td>{{ data.encounter_child.physical_result_head_neck_nodes == "N" ? "Normal" : "Anormal" }}  <span class="text-opacity"> {{ data.encounter_child.physical_comments_head_neck_nodes  }} </span></td>
						</tr>
						<tr  >
							<th>Ojos/reflejo equitativo</th>
							<td>{{ data.encounter_child.physical_result_eyes_eq_reflex == "N" ? "Normal" : "Anormal" }}  <span class="text-opacity"> {{ data.encounter_child.physical_comments_eyes_eq_reflex  }} </span></td>
						</tr>
						<tr  >
							<th>Oídos/Audición</th>
							<td>{{ data.encounter_child.physical_result_ent_hearing == "N" ? "Normal" : "Anormal" }}  <span class="text-opacity"> {{ data.encounter_child.physical_comments_ent_hearing  }} </span></td>
						</tr>
						<tr  >
							<th>Boca/Dental</th>
							<td>{{ data.encounter_child.physical_result_mouth_dental == "N" ? "Normal" : "Anormal" }}  <span class="text-opacity"> {{ data.encounter_child.physical_comments_mouth_dental  }} </span></td>
						</tr>
						<tr  >
							<th>Pecho/Pulmones</th>
							<td>{{ data.encounter_child.physical_result_chest_lungs == "N" ? "Normal" : "Anormal" }}  <span class="text-opacity"> {{ data.encounter_child.physical_comments_chest_lungs  }} </span></td>
						</tr>
						<tr  >
							<th>Corazón</th>
							<td>{{ data.encounter_child.physical_result_heart == "N" ? "Normal" : "Anormal" }}  <span class="text-opacity"> {{ data.encounter_child.physical_comments_heart  }} </span></td>
						</tr>
						<tr  >
							<th>Abdomen</th>
							<td>{{ data.encounter_child.physical_result_abdomen == "N" ? "Normal" : "Anormal" }}  <span class="text-opacity"> {{ data.encounter_child.physical_comments_abdomen  }} </span></td>
						</tr>
						<tr  >
							<th>Genitales externos</th>
							<td>{{ data.encounter_child.physical_result_external_genitalia == "N" ? "Normal" : "Anormal" }}  <span class="text-opacity"> {{ data.encounter_child.physical_comments_external_genitalia  }} </span></td>
						</tr>
						<tr  >
							<th>Espalda</th>
							<td>{{ data.encounter_child.physical_result_back == "N" ? "Normal" : "Anormal" }}  <span class="text-opacity"> {{ data.encounter_child.physical_comments_back  }} </span></td>
						</tr>
						<tr  >
							<th>Extremidades/Caderas</th>
							<td>{{ data.encounter_child.physical_result_extremities_hips == "N" ? "Normal" : "Anormal" }}  <span class="text-opacity"> {{ data.encounter_child.physical_comments_extremities_hips  }} </span></td>
						</tr>
						<tr  >
							<th>Neurológico</th>
							<td>{{ data.encounter_child.physical_result_neurological == "N" ? "Normal" : "Anormal" }}  <span class="text-opacity"> {{ data.encounter_child.physical_comments_neurological  }} </span></td>
						</tr>
					</table>
				</div>
			</div>

			<div class="col-lg-12" style="margin-top: 12px;">
				<div class="col-md-6">
					<div class="bordercontent">
						<div class="well well-sm text-center text-success" style="margin:4px 0px;"><b>examinacion de ojo</b></div>
						<table class="table table-condensend table-bordered table-details table-hover-app ">
							<tr  >
								<th>Con lentes izquierdo</th>
								<td>{{ data.encounter.eye_withglasses_left }} </td>
							</tr>
							<tr  >
								<th>Con lentes derecho</th>
								<td>{{ data.encounter.eye_withglasses_right }} </td>
							</tr>
							<tr  >
								<th>Con lentes ambos</th>
								<td>{{ data.encounter.eye_withglasses_both }} </td>
							</tr>
							<tr  >
								<th>Con lentes izquierdo</th>
								<td>{{ data.encounter.eye_withoutglasses_left }} </td>
							</tr>
							<tr  >
								<th>Con lentes derecho</th>
								<td>{{ data.encounter.eye_withoutglasses_right }} </td>
							</tr>
							<tr  >
								<th>Con lentes ambos</th>
								<td>{{ data.encounter.eye_withoutglasses_both }} </td>
							</tr>
						</table>
					</div>
				</div>
				<div class="col-md-6">
					<div class="bordercontent">
						<div class="well well-sm text-center text-success" style="margin:4px 0px;"><b>Examen auditivo izquierdo</b></div>
							<table class="table table-condensend table-bordered table-details table-hover-app">
								<tr  >
									<th>1000</th>
									<th>2000</th>
									<th>3000</th>
									<th>4000</th>
								</tr>
								<tr>
									<td> <span ng-show="data.encounter.audio_left_1000>0">{{ data.encounter.audio_left_1000 }}</span></td>
									<td> <span ng-show="data.encounter.audio_left_2000>0">{{ data.encounter.audio_left_2000 }}</span></td>
									<td> <span ng-show="data.encounter.audio_left_3000>0">{{ data.encounter.audio_left_3000 }}</span></td>
									<td> <span ng-show="data.encounter.audio_left_4000>0">{{ data.encounter.audio_left_4000 }}</span></td>
								</tr>
							</table>
							<div class="well well-sm text-center text-success" style="margin:4px 0px;"><b>Examen auditico derecho</b></div>
							<table class="table table-condensend table-bordered table-details table-hover-app">
								<tr  >
									<th>1000</th>
									<th>2000</th>
									<th>3000</th>
									<th>4000</th>
								</tr>
								<tr>
									<td> <span ng-show="data.encounter.audio_right_1000>0">{{ data.encounter.audio_right_1000 }}</span></td>
									<td> <span ng-show="data.encounter.audio_right_2000>0">{{ data.encounter.audio_right_2000 }}</span></td>
									<td> <span ng-show="data.encounter.audio_right_3000>0">{{ data.encounter.audio_right_3000 }}</span></td>
									<td> <span ng-show="data.encounter.audio_right_4000>0">{{ data.encounter.audio_right_4000 }}</span></td>
								</tr>
							</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-12" style="margin-top: 12px;" >
			<div class="col-md-3">
				<div class="well well-sm text-center" style="margin:4px 0px;"><b>Medicamentos <span class="badge">{{ data.encounter_medications.length}}</span></b></div>
				<div class="item-box"  ng-cloak ng-repeat="item in data.encounter_medications">
					<span class="text-info" data-toggle="tooltip" title="Med. title">{{item.title +' '+item.dose}} </span><label data-toggle="tooltip" title="Amount" class="label label-warning">{{item.amount }}</label>
					<p>{{item.directions}} </p>
				</div>
			</div>
			<div class="col-md-3">
				<div class="well well-sm text-center" style="margin:4px 0px;"><b>Solicitudes <span class="badge">{{ data.encounter_results.length}}</span></b></div>
				<div class="item-box" ng-repeat="item in data.encounter_results" ng-cloak>
					<div class="pull-right">
						<b >{{ data.status_results[item.status] }}</b>
					</div>
					<span class="text-info" data-toggle="tooltip" title="Req. title">{{item.title }}</span> <label data-toggle="tooltip" title="Result type" class="label label-warning">{{item.type_result}}</label>
					<p>{{item.comments }}</p>
				</div>
			</div>
			<div class="col-md-3">
				<div class="well well-sm text-center" style="margin:4px 0px;"><b>Derivaciones <span class="badge">{{ data.encounter_referrals.length}}</span></b></div>
				<div class="item-box" ng-cloak ng-repeat="item in data.encounter_referrals">
					<span class="text-info" data-toggle="tooltip" title="Service">{{item.service}}</span> <label data-toggle="tooltip" title="speciality" class="label label-default" style="font-size:12px;">{{item.speciality }}</label> <br>
					<p >{{item.reason }} </p>
					<div class="clearfix"></div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="well well-sm text-center" style="margin:4px 0px;"><b>Addendums <span class="badge">{{ data.encounter_addendums.length}}</span></b></div>
				<div class="item-box"  ng-cloak ng-repeat="item in data.encounter_addendums" >
				{{item.notes}}
					<br>
					<i class="fa fa-user" data-toggle="tooltip" title="User: {{item.nick_name}}"></i> {{ item.user}}<br>
					<i class="fa fa-clock-o" data-toggle="tooltip" title="Date: {{item.date}}"></i> {{ ngHelper.humanDate(item.create_at) }}<br>
				</div>
			</div>
		</div>
	</div>
</div>