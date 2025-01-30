<?php echo form_open('/encounter/referrals/save/',[
		'class' => 'form-horizontal',
		'ng-submit' => 'action_referrals.submit($event)',
		'id' => 'form-referrals',
		'autocomplete' => 'off',
	]); ?>
		<div class="form-horizontal">
			<div class="form-group form-group-sm">
				<label class="col-sm-3 control-label">Especialidad</label>
				<div class="col-sm-9" >
					<input type="text"  class="form-control" ng-model="default.referrals.speciality" >
				</div>	
			</div>
			<div class="form-group form-group-sm">
				<label class="col-sm-3 control-label">Servicios</label>
				<div class="col-sm-9">
					<input type="text" class="form-control"  ng-model="default.referrals.service"  />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-sm-3 control-label">Comentarios/Razones</label>
				<div class="col-sm-9">
					<textarea placeholder="Razón" ng-model="default.referrals.reason" rows="2" class="form-control"></textarea>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-sm-3 control-label">Gravedad</label>
				<div class="col-sm-9">
					<select ng-model="default.referrals.acuity" class="form-control" >
						<option value="Routine">Rutina</option>
						<option value="Urgent">Urgente</option>
					</select>
				</div>
			</div>
			<div class="form-group form-group-sm" ng-show="default.referrals.user_created_nickname">
				<label class="col-sm-3 control-label">Creado por</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" readonly="true" ng-model="default.referrals.user_created_nickname"  />
				</div>
			</div>
			<div class="form-group form-group-sm" ng-show="default.referrals.user_created_nickname">
				<label class="col-sm-3 control-label">Fecha de derivación</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" readonly="true" ng-model="default.referrals.refer_date"  />
				</div>
			</div>
		</div>
		<div class="row" style="margin-bottom:0px;">
			<div class="col-lg-12 text-right well well-sm" style="margin-bottom:0px;">
				<button type="button" ng-click="action_referrals.delete(default.referrals.idx)" ng-show="default.referrals.id" class="btn btn-danger"> Eliminar </button>
				<button type="submit" class="btn btn-primary submit"> Guardar </button>
			</div>
		</div>
	<?php echo form_close(); ?>
	
