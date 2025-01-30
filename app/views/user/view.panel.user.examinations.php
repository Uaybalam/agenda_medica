<div class="panel panel-default">
	<div class="panel-heading">
		<b>Exámenes físicos personales</b>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-4">
				<input type="text" placeholder="Titulo" class="form-control input-sm" ng-model="default.examination.title" />
			</div>
			<div class="col-sm-6">
				<textarea placeholder="Contenido" class="form-control input-sm"  rows="5" ng-model="default.examination.content"></textarea>
			</div>
			<div class="col-sm-2" style="padding-top:5px;">
				<button class="btn btn-primary btn-xs" ng-click="insert(default.examination)"> <i class="fa fa-plus"></i> Agregar </button>
			</div>
		</div>
		<hr>
		<div class="row" ng-repeat="examination in data.examinations" style="padding-top:5px;">
			<div class="col-sm-4" >
				<input type="text" placeholder="Titulo" class="form-control input-sm" ng-model="examination.title"/>
			</div>
			<div class="col-sm-6">
				<textarea placeholder="Contenido" class="form-control input-sm"  rows="5" ng-model="examination.content"></textarea>
			</div>
			<div class="col-sm-2" style="padding-top:5px;">
				<button class="btn btn-success btn-xs" ng-click="update(examination)" ng-disabled="disabled(examination)"> <i class="fa fa-plus"></i> Actualizar </button>
				<button class="btn btn-danger btn-xs"  ng-click="delete(examination)" > <i class="fa fa-trash"></i> Eliminar </button>
			</div>
		</div>
	</div>
	<div class="panel-footer">
		
	</div>
</div>


