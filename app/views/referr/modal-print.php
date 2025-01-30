<form autocomplete="OFF" class="from form-horizontal" target="_blank" method="POST" action="{{'/encounter/referrals/' + action_referrals.form.id+'/pdf'}}">
	<div class="form-group form-group-sm">
		<label class="col-sm-2 control-label">Especialidad</label>
		<div class="col-sm-10">
			<input type="text" class="form-control"  ng-model="action_referrals.form.speciality" readonly="true" />
		</div>
	</div>
	<div class="form-group form-group-sm">
		<label class="col-sm-2 control-label">Servicio</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" ng-model="action_referrals.form.service" readonly="true" />
		</div>
	</div>
	<div class="form-group form-group-sm">
		<label class="col-sm-2 control-label">Razón</label>
		<div class="col-sm-10">
			<textarea ng-model="action_referrals.form.reason" readonly="true" class="form-control" rows="3"></textarea>
		</div>
	</div>
	<div class="form-group form-group-sm">
		<label class="col-sm-2 control-label">Gravedad</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" ng-model="action_referrals.form.acuity"  readonly="true" />
		</div>
	</div>
	<div class="form-group form-group-sm">
		<label class="col-sm-2 control-label">Estatus</label>
		<div class="col-sm-10">
			<select class="form-control" name="status"  ng-model="action_referrals.form.status"  >
				<?php foreach($_['referr_status'] as $key =>  $item ) : 
					if($key==0 ) continue;
					?>
					<option value="<?= $key?>"><?= $item ?></option>
				<?php endforeach; ?>
			</select>
			
		</div>
	</div>
	<div class="form-group form-group-sm">
		<label class="col-sm-2 control-label">Código ICD-10</label>
		<div class="col-sm-10">
			<input type="text" ng-model="action_referrals.form.print_icd_code" class="form-control" name="icdCode" />
		</div>
	</div>
	
	<div class="form-group form-group-sm">
		<label class="col-sm-2 control-label">Diagnostico</label>
		<div class="col-sm-4" >
			<?php foreach($_['diagnosis'] as $item ) : ?>
				<label><input checked="true" type="checkbox" value="<?= $item->comment?>" name="referrDiagnosis[]" /> <?= $item->comment?></label></br>
			<?php endforeach; ?>
		</div>
		<label class="col-sm-2 control-label">Diagnostico Extra</label>
		<div class="col-sm-4" >
			<textarea ng-model="action_referrals.form.print_extra_diagnosis" class="form-control" rows="2" name="extraDiagnosis"></textarea>
		</div>
	</div>

	<div class="form-group form-group-sm">
		<label class="col-sm-2 control-label">Servicios solicitados</label>
		<div class="col-sm-10">
			<textarea ng-model="action_referrals.form.print_services_requested" name="servicesRequested" class="form-control" rows="3"></textarea>
		</div>
	</div>
	<div class="form-group form-group-sm">
		<label class="col-sm-2 control-label"></label>
		<div class="col-sm-10">
			<button class="btn btn-warning" type="submit"> Imprimir </button>
		</div>
	</div> 
	<div class="row" ng-cloak ng-show="action_referrals.form.print_user">
		<div class="well well-sm text-right" style="margin-bottom: 0px;">
			Última fecha de impresión <b>{{ngHelper.formatDate(action_referrals.form.print_date)}}</b> <br>
			Último Usuario que imprimio <b>{{action_referrals.form.print_user}}</b>
		</div>
	</div>
</form>