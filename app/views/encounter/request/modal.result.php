<div class="row"> 
	<div class="col-md-6 form-horizontal">
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label"> PAciente </label>
			<div class="col-md-9">
				<input class="form-control input-sm" readonly="true" type="text" ng-model="default.result.patient" />
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label"> Titulo </label>
			<div class="col-md-9">
				<input class="form-control input-sm" readonly="true" type="text" ng-model="default.result.title" />
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label"> Comentarios </label>
			<div class="col-md-9">
				<textarea class="form-control input-sm" rows="2" readonly="true" type="text" ng-model="default.result.comments" /></textarea>
			</div>
		</div>
		<!-- if Refused-->
		<div class="form-group form-group-sm" ng-show="action_results.originalStatus==6">
			<label class="col-md-3 control-label"> Rechazado por </label>
			<div class="col-md-9">
				<div class="input-group">
				  	<span class="input-group-addon" title="{{ default.result.refused_date }}"><i class="fa fa-clock-o"></i></span>
				 	<input title="User nick name" ng-model="default.result.refused_nickname" readonly="true" type="text" class="form-control"  >
				</div>
			</div>
		</div>
		<div class="form-group form-group-sm" ng-show="action_results.originalStatus==6" >
			<label class="col-md-3 control-label"> Razón </label>
			<div class="col-md-9">
				<textarea  readonly="true"  ng-model="default.result.refused_reason"  class="form-control"  ></textarea>
			</div>
		</div>
		<!-- if Received-->
		<div class="form-group form-group-sm" ng-show="default.result.file_name">
			<label class="col-md-3 control-label"> Recivido por </label>
			<div class="col-md-9">
				<div class="input-group">
				  	
				 	<input title="Usar nombre de usuario" ng-model="default.result.recive_nickname" readonly="true" type="text" class="form-control"  >
				 	<span class="input-group-addon" title="{{ ngHelper.formatDate(default.result.recive_date) }}"><i class="fa fa-clock-o"></i> {{ ngHelper.normalDate(default.result.recive_date) }}</span>
				</div>
			</div>
		</div>
		<!-- if Done-->
		<div class="form-group form-group-sm" ng-show="default.result.done_nickname">
			<label class="col-md-3 control-label"> Completado por </label>
			<div class="col-md-9">
				<div class="input-group">
				  	<span class="input-group-addon" title="{{ default.result.done_date }}"><i class="fa fa-clock-o"></i></span>
				 	<input title="Usar nombre de usuario" ng-model="default.result.done_nickname" readonly="true" type="text" class="form-control"  >
				</div>

			</div>
		</div>
		<!-- if Doc on File-->
		<div class="form-group form-group-sm" ng-show="action_results.originalStatus==8">
			<label class="col-md-3 control-label"> Por </label>
			<div class="col-md-9">
				<div class="input-group">
				  	<span class="input-group-addon" title="{{ default.result.doc_on_file_date }}"><i class="fa fa-clock-o"></i></span>
				 	<input title="Usar nombre de usuario" ng-model="default.result.doc_on_file_nickname" readonly="true" type="text" class="form-control"  >
				</div>
			</div>
		</div>
		<div class="form-group form-group-sm" ng-show="action_results.originalStatus==8" >
			<label class="col-md-3 control-label"> Razón </label>
			<div class="col-md-9">
				<textarea  readonly="true"  ng-model="default.result.doc_on_file_reason"  class="form-control"  ></textarea>
			</div>
		</div>
	</div>
	<div class="col-md-6 form-horizontal">
		<div class="form-group form-group-sm" >
			<div class="col-md-3 control-label"  ng-show="action_results.showUpload()"  >
				<label class="btn btn-warning btn-sm submit" > <i class="fa fa-upload" aria-hidden="true"></i> Subir
					 <input type="file" style="display:none;" onchange="angular.element(this).scope().action_results.upload(this)"  >
				</label>
				<hr>
				<button ng-disabled="default.result.file_name=='' ? true : false" ng-click="action_results.remove()" class="btn btn-danger btn-sm submit" type="button"> <i class="fa fa-trash" aria-hidden="true"></i> Remover </button>
			</div>
			<div class="col-md-9">
				<a  style="height:120px;" ng-href="/encounter/results/open/{{default.result.id}}?random={{action_results.randomID}}" target="_blank" class="thumbnail">
					<img   style="height: 110px; width: 100%; display: block;object-fit: scale-down" ng-src="{{ '/encounter/results/' + default.result.id +'/open-preview/?random='+action_results.randomID}}" alt="{{ default.result.title }}" data-holder-rendered="true" > 
				</a>
			</div>
		</div>
		<div class="form-group form-group-sm" ng-hide="default.result.file_name== '' ? true : false" " >
			<label class="col-md-3 control-label">Nombre de documento</label>
			<div class="col-md-9">
				<input type="text" readonly="true" class="form-control" ng-model="default.result.title_document" >
			</div>
		</div>
	</div>
</div>
<hr>
<div class="row" style="padding-bottom: 16px;" ng-hide="action_results.hideUpdate()">
	<div class="col-md-12 form-horizontal">
		<div class="form-group form-group-sm">
			<label class="col-md-2 control-label"> Estatus </label>
			<div class="col-md-3">
				<select class="form-control" ng-model="default.result.status">
					<option value="2" disabled="true">No afiliado</option>
					<option value="3" disabled="true">Enviar</option>
					<option value="4">Resultados recibidos</option>
					<option value="5">Completado</option>
					<option value="8">Documento en archivo</option>
					<option value="6">Rechazado</option>
				</select>
			</div>
		</div>
		<div class="form-group form-group-sm" ng-show="default.result.status==8">
			<label class="col-md-3 control-label"> Razón </label>
			<div class="col-md-9">
				<input type="text" ng-model="default.result.doc_on_file_reason" class="form-control" />
			</div>
		</div>
		<div class="form-group form-group-sm" ng-show="default.result.status==5 || default.result.status==4" >
			<label class="col-md-3 control-label"> Titulo de documento</label>
			<div class="col-md-9">
				<input type="text" ng-model="default.result.title_document" placeholder=""  class="form-control" />
			</div>
		</div>
		<div class="form-group form-group-sm" ng-show="default.result.status==6">
			<label class="col-md-3 control-label"> Razón de rechazo </label>
			<div class="col-md-9">
				<input type="text" ng-model="default.result.refused_reason" placeholder="" class="form-control" />
			</div>
		</div>
		<div class="form-group form-group-sm" ng-show="default.result.status==5">
			<label class="col-md-3 control-label"> Contacto del paciente </label>
			<div class="col-md-9">
				<label style="margin:0px;margin-left: 5px;vertical-align:middle;width: 100px;" class="switch" >
					<input  type="checkbox" ng-true-value="'1'" ng-false-value="'0'" ng-model="default.result.contact_patient">
					<span class="on">Si</span>
					<span class="off">No</span>
				</label>
			</div>
		</div>
		<div class="form-group form-group-sm" ng-show="default.result.contact_patient==1">
			<label class="col-md-3 control-label"> Razón de contacto </label>
			<div class="col-md-9">
				<textarea class="form-control" ng-model="default.result.reason_contact" placeholder="The Document type is an warning"></textarea>
			</div>
		</div>
		<div class="form-group form-group-sm" ng-show="default.result.status==5 || default.result.status==6 || default.result.status==8 ">
			<label class="col-md-3 control-label"> Pin de Confirmación </label>
			<div class="col-md-9">
				<input type="text" ng-model="default.result.pin" placeholder="" class="form-control input-password" />
			</div>
		</div> 
		<button class="btn btn-primary btn-sm pull-right" ng-click="action_results.refreshStatus()" > Actualizar </button> 
	</div>
</div>

