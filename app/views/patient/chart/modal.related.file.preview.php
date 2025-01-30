<div class="row">
	<div class="col-md-5">
		<div class="form form-horizontal" >
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label"> Paciente </label>
				<div class="col-md-9">
					<input class="form-control input-sm" readonly="true" type="text" ng-model="default.document.patient" />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label"> Titulo </label>
				<div class="col-md-9">
					<textarea readonly="true" class="form-control input-sm" ng-model="default.document.title"></textarea>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label"> Tipo </label>
				<div class="col-md-9">
					<input class="form-control input-sm" readonly="true" type="text" ng-model="default.document.type" />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label"> Fecha de almacenamiento</label>
				<div class="col-md-9">
					<input class="form-control input-sm" readonly="true" type="text" ng-model="default.document.create_at" />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label"> Almacenado por </label>
				<div class="col-md-9">
					<input class="form-control input-sm" readonly="true" type="text" ng-model="default.document.user_created" />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label"> ID de consulta</label>
				<div class="col-md-9">
					<input class="form-control input-sm" readonly="true" type="text" ng-model="default.document.encounter_id" />
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-7" ng-show="default != undefined"> 
		<a style="height:420px;" ng-href="{{ default.document.urlOpenImage}}" target="_blank" class="thumbnail"> 
			<img ng-show="default.document.file_name == 'image'" nfstyle="height: 410px; width: 100%; display: block;object-fit: scale-down" src="{{ default.document.urlImage }}" />
			<iframe ng-show="default.document.file_name == 'application'" style="height: 410px; width: 100%; display: block;object-fit: scale-down" src="{{ default.document.urlImage }}" frameborder="0"></iframe>
		</a>
	</div>
</div>
