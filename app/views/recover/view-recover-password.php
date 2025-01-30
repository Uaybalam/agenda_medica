<div class="row" style="margin-top:5%;">
	<div class="col-sm-2"></div>
	<div class="col-sm-8">
		<div class="panel panel-warning">
			<div class="panel-heading text-center">
				<span style="font-size:26px;">Recover Password</span>
			</div>
			<div class="panel-body">
				<?php echo(form_open('/recover/send', [
					'class' => 'form-horizontal',
					'id' => 'form-login',
					'autocomplete' => 'off'
				])); ?>
					<div class="form-group">
						<label class="col-md-2 control-label"> Email </label>
						<div class="col-md-10">
							<input type="text" autocorrect="off" autocapitalize="none" autocomplete="off" class="form-control" autofocus name="email" value="<?= $_['email']?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label"></label>
						<div class="col-md-10">
							<button class="btn btn-primary"> Send <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>  </button>
						</div>
					</div>
				<?php echo(form_close()); ?>
			</div>
		</div>
	</div>
	<div class="col-sm-2"></div>
</div>
