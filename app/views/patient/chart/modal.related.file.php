<div class="form-horizontal">
	<div class="form-group">
		<label class="col-md-3 control-label">Titulo</label>
		<div class="col-md-9">
			<input type="text" class="form-control" ng-model="default.patient_related_files.title" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-3 control-label">Tipo</label>
		<div class="col-md-9">
			<select class="form-control" ng-model="default.patient_related_files.type"  >
				<option value="0" selected="true" disabled="true">Selecciona un tipo</option>
				<option ng-repeat="(key, val) in data.catalog_related_file_types">{{val }}</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-3 control-label">Archivo</label>
		<div class="col-md-2">
			<label class="btn btn-danger">
				<i class="fa fa-plus"></i>
				Subir
				<input id="action_file_value" onchange="angular.element(this).scope().action_file.changed(this)" style="display:none;" type="file" />
			</label>
		</div>
		<label class="col-md-2 control-label">Archivo selecionado</label>
		<div class="col-md-5">
			<input type="text" class="form-control" ng-model="default.patient_related_files.name_file" disabled="true" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-3 control-label">Documento para revisar</label>
		<div class="col-md-9">
			<select class="form-control" ng-model="default.patient_related_files.document_for_done"  >
				<option value="0">No</option>
				<option value="1">Si</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-3 control-label">ID de consulta</label>
		<div class="col-md-9">
			<select class="form-control"  ng-model="default.patient_related_files.encounter_id"  >
				<optgroup label="No encounter related">
					<option value="0">Sin consulta</option>
				</optgroup>
				<optgroup label="Select encounter">
					<?php foreach ($_['encounters'] as $key => $enc) : ?>
						<option value="<?= $enc['id']?>"><?= $enc['id']?> &nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp; <?= date('m/d/Y',strtotime($enc['create_at']))?></option>
					<?php endforeach ?>
				</optgroup>
			</select>
			<small class="help-block">Esta relación se utiliza en la facturación.</small>
		</div>
	</div>
</div>
<div class="row" style="margin-bottom:0px;">
	<div class="col-lg-12 text-right well well-sm" style="margin-bottom:0px;">
		<button type="button" ng-click="action_file.submit()" class="btn btn-primary submit"> Enviar </button>
	</div>
</div>