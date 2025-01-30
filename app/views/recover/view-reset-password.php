<div class="row" style="margin-top:5%;">
	<div class="col-sm-2"></div>
	<div class="col-sm-8">
		<div class="panel panel-warning">
			<div class="panel-heading text-center">
				<span style="font-size:26px;">Recover Password</span>
			</div>
			<div class="panel-body">
				<?php echo(form_open('/recover/change', [
					'class' => 'form-horizontal',
					'id' => 'form-login',
					'autocomplete' => 'off'
				])); ?>
					<div class="form-group">
						<label class="col-md-3 control-label"> Email </label>
						<div class="col-md-9">
							<input type="text" readonly="true" autocorrect="off" autocapitalize="none" autocomplete="off" class="form-control"  name="email" value="<?= $_['email']?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"> Token </label>
						<div class="col-md-9">
							<input type="text" readonly="true" autocorrect="off" class="form-control"  name="token" value="<?= $_['token']?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"> New Password </label>
						<div class="col-md-9">
							<input type="text" autocorrect="off" class="form-control input-password" autofocus name="password"  />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"> Confirm Password </label>
						<div class="col-md-9">
							<input type="text" autocorrect="off" class="form-control input-password"  name="password_confirm"  />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"></label>
						<div class="col-md-9">
							<button class="btn btn-primary"> Save <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>  </button>
						</div>
					</div>
				<?php echo(form_close()); ?>
			</div>
		</div>
	</div>
	<div class="col-sm-2"></div>
</div>
