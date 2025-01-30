<div class="row">
	<div class="col-lg-12">
		<div class="form-horizontal">
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Razón</label>
				<div class="col-md-9">
					<textarea class="form-control" placeholder="Ejemplo: Capturar factura" ng-model="default.data_cancel.reason_cancel" rows="2" ></textarea>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">PIN de suario</label>
				<div class="col-md-9">
					<input type="text" class="form-control input-sm input-password" ng-model="default.data_cancel.pin" />
					<span class="help-block"> Tu puedes editar el PIN en <a href="/user/profile"> Perfil </a > </span>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row" > 
	<div class="col-sm-12 text-right well well-sm" style="margin:0px;"> 
		<button type="button" class="btn btn-primary submit" ng-click="action_checkout_cancel.set_checked_out()" > Enviar </button> 
	</div> 
</div>