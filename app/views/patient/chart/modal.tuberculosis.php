<div class="row form-horizontal" >
	<div class="col-lg-12">
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Tipo </label>
			<div class="col-sm-9">
				<input type="text" ng-model="default.tuberculosis.type" class="form-control "   />
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Resultado </label>
			<div class="col-sm-9">
				<input type="text" ng-model="default.tuberculosis.result" class="form-control "   />
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Tamaño </label>
			<div class="col-sm-9">
				<input type="text" ng-model="default.tuberculosis.size" class="form-control "   />
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Fecha </label>
			<div class="col-sm-9">
				<input type="text" placeholder="Mes/Día/Año" ng-model="default.tuberculosis.date" class="form-control create-datepicker"   />
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Induración </label>
			<div class="col-sm-9">
				<input type="text" ng-model="default.tuberculosis.induration" class="form-control "   />
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Revisado por</label>
			<div class="col-sm-9">
				<input type="text" ng-model="default.tuberculosis.read_by" class="form-control "   />
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Fecha de revisión </label>
			<div class="col-sm-9">
				<input type="text" placeholder="Mes/Día/Año" ng-model="default.tuberculosis.date_read" class="form-control create-datepicker"   />
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Evaluación de riesgos </label>
			<div class="col-sm-9">
				<select ng-model="default.tuberculosis.risk_assessment" class="form-control">
					<option value=""></option>
					<option value="Yes">Si</option>
					<option value="No">No</option>
				</select>
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label">Radiografía de tórax</label>
			<div class="col-sm-9">
				<input type="text" ng-model="default.tuberculosis.chest_x_ray" class="form-control "   />
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> ¿Se proporciono tratamiento? </label>
			<div class="col-sm-9">
				<select ng-model="default.tuberculosis.treatment_given" class="form-control">
					<option value=""></option>
					<option value="Yes">Si</option>
					<option value="No">No</option>
				</select>
			</div>
		</div>
		<div class="form-group form-group-sm" ng-show="default.tuberculosis.treatment_given=='Yes'">
			<label class="col-sm-3 control-label"> Inicio de tratamiento </label>
			<div class="col-sm-3">
				<input type="text" placeholder="Mes/Día/Año" ng-model="default.tuberculosis.treatment_start_date" class="form-control create-datepicker"   />
			</div>
			<label class="col-sm-3 control-label"> Fin de tratamiento </label>
			<div class="col-sm-3">
				<input type="text" placeholder="Mes/Día/Año" ng-model="default.tuberculosis.treatment_end_date" class="form-control create-datepicker"   />
			</div>
		</div>
	</div>
</div>
<div class="row"> 
	<div class="col-sm-12 text-right well well-sm" style="margin:0px;"> 
		<button type="button" ng-click="action_tuberculosis.submit()" class="btn btn-primary submit"> Guardar </button> 
	</div> 
</div>