<?php echo form_open('/encounter/laboratory/save/',[
		'class' => 'form-horizontal',
		'ng-submit' => 'action_laboratory.submit($event)',
		'autocomplete' => 'off'
	]); ?>
			
		<div class="row">
			<div class="form-group">
				<label class="col-sm-3 control-label">Name</label>
				<div class="col-sm-9" >
					<input type="text"  class="form-control" ng-model="default.laboratory.name" >
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Comments</label>
				<div class="col-sm-9">
					<textarea  ng-model="default.laboratory.comments" rows="4" class="form-control"></textarea>
				</div>
			</div>
		</div>	
		<div class="row well well" style="margin-bottom:0px;">
			<div class="col-lg-12 text-right" style="margin-bottom:0px;">
				<button type="button" ng-click="action_laboratory.delete(default.laboratory.idx)" ng-show="default.laboratory.id" class="btn btn-danger"> Remove </button>
				<button type="submit" class="btn btn-primary submit" > {{ default.laboratory.id> 0 ? 'Edit' : 'Add new'  }} </button>
			</div>
		</div>
	<?php echo form_close(); ?>
