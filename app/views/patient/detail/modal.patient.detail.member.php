<div class="row form-horizontal" >
	<div class="col-lg-12">
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Nombre/Titulo </label>
			<div class="col-sm-9">
				<input type="text" ng-model="default.patient_member.membership_name" class="form-control input-sm"   />
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Tipo </label>
			<div class="col-sm-9">
				<input type="text" ng-model="default.patient_member.membership_type" class="form-control input-sm"   />
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Fecha </label>
			<div class="col-sm-9">
				<input type="text" ng-model="default.patient_member.membership_date" class="form-control input-sm create-datepicker"   />
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Notas </label>
			<div class="col-sm-9">
				<textarea rows="3" class="form-control" ng-model="default.patient_member.membership_notes"></textarea>
			</div>
		</div>
	</div>
</div>
<div class="row"> 
	<div class="col-sm-12 text-right well well-sm" style="margin:0px;"> 
		<button type="button" ng-click="action_member.submit()" class="btn btn-primary submit"> Actualizar </button> 
	</div> 
</div>