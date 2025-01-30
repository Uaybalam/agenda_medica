<div class="row form-horizontal">
	<div class="form-group">
		<div class="col-md-1">
			
		</div>
		<div class="col-md-10" style="font-size:12px;">
			<label class="radio" >
				<input type="radio" name="filter_compare_encounter" ng-change="filter_compare()" ng-model="default.filter_compare" value="_vitals_basic"  /><span>Motivo de Consulta</span>	
			</label>
			<label class="radio" >
				<input type="radio" name="filter_compare_encounter" ng-change="filter_compare()" ng-model="default.filter_compare" value="_vitals_heart"  />
				<span>  <i class="fa fa-line-chart"></i> Signos vitales cardíacos</span>	
			</label>
			<label class="radio" >
				<input type="radio" name="filter_compare_encounter" ng-change="filter_compare()" ng-model="default.filter_compare" value="_vitals_physical"  />
				<span> <i class="fa fa-line-chart"></i> Signos vitales físicos</span>

			</label>
			<label class="radio" >
				<input type="radio" name="filter_compare_encounter" ng-change="filter_compare()" ng-model="default.filter_compare" value="_blood_pressure"  />
				<span> <i class="fa fa-line-chart"></i> Presión sanguinea</span>	
			</label>
			<label class="radio" >
				<input type="radio" name="filter_compare_encounter" ng-change="filter_compare()" ng-model="default.filter_compare" value="_vitals_eyes"  />
				<span> Signos vitales oculares</span>	
			</label>
			<label class="radio" >
				<input type="radio" name="filter_compare_encounter" ng-change="filter_compare()" ng-model="default.filter_compare" value="_vitals_audio"  /><span>Signos vitales auditivos</span>	
			</label>
			<label class="radio" >
				<input type="radio" name="filter_compare_encounter" ng-change="filter_compare()" ng-model="default.filter_compare" value="_vitals_urinalysis"  /><span>Signos vitales urinarios</span>	
			</label>
			<label class="radio" >
				<input type="radio" name="filter_compare_encounter" ng-change="filter_compare()" ng-model="default.filter_compare" value="_illness_history"  /><span>Historial de enfermedades</span>	
			</label>
			<label class="radio" >
				<input type="radio" name="filter_compare_encounter" ng-change="filter_compare()" ng-model="default.filter_compare" value="_physical_exam"  /><span>Examen físico</span>	
			</label>
			<label class="radio" >
				<input type="radio" name="filter_compare_encounter" ng-change="filter_compare()" ng-model="default.filter_compare" value="_diagnosis"  /><span>Diagnostico</span>	
			</label>
			<label class="radio" >
				<input type="radio" name="filter_compare_encounter" ng-change="filter_compare()" ng-model="default.filter_compare" value="_procedure"  /><span>Educación del paciente</span>	
			</label>
			<label class="radio" >
				<input type="radio" name="filter_compare_encounter" ng-change="filter_compare()" ng-model="default.filter_compare" value="_medications"  /><span>Medicaciones</span>	
			</label>
			<label class="radio" >
				<input type="radio" name="filter_compare_encounter" ng-change="filter_compare()" ng-model="default.filter_compare" value="_results"  /><span>  <i class="fa fa-file-text-o"></i> Solicitudes</span>	
			</label>
			<label class="radio" >
				<input type="radio" name="filter_compare_encounter" ng-change="filter_compare()" ng-model="default.filter_compare" value="_referrals"  /><span>Derivaciones</span>	
			</label>
		</div>
		<label class="col-md-3"></label>
	</div>
</div>
<div class="row">
	<div class="col-lg-12" id="encounter_compare_result"></div>
</div>