<?php echo form_open('/encounter/medication/save/',[
		'class' => 'form-horizontal',
		'ng-submit' => 'action_medication.submit($event)',
		'id' => 'form-medication',
		'autocomplete' => 'off'
	]); ?>
		
		<div class="form-horizontal">
			<div class="form-group form-group-sm">
				<label class="col-sm-3 control-label">Medicación</label>
				<div class="col-sm-9" >
					<input type="text" placeholder="Nombre de medicación" class="form-control" ng-model="default.medication.title" >
				</div>
			</div>
			
			<div class="form-group form-group-sm">
				<label class="col-sm-3 control-label">Cantidad</label>
				<div class="col-sm-9">
					<input type="text" ng-model="default.medication.amount" class="form-control"  />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-sm-3 control-label">Renovación</label>
				<div class="col-sm-3">
					<input type="number"  class="form-control" placeholder="" ng-model="default.medication.refill" >
				</div>
				<label class="col-sm-3 control-label">Crónico</label>
				<div class="col-sm-3">
					<select class="form-control" ng-model="default.medication.chronic">
						<option value="No">No</option>
						<option value="Yes">Si</option>
					</select>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-sm-3 control-label">Indicaciones</label>
				<div class="col-sm-9">
					<textarea  ng-model="default.medication.directions" rows="2" class="form-control"></textarea>
				</div>
			</div>
		</div>	
		<div class="row" style="margin-bottom:0px;">
			<div class="col-lg-12 text-right well well-sm" style="margin-bottom:0px;">
				<button type="button" ng-click="action_medication.delete(default.medication.idx)" ng-show="default.medication.id" class="btn btn-danger"> Eliminar </button>
				<button type="submit" class="btn btn-primary submit" > Guardar </button>
			</div>
		</div>
	<?php echo form_close(); ?>
