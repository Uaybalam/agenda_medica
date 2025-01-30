<?php echo form_open('/encounter/results/save/',[
		'class' => 'form-horizontal',
		'ng-submit' => 'action_results.submit($event)',
		'autocomplete' => 'off'
	]); ?>
			
		<div class="form-horizontal">
			<div class="form-group form-group-sm">
				<label class="col-sm-3 control-label">Tipo</label>
				<div class="col-sm-9" >
					<select class="form-control"  ng-model="default.results.type_result" >
						<option value="">Elige un tipo</option>
						<option ng-repeat="avalible in data.encounter_results_availible" value="{{ avalible }}"> {{ avalible}}</option>
					</select>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-sm-3 control-label">Titulo</label>
				<div class="col-sm-9" >
					<input type="text"  class="form-control" ng-model="default.results.title" >
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-sm-3 control-label">Comentarios/Razones</label>
				<div class="col-sm-9">
					<textarea  ng-model="default.results.comments" rows="4" class="form-control"></textarea>
				</div>
			</div>
		</div>	
		<div class="row" style="margin-bottom:0px;">
			<div class="col-lg-12 text-right well well-sm" style="margin-bottom:0px;">
				<button type="button" ng-click="action_results.delete(default.results.idx)" ng-show="default.results.id" class="btn btn-danger"> Eliminar </button>
				<button type="submit" class="btn btn-primary submit" > Guardar </button>
			</div>
		</div>
	<?php echo form_close(); ?>
