<?php echo form_open('/encounter/physicalexam/save/',[
		'class' => 'form-horizontal',
		'ng-submit' => 'action_physicalexam.submit($event)',
		'id' => 'form-physicalexam',
		'autocomplete' => 'off'
	]); ?>
		<!--   -->
		<div class="form-horizontal">
			<div class="form-group">
				<label class="col-sm-3 control-label">Titulo</label>
				<div class="col-sm-9" >
					<div class="input-group">
			      		<select ng-model="default.physicalexam.title" class="form-control"> 
						    <option  ng-repeat="(key,val) in data.catalog_examinations" value="{{val.title}}">{{val.title}}</option>
						</select>
			      		<span class="input-group-btn">
			        		<button ng-click="action_physicalexam.import_template()" class="btn btn-success" type="button">Agregar plantilla!</button>
			      		</span>
			    	</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Contenido</label>
				<div class="col-sm-9">
					<textarea ng-model="default.physicalexam.content" rows="6" class="form-control"></textarea>
				</div>
			</div>
		</div>
		<div class="row" style="margin-bottom:0px;">
			<div class="col-lg-12 text-right well well-sm" style="margin-bottom:0px;">
	
				<a class="btn btn-info" target="_blank" href="/user/examinations"> Editar el contenido de examines</a>
				<button type="button" ng-click="action_physicalexam.delete(default.physicalexam.idx)" ng-show="default.physicalexam.id" class="btn btn-danger"> Eliminar </button>
				<button type="submit" class="btn btn-primary submit"> Guardar </button>
			</div>
		</div>
	<?php echo form_close(); ?>
	
