<div class="row form-horizontal">
	<div class="col-lg-12">
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label">Created at</label>
			<div class="col-md-9">
				<input readonly="true" ng-model="default.warning_reply.create_at" class="form-control input-sm" />
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label">Created By</label>
			<div class="col-md-9">
				<input readonly="true" ng-model="default.warning_reply.user_create" class="form-control input-sm" />
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label">Description</label>
			<div class="col-md-9">
				<textarea ng-model="default.warning_reply.description" readonly="true"  class="form-control input-sm"></textarea>
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label">Message reply</label>
			<div class="col-md-9">
				<textarea ng-model="default.warning_reply.description_reply"  class="form-control input-sm"></textarea>
			</div>
		</div>
	</div>
</div>
<div class="row well well-sm" style="margin-bottom:0px;"> 
	<div class="col-lg-12 text-right" style="margin-bottom:0px;"> 
		<button ng-click="action_warning.update_reply()" type="button" class="btn btn-primary">Submit</button>
	</div>
</div>