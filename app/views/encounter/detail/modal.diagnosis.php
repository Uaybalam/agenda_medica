<?php echo form_open('/diagnosis/save/',[
		'class' => 'form-horizontal',
		'ng-submit' => 'action_diagnosis.submit($event)',
		'autocomplete' => 'off'
	]); ?>
		
		<div class="form-horizontal"> 
			<div class="col-lg-12">
				<div class="form-group">
					<label class="col-sm-3 control-label">Comentarios del diagnostico</label>
					<div class="col-sm-9">
						<textarea  ng-change="action_diagnosis.search_comment(default.diagnosis.comment)"  ng-model="default.diagnosis.comment"  class="form-control" placeholder="Comentarios del diagnostico"  rows="4"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label"></label>
					<div class="col-sm-9">
						<p ng-show="result_diagnosis" ng-repeat="diagnosis in data.catalog_diagnostics">
							{{ diagnosis.comment }}
						</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">¿Es cronico?</label>
					<div class="col-sm-9">	
						<label class="radio">
							<input ng-model="default.diagnosis.chronic" value="0" type="radio"  >
							<span>No</span>
						</label>
						<label class="radio"> 	
							<input ng-model="default.diagnosis.chronic" value="1" type="radio"  >
							<span>Si</span>
						</label>
					</div>
				</div>
			</div>
		</div>
		<div class="row" style="margin-bottom:0px;">
			<div class="col-lg-12 text-right well well-sm" style="margin-bottom:0px;">
				<button type="button" ng-click="action_diagnosis.delete()" ng-show="default.diagnosis.id"  class="btn btn-danger"> Eliminar </button>
				<button type="submit" class="btn btn-primary submit"> Guardar </button>
			</div>
		</div>
	<?php echo form_close(); ?>
	
