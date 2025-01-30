<div class="row">
	<div class="col-md-8">
		<div class="form-horizontal">
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Titulo</label>
				<div class="col-sm-9" >
					<input type="text" class="form-control" readonly="true" ng-model="default.file.title" >
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">Paciente</label>
				<div class="col-sm-9" >
					<input type="text" class="form-control" readonly="true" ng-model="default.file.patient" >
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Cargado por</label>
				<div class="col-sm-9" >
					<input type="text" class="form-control" readonly="true" ng-model="default.file.created_by" >
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Cargado el</label>
				<div class="col-sm-9" >
					<input type="text" class="form-control" readonly="true" ng-model="default.file.create_at" >
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<a  style="height:320px;" ng-href="/patient/related-files/open/{{default.file.id}}?random={{randomID}}" target="_blank" class="thumbnail">
			<img   style="height: 310px; width: 100%; display: block;object-fit: scale-down" 
			ng-src="/patient/related-files/open/{{default.file.id}}/preview/?random={{randomID}}"  data-holder-rendered="true" /> 
		</a>
	</div>
</div>