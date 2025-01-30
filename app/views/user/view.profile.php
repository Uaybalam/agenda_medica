<form class="panel panel-default" autocomplete="off" action="/user/update/" method="post">
	<div class="panel-heading">
		<label>Perfil de usuario</label>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-7">
				<div class="form-horizontal" >
					<div class="form-group">
						<label class="col-sm-3 control-label" >Nombre de usuario</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" readonly="true"  value="<?= $_['user']->nick_name; ?>"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" >Tipo de usuario</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" readonly="true" value="<?= in_array($_['user']->access_type,$_['access_type']) ? $_['access_type'][$_['user']->access_type] : "Root"; ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Nombres</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="names" value="<?= $_['user']->names; ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Apellido</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="last_name" value="<?= $_['user']->last_name; ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">API KEY</label>
						<div class="col-sm-4">
							<input type="text" readonly class="form-control" name="api_key" value="<?= $_['user']->instance_id."-".$_['user']->id; ?>" />
							<p class="text-info text-center">Agregar esta clave en tu perfil medico de dirmedal para poder agedar citas</p>
						</div> 
						<label class="col-sm-1 control-label">PIN</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" name="pin" value="<?= $_['user']->pin; ?>" />
							<?php if($this->input->get('new') == 1 || $_['user']->pin==='' ) : ?>
								<p class="text-danger" style="margin:0px;">Establece tu palabra clave de identificación.</p>
							<?php endif; ?>
							<p class="text-info text-center">Esto te ayuda a firmar documentos de manera más fácil, utiliza números o palabras clave.</p>
						</div>	
					</div>
				</div>
			</div>
			<div class="col-sm-5">
				<div class="form-group"> 
					<div class="custom-checkbox custom-checkbox-sm">
						<label>
							Activar la verificacion de 2 pasos
					    	<input type="checkbox" id="g2fa" <?=$_['user']->active2fa ? "checked" : "" ?>/>
					    	<span class="checkbox"></span>
					  	</label>
					</div>
				</div>
				<div class="text-center" id="content-qr" style="display: <?=$_['user']->active2fa ? "block" : "none" ?>">
					<img src="<?=$_['user']->qrCodeUrl ?>" alt="">
				</div>
			</div>
		</div>
	</div> 
	<div class="panel-footer text-right">
			
		<button type="button" class="btn-md btn btn-success" data-toggle="modal" data-target="#user-modal-password"> Editar Contraseña </button>
		<button type="submit" class="btn-md btn btn-primary"> Actualizar </button>
	</div>
</form>