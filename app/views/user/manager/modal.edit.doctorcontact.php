<div class="row form-horizontal" >
	<div class="col-lg-12">
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Nombre completo </label>
			<div class="col-sm-9">
				<input type="text" ng-model="default.user_doctorcontact.emergency_contact_doctor_name" class="form-control input-sm"   />
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Dirección </label>
			<div class="col-sm-9">
				<input type="text" ng-model="default.user_doctorcontact.emergency_contact_doctor_address" class="form-control input-sm"   />
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label">Teléfono</label>
			<div class="col-md-9">
				<input ng-model="default.user_doctorcontact.emergency_contact_doctor_phone" class="form-control input-sm" data-mask="999 999 999" placeholder="Lada y numero"/>
			</div>
		</div>
	</div>
</div>
<div class="row"> 	
	<div class="col-sm-12 text-right well well-sm" style="margin:0px;"> 
		<button type="button" ng-click="action_doctorcontact.update()" class="btn btn-primary submit"> Update </button> 
	</div> 
</div>