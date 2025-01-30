<div class="row">
	<div class="col-md-6">
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label"> <i class="fa fa-question-circle-o" data-toggle="tooltip" title="27. ¿ACEPTA LA ASIGNACIÓN? (Para reclamaciones gubernamentales, ver en la parte posterior)"></i> Aceptar asignación</label>
			<div class="col-md-9">
				<label class="radio">
					<input ng-disabled="data.not_edit" value="Yes" ng-model="data.billing.accept_assignment" type="radio" name="accept_assignemnt">
					<span>Si</span>
				</label>
				<label class="radio">
					<input ng-disabled="data.not_edit" value="No" ng-model="data.billing.accept_assignment" type="radio" name="accept_assignemnt">
					<span>No</span>
				</label>
			</div>
		</div>
		<!--
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label"> <i class="fa fa-question-circle-o" data-toggle="tooltip" title="30. Rsvd for NUCC Use"></i> Rsvd for NUCC</label>
			<div class="col-md-9">
				<input ng-readonly="data.not_edit" type="text"  ng-model="data.billing.rsvd_for_nucc" class="form-control input-sm"  />
			</div>
		</div>
		-->
	</div>
	<div class="col-md-6">
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label"> <i class="fa fa-question-circle-o" data-toggle="tooltip" title="28. TOTAL DE CARGOS"></i> Total de cargos</label>
			<div class="col-md-9">
				<input ng-readonly="true" type="text" ng-model="data.billing.total_charge" class="form-control input-sm" placeholder=" $ 0.0" />
			</div>
		</div>

		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label">Estados Actuales</label>
			<div class="col-md-9">
				<input readonly="true" type="text" ng-model="data.billing.status_str" class="form-control input-sm"  />
			</div>
		</div>
	
		
	</div>
</div>
