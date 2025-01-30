<?= form_open('/user/edit/password/', [
		'class' => 'row form-horizontal',
		'autocomplete' => 'off'	
	]); ?>
		<div style="visibility:hidden;width:0px;height:0px;">
			<input type="text" name="hidden_browser[]"/>
			<input type="password" name="hidden_browser[]" />
		</div>
		<div class="col-lg-12"  >
			<div class="form-group">	
				<label class="col-sm-3 control-label" > Antigua contraseña</label>
				<div class="col-sm-9">
			     	<input type="password"  class="form-control" name="password_old"  />
			    </div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label" > Nueva contraseña</label>
				<div class="col-sm-9">
			     	<input type="password"  class="form-control" name="password_new"  />
			    </div>
			</div>
			<div class="form-group">	
				<label class="col-sm-3 control-label" > Confirmar contraseña</label>
				<div class="col-sm-9">
			     	<input type="password"  class="form-control" name="password_confirm"  />
			    </div>
			</div>
			<div class="form-group">	
				<label class="col-sm-3 control-label" ></label>
				<div class="col-sm-5">		
			     	<button type="submit" class="btn btn-primary"  > Guardar </button>
				</div>
			</div>
		</div>
<?= form_close();?>