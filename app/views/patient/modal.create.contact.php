<div class="row form-horizontal">
	<div class="col-lg-12">
		<div class="form-group">
			<label class="col-lg-3 control-label">Paciente</label>
			<div class="col-lg-9">
				<input readonly="true" class="form-control" type="text" ng-model="default.contact.full_name" >
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label">Razón</label>
			<div class="col-lg-9">
				<textarea rows="3" class="form-control" type="text" ng-model="default.contact.reason" ></textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3"></label>
			<div class="col-lg-9">
				<button type="button" ng-click="action_contact.submit()" class="btn btn-primary">Enviar</button>
			</div>
		</div>
	</div>
</div>