<div class="row">
	<div class="col-md-8">
		<?php echo form_open('/encounter/sign/',[
			'class' => 'form-horizontal',
			'ng-submit' => 'confirmDelete($event)',
			'autocomplete' => 'off'
		]); ?>
			<div class="row" style="">
				<label class="col-sm-12 text-center text-warning">Advertencia: Si eliminas el archivo, no podrás recuperarlo.</label>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Titulo</label>
				<div class="col-sm-9" >
					<input type="text" class="form-control" readonly="true" ng-model="default.file.title" >
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">Paciente</label>
				<div class="col-sm-9" >
					<input type="text" class="form-control" readonly="true" ng-model="default.file.patient" >
				</div>
			</div>

			<div class="row" style="">
				<label class="col-sm-3 text-right">Razón de eliminarse</label>
				<div class="col-sm-9" >
					<input type="text" class="form-control" ng-model="default.file.reason_delete" >
					<span class="help-block"> Este mensaje se guardará en las advertencias del paciente.</span>
				</div>
			</div>

			<div class="row" style="">
				<label class="col-sm-3 text-right">Ingresa tu pin</label>
				<div class="col-sm-9" >
					<input type="text" class="form-control input-password" ng-model="default.file.pin" >
					<span class="help-block"> Tu puedes editar el PIN en <a href="/user/profile"> Perfil </a > </span>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
	<div class="col-md-4">
		<a  style="height:320px;" ng-href="/patient/related-files/open/{{default.file.id}}?random={{randomID}}" target="_blank" class="thumbnail">
			<img   style="height: 310px; width: 100%; display: block;object-fit: scale-down" 
			ng-src="/patient/related-files/open/{{default.file.id}}/preview/?random={{randomID}}"  data-holder-rendered="true" /> 
		</a>
	</div>
</div>
<div class="row" style="margin-bottom:0px;">
	<div class="col-lg-12 text-right well well-sm" style="margin-bottom:0px;">
		<button type="submit" class="btn btn-primary submit" > Confirmar delete </button>
	</div>
</div>