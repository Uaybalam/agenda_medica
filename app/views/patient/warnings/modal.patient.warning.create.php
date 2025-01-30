<div class="form-horizontal">
	<div class="form-group form-group-sm">
		<label class="col-md-3 control-label">Descripión</label>
		<div class="col-md-9">
			<textarea class="form-control" placeholder="Describe notes" ng-model="default.warning.description"></textarea>
		</div>
	</div>
	<div class="form-group form-group-sm">
		<label class="col-md-3 control-label">¿Require respuesta?</label>
		<div class="col-md-9">
			<div class="btn-group btn-group-sm">
				<label class="btn btn-default" ng-class="(default.warning.request_reply==0) ? 'active' : ''" ng-click="default.warning.request_reply=0" > No </label>
				<label class="btn btn-default" ng-class="(default.warning.request_reply==1) ? 'active' : ''" ng-click="default.warning.request_reply=1" > Si </label>
			</div>
		</div>
	</div>
</div>
<div class="row" style="margin-bottom:0px;">
	<div class="col-lg-12 text-right well well-sm" style="margin-bottom:0px;">
		<button type="button" ng-click="action_warning.submit()" class="btn btn-primary submit"> Enviar </button>
	</div>
</div>