<?php echo form_open('/encounter/update/child/',[
	'class' => 'form-horizontal',
	'ng-submit' => 'action_childphysical.submit($event)',
	'autocomplete' => 'off'
]); ?>
	<div class="row">
		<div class="col-sm-3">
			<ul class="nav nav-pills nav-stacked well well-sm">
				<li class="active">
					<a href="#childphysical-basic" data-toggle="tab" aria-expanded="true">Basico</a>
				</li>
				<li class="">
					<a href="#childphysical-intervalhistory" data-toggle="tab" aria-expanded="true">Historia del intervalo</a>
				</li>
				<li class="">
					<a href="#childphysical-development" data-toggle="tab" aria-expanded="true">Desarrollo</a>
				</li>
				<li class="">
					<a href="#childphysical-physicalexamination" data-toggle="tab" aria-expanded="true">Examen fisico</a>
				</li>
				<li class="">
					<a href="#childphysical-tobacco" data-toggle="tab" aria-expanded="true">Tabaco</a>
				</li>
			</ul>
		</div>
		<div class="col-sm-9">
			<div class="tab-content">
				<div id="childphysical-basic" class="tab-pane fade active in">
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Código Étnico</label>
						<div class="col-sm-9">
							<select ng-model="default.encounter_child.ethnic_code" style="width:100%;">
								<option value="''">Seleciona un código etnico</option>
								<option ng-repeat="(key, value) in data.settings_ethnic_codes" value="{{key}}" >{{ value}}</option>
							</select>
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Tipo de prueba </label>
						<div class="col-sm-9">
							<div class="btn-group btn-group-sm " >
							    <label class="btn btn-default" ng-class="action_childphysical.active_radio( 'type_of_screen' , 'Initial')" ng-click="default.encounter_child.type_of_screen = 'Initial'">Inicial</label>
							    <label class="btn btn-default" ng-class="action_childphysical.active_radio( 'type_of_screen' , 'Periodic')" ng-click="default.encounter_child.type_of_screen = 'Periodic'">Periodico</label>
							</div>
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Derivado a WIC</label>
						<div class="col-sm-9">
							<div class="btn-group btn-group-sm " >
							    <label class="btn btn-default" ng-class="action_childphysical.active_radio( 'referred_to_wic' , 'Yes')" ng-click="default.encounter_child.referred_to_wic = 'Yes'">Si</label>
							    <label class="btn btn-default" ng-class="action_childphysical.active_radio( 'referred_to_wic' , 'No')" ng-click="default.encounter_child.referred_to_wic = 'No'">No</label>
							</div>
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Inscrito en WIC</label>
						<div class="col-sm-9">
							<div class="btn-group btn-group-sm " >
							    <label class="btn btn-default" ng-class="action_childphysical.active_radio( 'enrolled_in_wic' , 'Yes')" ng-click="default.encounter_child.enrolled_in_wic = 'Yes'">Si</label>
							    <label class="btn btn-default" ng-class="action_childphysical.active_radio( 'enrolled_in_wic' , 'No')" ng-click="default.encounter_child.enrolled_in_wic = 'No'">No</label>
							</div>
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Tratamiento</label>
						<div class="col-sm-9">
							<textarea rows="3"  class="form-control" ng-model="default.encounter_child.treatment"></textarea>
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Evaluación</label>
						<div class="col-sm-9">
							<textarea rows="3"  class="form-control" ng-model="default.encounter_child.assessment"></textarea>
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Evaluación de Riesgo de TB</label>
						<div class="col-sm-9">
							<div class="btn-group btn-group-sm" >
							    <label class="btn btn-default" ng-class="action_childphysical.active_radio( 'tb_risk' , '')" ng-click="default.encounter_child.tb_risk = ''">No verificado</label>
							    <label class="btn btn-default" ng-class="action_childphysical.active_radio( 'tb_risk' , 'Yes')" ng-click="default.encounter_child.tb_risk = 'Yes'">Con riesgo</label>
							    <label class="btn btn-default" ng-class="action_childphysical.active_radio( 'tb_risk' , 'No')" ng-click="default.encounter_child.tb_risk = 'No'">Sin riesgo</label>
							</div>
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Cuestionario de Riesgo de Plomo</label>
						<div class="col-sm-9">
							<div class="btn-group btn-group-sm" >
							    <label class="btn btn-default" ng-class="action_childphysical.active_radio( 'lead_risk' , '')" ng-click="default.encounter_child.lead_risk = ''">No verificado</label>
							    <label class="btn btn-default" ng-class="action_childphysical.active_radio( 'lead_risk' , 'Yes')" ng-click="default.encounter_child.lead_risk = 'Yes'">Con riesgo</label>
							    <label class="btn btn-default" ng-class="action_childphysical.active_radio( 'lead_risk' , 'No')" ng-click="default.encounter_child.lead_risk = 'No'">Sin riesgo</label>
							</div>
						</div>
					</div>
				</div>
				<div id="childphysical-intervalhistory" class="tab-pane fade">
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Dieta</label>
						<div class="col-sm-9">
							<input type="text"  class="form-control" ng-model="default.encounter_child.interval_history_diet" />
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Enfermedad</label>
						<div class="col-sm-9">
							<input type="text"  class="form-control" ng-model="default.encounter_child.interval_history_illness" />
						</div>	
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Problemas</label>
						<div class="col-sm-9">
							<input type="text"  class="form-control" ng-model="default.encounter_child.interval_history_problems" />
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Reacción a la inmunización</label>
						<div class="col-sm-9">
							<input type="text"  class="form-control" ng-model="default.encounter_child.interval_history_immunization" />
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Preocupaciones de los padres</label>
						<div class="col-sm-9">
							<input type="text"  class="form-control" ng-model="default.encounter_child.interval_history_parental_concerns" />
						</div>
					</div>
				</div>
				<div id="childphysical-development" class="tab-pane fade">
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Desarrollo</label>
						<div class="col-sm-9">
							<div class="btn-group btn-group-sm" >
							    <label class="btn btn-default" ng-class="action_childphysical.active_radio('development_result','')" ng-click="default.encounter_child.development_result = ''">Sin verificación</label>
							    <label class="btn btn-default" ng-class="action_childphysical.active_radio('development_result','Normal')" ng-click="default.encounter_child.development_result = 'Normal'">Normal</label>
							    <label class="btn btn-default" ng-class="action_childphysical.active_radio('development_result','Abnormal')" ng-click="default.encounter_child.development_result = 'Abnormal'">Aormal</label>
							</div>
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Opciones</label>
						<div class="col-sm-9">
							<select ng-model="default.encounter_child.development_options" multiple="true"  style="width:100%;">
								<option ng-repeat="item in data.development_options_default" >{{ item}}</option>
							</select>
							<span class="help-block">Para pacientes menores de 20 años</span>
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Plan</label>
						<div class="col-sm-9">
							<select ng-model="default.encounter_child.development_plan" multiple="true"  style="width:100%;">
								<option ng-repeat="item in data.development_plan_default" >{{ item}}</option>
							</select>
							<span class="help-block">Para pacientes menores de 20 años</span>
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-3 control-label">Educación</label>
						<div class="col-sm-9">
							<select ng-model="default.encounter_child.educations" multiple="true"  style="width:100%;">
								<option ng-repeat="item in data.options_educations_default" >{{ item}}</option>
							</select>
						</div>
					</div>
				</div>
				<div id="childphysical-physicalexamination" class="tab-pane fade">
					
					<div class="form-group form-group-sm">
						<label class="col-sm-2 control-label">Apariencia general</label>
						<div  class="col-sm-3 text-center">
							<div class="btn-group btn-group-sm" >
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_general_appearance' , 'N')" ng-click="default.encounter_child.physical_result_general_appearance = 'N'">Normal</label>
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_general_appearance' , 'AB')" ng-click="default.encounter_child.physical_result_general_appearance = 'AB'">Anormal</label>
							</div>
						</div>
						<div class="col-sm-7">
							<input type="text" placeholder="Comentarios sobre anormalidades" class="form-control input-sm" ng-model="default.encounter_child.physical_comments_general_appearance" />	
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-2 control-label">Nutrición</label>
						<div  class="col-sm-3 text-center">
							<div class="btn-group btn-group-sm" >
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_nutrition' , 'N')" ng-click="default.encounter_child.physical_result_nutrition = 'N'">Normal</label>
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_nutrition' , 'AB')" ng-click="default.encounter_child.physical_result_nutrition = 'AB'">Anormal</label>
							</div>
						</div>
						<div class="col-sm-7">
							<input type="text" placeholder="Comentarios sobre anormalidades" class="form-control input-sm" ng-model="default.encounter_child.physical_comments_nutrition" />	
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-2 control-label">Piel</label>
						<div  class="col-sm-3 text-center">
							<div class="btn-group btn-group-sm" >
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_skin' , 'N')" ng-click="default.encounter_child.physical_result_skin = 'N'">Normal</label>
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_skin' , 'AB')" ng-click="default.encounter_child.physical_result_skin = 'AB'">Anormal</label>
							</div>
						</div>
						<div class="col-sm-7">
							<input type="text" placeholder="Comentarios sobre anormalidades" class="form-control input-sm" ng-model="default.encounter_child.physical_comments_skin" />	
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-2 control-label">Cabeza, cuello y ganglios</label>
						<div  class="col-sm-3 text-center">
							<div class="btn-group btn-group-sm" >
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_head_neck_nodes' , 'N')" ng-click="default.encounter_child.physical_result_head_neck_nodes = 'N'">Normal</label>
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_head_neck_nodes' , 'AB')" ng-click="default.encounter_child.physical_result_head_neck_nodes = 'AB'">Anormal</label>
							</div>
						</div>
						<div class="col-sm-7">
							<input type="text" placeholder="Comentarios sobre anormalidades" class="form-control input-sm" ng-model="default.encounter_child.physical_comments_head_neck_nodes" />	
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-2 control-label">Ojos/reflejo equitativo</label>
						<div  class="col-sm-3 text-center">
							<div class="btn-group btn-group-sm" >
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_eyes_eq_reflex' , 'N')" ng-click="default.encounter_child.physical_result_eyes_eq_reflex = 'N'">Normal</label>
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_eyes_eq_reflex' , 'AB')" ng-click="default.encounter_child.physical_result_eyes_eq_reflex = 'AB'">Anormal</label>
							</div>
						</div>
						<div class="col-sm-7">
							<input type="text" placeholder="Comentarios sobre anormalidades" class="form-control input-sm" ng-model="default.encounter_child.physical_comments_eyes_eq_reflex" />	
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-2 control-label">Oídos/Audición</label>
						<div  class="col-sm-3 text-center">
							<div class="btn-group btn-group-sm" >
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_ent_hearing' , 'N')" ng-click="default.encounter_child.physical_result_ent_hearing = 'N'">Normal</label>
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_ent_hearing' , 'AB')" ng-click="default.encounter_child.physical_result_ent_hearing = 'AB'">Anormal</label>
							</div>
						</div>
						<div class="col-sm-7">
							<input type="text" placeholder="Comentarios sobre anormalidades" class="form-control input-sm" ng-model="default.encounter_child.physical_comments_ent_hearing" />	
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-2 control-label">Boca/Dental</label>
						<div  class="col-sm-3 text-center">
							<div class="btn-group btn-group-sm" >
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_mouth_dental' , 'N')" ng-click="default.encounter_child.physical_result_mouth_dental = 'N'">Normal</label>
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_mouth_dental' , 'AB')" ng-click="default.encounter_child.physical_result_mouth_dental = 'AB'">Anormal</label>
							</div>
						</div>
						<div class="col-sm-7">
							<input type="text" placeholder="Comentarios sobre anormalidades" class="form-control input-sm" ng-model="default.encounter_child.physical_comments_mouth_dental" />	
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-2 control-label">Pecho/Pulmones</label>
						<div  class="col-sm-3 text-center">
							<div class="btn-group btn-group-sm" >
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_chest_lungs' , 'N')" ng-click="default.encounter_child.physical_result_chest_lungs = 'N'">Normal</label>
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_chest_lungs' , 'AB')" ng-click="default.encounter_child.physical_result_chest_lungs = 'AB'">Anormal</label>
							</div>
						</div>
						<div class="col-sm-7">
							<input type="text" placeholder="Comentarios sobre anormalidades" class="form-control input-sm" ng-model="default.encounter_child.physical_comments_chest_lungs" />	
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-2 control-label">Corazón</label>
						<div  class="col-sm-3 text-center">
							<div class="btn-group btn-group-sm" >
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_heart' , 'N')" ng-click="default.encounter_child.physical_result_heart = 'N'">Normal</label>
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_heart' , 'AB')" ng-click="default.encounter_child.physical_result_heart = 'AB'">Anormal</label>
							</div>
						</div>
						<div class="col-sm-7">
							<input type="text" placeholder="Comentarios sobre anormalidades" class="form-control input-sm" ng-model="default.encounter_child.physical_comments_heart" />	
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-2 control-label">Abdomen</label>
						<div  class="col-sm-3 text-center">
							<div class="btn-group btn-group-sm" >
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_abdomen' , 'N')" ng-click="default.encounter_child.physical_result_abdomen = 'N'">Normal</label>
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_abdomen' , 'AB')" ng-click="default.encounter_child.physical_result_abdomen = 'AB'">Anormal</label>
							</div>
						</div>
						<div class="col-sm-7">
							<input type="text" placeholder="Comentarios sobre anormalidades" class="form-control input-sm" ng-model="default.encounter_child.physical_comments_abdomen" />	
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-2 control-label">Genitales externos</label>
						<div  class="col-sm-3 text-center">
							<div class="btn-group btn-group-sm" >
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_external_genitalia' , 'N')" ng-click="default.encounter_child.physical_result_external_genitalia = 'N'">Normal</label>
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_external_genitalia' , 'AB')" ng-click="default.encounter_child.physical_result_external_genitalia = 'AB'">Anormal</label>
							</div>
						</div>
						<div class="col-sm-7">
							<input type="text" placeholder="Comentarios sobre anormalidades" class="form-control input-sm" ng-model="default.encounter_child.physical_comments_external_genitalia" />	
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-2 control-label">Espalda</label>
						<div  class="col-sm-3 text-center">
							<div class="btn-group btn-group-sm" >
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_back' , 'N')" ng-click="default.encounter_child.physical_result_back = 'N'">Normal</label>
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_back' , 'AB')" ng-click="default.encounter_child.physical_result_back = 'AB'">Anormal</label>
							</div>
						</div>
						<div class="col-sm-7">
							<input type="text" placeholder="Comentarios sobre anormalidades" class="form-control input-sm" ng-model="default.encounter_child.physical_comments_back" />	
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-2 control-label">Extremidades/Caderas</label>
						<div  class="col-sm-3 text-center">
							<div class="btn-group btn-group-sm" >
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_extremities_hips' , 'N')" ng-click="default.encounter_child.physical_result_extremities_hips = 'N'">Normal</label>
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_extremities_hips' , 'AB')" ng-click="default.encounter_child.physical_result_extremities_hips = 'AB'">Anormal</label>
							</div>
						</div>
						<div class="col-sm-7">
							<input type="text" placeholder="Comentarios sobre anormalidades" class="form-control input-sm" ng-model="default.encounter_child.physical_comments_extremities_hips" />	
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-2 control-label">Neurológico</label>
						<div  class="col-sm-3 text-center">
							<div class="btn-group btn-group-sm" >
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_neurological' , 'N')" ng-click="default.encounter_child.physical_result_neurological = 'N'">Normal</label>
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_neurological' , 'AB')" ng-click="default.encounter_child.physical_result_neurological = 'AB'">Anormal</label>
							</div>
						</div>
						<div class="col-sm-7">
							<input type="text" placeholder="Comentarios sobre anormalidades" class="form-control input-sm" ng-model="default.encounter_child.physical_comments_neurological" />	
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-2 control-label">Pulsos femorales</label>
						<div  class="col-sm-3 text-center">
							<div class="btn-group btn-group-sm" >
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_fem_pulses' , 'N')" ng-click="default.encounter_child.physical_result_fem_pulses = 'N'">Normal</label>
							    <label class="btn btn-default btn-sm" ng-class="action_childphysical.active_radio( 'physical_result_fem_pulses' , 'AB')" ng-click="default.encounter_child.physical_result_fem_pulses = 'AB'">Anormal</label>
							</div>
						</div>
						<div class="col-sm-7">
							<input type="text" placeholder="Comentarios sobre anormalidades" class="form-control input-sm" ng-model="default.encounter_child.physical_comments_fem_pulses" />	
						</div>
					</div>
				</div>
				<div id="childphysical-tobacco" class="tab-pane fade">
					<div class="form-group form-group-sm">
						<label class="col-sm-8 control-label">El paciente está expuesto al humo de tabaco pasivo (segunda mano)</label>
						<div class="col-sm-4">
							<div class="btn-group btn-group-sm" >
							   	<label class="btn btn-default" ng-class="action_childphysical.active_radio( 'tobacco_patient_exposed' , 'Yes')" ng-click="default.encounter_child.tobacco_patient_exposed = 'Yes'">Si</label>
							    <label class="btn btn-default" ng-class="action_childphysical.active_radio( 'tobacco_patient_exposed' , 'No')" ng-click="default.encounter_child.tobacco_patient_exposed = 'No'">No</label>
							</div>
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-8 control-label">Uso de tabaco por parte del paciente</label>
						<div class="col-sm-4">
							<div class="btn-group btn-group-sm" >
							   	<label class="btn btn-default" ng-class="action_childphysical.active_radio( 'tobacco_used_by_patient' , 'Yes')" ng-click="default.encounter_child.tobacco_used_by_patient = 'Yes'">Si</label>
							    <label class="btn btn-default" ng-class="action_childphysical.active_radio( 'tobacco_used_by_patient' , 'No')" ng-click="default.encounter_child.tobacco_used_by_patient = 'No'">No</label>
							</div>
						</div>
					</div>
					<div class="form-group form-group-sm">
						<label class="col-sm-8 control-label">Asesorado sobre/Referido para la prevención/cesación del uso de tabaco</label>
						<div class="col-sm-4">
							<div class="btn-group btn-group-sm" >
							   	<label class="btn btn-default" ng-class="action_childphysical.active_radio( 'tobacco_prevention_referred' , 'Yes')" ng-click="default.encounter_child.tobacco_prevention_referred = 'Yes'">Si</label>
							    <label class="btn btn-default" ng-class="action_childphysical.active_radio( 'tobacco_prevention_referred' , 'No')" ng-click="default.encounter_child.tobacco_prevention_referred = 'No'">No</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row"> 
		<div class="col-sm-12 text-right well well-sm" style="margin:0px;"> 
			<button type="submit" class="btn btn-primary submit"> Guardar </button> 
		</div> 
</div>
</form>