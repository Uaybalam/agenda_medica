<div class="row">
	<div class="form-horizontal">
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label">Medicamentos actuales</label>
			<div class="col-md-9">
				<textarea class="form-control" ng-model="recorded_data.current_medications" placeholder=""></textarea>
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label">Comentarios</label>
			<div class="col-md-9">
				<textarea class="form-control" ng-model="recorded_data.comments" placeholder="¿Has sido diagnosticado con algún otro problema?"></textarea>
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label">Cirugías</label>
			<div class="col-md-9">
				<textarea class="form-control" ng-model="recorded_data.surgeries" placeholder=""></textarea>
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label"></label>
			<div class="col-md-9">
				<button type="button" class="btn btn-primary submit" ng-click="submit()" >Sí, confirmo la información</button>
			</div>
		</div>
	</div>
</div>