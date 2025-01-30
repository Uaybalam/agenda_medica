<div class="row">
	<div class="col-md-5">
		<form class="form form-horizontal" ng-submit="action_check_document.submit($event)">
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label"> Paciente </label>
				<div class="col-md-9">
					<input class="form-control input-sm" readonly="true" type="text" ng-model="default.document.patient" />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label"> Titulo </label>
				<div class="col-md-9">
					<textarea class="form-control input-sm" readonly="true" ng-model="default.document.title"></textarea>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label"> Contacto con paciente </label>
				<div class="col-md-9">
					<label style="margin:0px;margin-left: 5px;vertical-align:middle;width: 100px;" class="switch" >
						<input  type="checkbox" ng-true-value="'1'" ng-false-value="'0'" ng-model="default.document.contact_patient">
						<span class="on">Si</span>
						<span class="off">No</span>
					</label>
				</div>
			</div>
			<div class="form-group form-group-sm" ng-show="default.document.contact_patient==1">
				<label class="col-md-3 control-label"> Razón de contacto </label>
				<div class="col-md-9">
					<textarea class="form-control" ng-model="default.document.reason_contact" placeholder="The Document type is an warning"></textarea>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label"> PIN </label>
				<div class="col-md-9">
					<input class="form-control input-sm input-password" type="text" ng-model="default.document.pin" />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label"></label>
				<div class="col-md-9">
					<button type="submit" class="btn btn-primary" />Completar</button>
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-7">
		<a  style="height:420px;" ng-href="{{ default.document.urlOpenImage}}" target="_blank" class="thumbnail">
				<img   style="height: 410px; width: 100%; display: block;object-fit: scale-down" ng-src="{{ default.document.urlImage }}"  data-holder-rendered="true" /> 
		</a>
	</div>
</div>
