
<form ng-submit="submitBillComments($event)" action="#" method="POST">
	<div class="row" >
		<label class="col-sm-12">Comentario</label>
		<div class="col-sm-12" >
			<input type="text" class="form-control" ng-model="default_comments" >
		</div>
	</div>
	<div class="row" style="margin-bottom:20px;">
		<label class="col-sm-12">Pin de usuario</label>
		<div class="col-sm-12" >
			<input type="text" class="form-control input-password" ng-model="default_pin" >
			<span class="help-block"> Tu puedes editar el PIN en <a href="/user/profile"> Perfil </a > </span>
		</div>
	</div>

	<div class="row well well" style="margin-bottom:0px;">
		<div class="col-lg-12 text-right" style="margin-bottom:0px;">
			<button type="submit"  class="btn btn-danger submit" > Confirmar cambio a comentada</button>
		</div>
	</div>
</form> 