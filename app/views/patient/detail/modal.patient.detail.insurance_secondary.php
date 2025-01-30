<div class="row form-horizontal" >
	<div class="col-lg-12">
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Plan name </label>
			<div class="col-sm-9">
				<div class="input-group input-group-sm">
					<select class="form-control" ng-model="default.patient_secondary_i.insurance_secondary_plan_name" >
						<option value="">--Sin seguro--</option>
						<option ng-repeat="insurance in data.insurance_plans" value="{{insurance.name}}">{{ insurance.name }}</option>
					</select>
					<span class="input-group-btn"> 
						<a href="/settings" class="btn btn-success" type="button">Agregar</a> 
					</span>
				</div>
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Numero de seguro</label>
			<div class="col-sm-9">
				<input type="text" ng-model="default.patient_secondary_i.insurance_secondary_identify" class="form-control input-sm"   />
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Notas </label>
			<div class="col-sm-9">
				<textarea rows="3" class="form-control" ng-model="default.patient_secondary_i.insurance_secondary_notes"></textarea>
			</div>
		</div>
	</div>
</div>
<div class="row"> 
	<div class="col-sm-12 text-right well well-sm" style="margin:0px;"> 
		<button type="button" ng-click="action_insurance_secondary.submit()" class="btn btn-primary submit"> Actualizar </button> 
	</div> 
</div>