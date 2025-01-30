<div class="row" style="margin-top:5%;">
	<div class="col-sm-2"></div>
	<div class="col-sm-8">
		<div class="panel panel-primary">
			<div class="panel-heading text-center">
				<span style="font-size:26px;">Acceso</span>
			</div>
			<div class="panel-body">
				<?php echo(form_open('/login/intent', [
					'class' => 'form-horizontal',
					'id' => 'form-login',
					'autocomplete' => 'off'
				])); ?>
					<div class="form-group">
						<label class="col-md-2 control-label"> Usuario </label>
						<div class="col-md-10">
							<input type="text" autocorrect="off" autocapitalize="none" autocomplete="off" class="form-control" autofocus name="nick_name" value="<?= $_['nick_name']?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label"> Contraseña </label>
						<div class="col-md-10">
							<input type="text" autocorrect="off" autocapitalize="none" autocomplete="off" class="form-control input-password" name="password" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label"></label>
						<div class="col-md-10">
							<button class="btn btn-primary">Iniciar sesión  <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>  </button>
							
							<a class=" pull-right" href="/recover"> Recuperar contraseña</a>
							
						</div>
					</div>
				<?php echo(form_close()); ?>
			</div>
		</div>
	</div>
	<div class="col-sm-2"></div>
</div>
