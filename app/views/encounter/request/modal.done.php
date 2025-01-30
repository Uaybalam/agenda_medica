<div class="row">
	<div class="col-lg-12">
		<div class="form-horizontal">
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">PIN de usuario</label>
				<div class="col-md-9">
					<input type="text" class="form-control input-sm input-password" ng-model="default.data_done.pin" />
					<span class="help-block"> Tu puedes editar el PIN en <a href="/user/profile"> Perfil </a > </span>
				</div>
			</div>
			<div  ng-show="data.invoice.total>0 && data.invoice.paid!=data.invoice.total">
				<div class="form-group form-group-sm">
					<label class="col-md-3 control-label text-danger"></label>
					<div class="col-md-9 text-danger">
						<label>
							<input type="checkbox" name="chkAuthBalanceDue" ng-model="data.invoice.auth_balancedue" ng-true-value="'On'" ng-false-value="'Off'"  />
							<span class="text-danger" style="font-weight: 300;">Authorize to save changes it with balance due of <strong>$ {{ data.invoice.balance_due}}</strong></span>
						</label>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row" > 
	<div class="col-sm-12 text-right well well-sm" style="margin:0px;"> 
		<button ng-disabled="checkDisabled()"  type="button" class="btn btn-primary submit" ng-click="action_encounter.set_checked_out()" > Enviar </button> 
	</div> 
</div>