
<div class="row form-horizontal" >
	<div class="col-lg-12">
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Dirección </label>
			<div class="col-sm-9">
				<input type="text" ng-model="default.user_address.address" class="form-control input-sm"   />
			</div>
		</div>
		
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Codigo Postal </label>
			<div class="col-sm-9">
				<input placeholder="Autocompleta ciudad/estado" type="text" ng-model="default.user_address.address_zipcode" class="form-control input-sm" ng-change="changeZipCode.toUser(default.user_address.address_zipcode)"  />
			</div>
		</div>
		<div class="form-group form-group-sm" >
			<label class="col-sm-3 control-label"> Ciudad </label>
			<div class="col-sm-3">
				<input type="text" ng-model="default.user_address.address_city" class="form-control input-sm"   />
			</div>
			<label class="col-sm-3 control-label"> Estado </label>
			<div class="col-sm-3">
				<input type="text" ng-model="default.user_address.address_state" class="form-control input-sm"   />
			</div>
		</div>
	</div>
</div>
<div class="row"> 
	<div class="col-sm-12 text-right well well-sm" style="margin:0px;"> 
		<button type="button" ng-click="action_address.update()" class="btn btn-primary submit"> Actualizar </button> 
	</div> 
</div>