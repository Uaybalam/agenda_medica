<div class="panel panel-default custom-widget">
	<div class="panel-heading"  >
		<div class="row">
			<div class="col-xs-6 col-sm-6"> 
				<label style="font-size:12px;">Comunicaciones  <span ng-cloak class="badge" data-placement="right" data-toggle="tooltip" title="Total communications">{{ (data.communications | filter: action_communicate.range_filter ).length }}</span></label>
			</div>
			<div class="col-xs-6 col-sm-6 text-right">
				<a target="_blank" ng-href="/patient/communication/pdf/{{ data.patient.id }}?{{action_communicate.data_filter()}}" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="Imprimir Comunicaciones"> <i class="fa fa-print"></i> </a>
				<button title="Crear solicitud de contacto" data-toggle="tooltip" data-placement="bottom" ng-click="action_contact.open()" class="btn btn-xs btn-success submit" > <i class="fa fa-comments-o"></i> </button>
				<button title="Agragar notas" data-toggle="tooltip" data-placement="bottom"  ng-click="action_communicate.open()" class="btn btn-xs btn-success submit" > <i class="fa fa-plus"></i> </button>
			</div>
		</div>
	</div>
	<div class="panel-body"  style="height:200px;font-size:12px;padding: 5px;overflow-y:auto;" >
		<table g-cloak style="margin:0px;"  class="table table-hover-app table-condensend table-bordered"  >
			<thead>
				<tr class="well">
					<td class="col-md-10"><b>Comentarios</b></td>
				</tr>
			</thead>
			<tbody>
				<tr dir-paginate="contact in data.communications | filter: action_communicate.range_filter | itemsPerPage:5 | orderBy:sortKey:reverse " pagination-id="dirpagination_comunications" >
					<td >
						<div style="float: right;">
							<label ng-class="data.typesOfCommunications[contact.type].class"> <i class="fa" ng-class="data.typesOfCommunications[contact.type].subclass"></i> </label>
						</div>
						<i  class="fa fa-clock-o"
							data-toggle="tooltip"
							data-original-title="{{ ngHelper.formatDate(contact.create_at) }}"></i>  <b>{{ngHelper.humanDate(contact.create_at) }}</b> 
						{{ contact.notes }}
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="panel-footer" style="padding-top:4px; padding-bottom:0px;height:42px;">
		<div class="row">
			<div class="col-md-6">
				<div class="input-group" >
				    <input placeholder="De" ng-model="default.filter.communicate_date_from" readonly="true" type="text" class="create-datepicker input-xs form-control">
				    <span class="input-group-addon" style="padding:0px;"></span>
				    <input placeholder="A" ng-model="default.filter.communicate_date_to" readonly="true" type="text" class="create-datepicker input-xs form-control">
				</div>
			</div>
			<div class="col-md-6 text-right dirpagination-xs" >
				<dir-pagination-controls
					on-page-change="dirPagCommunication(newPageNumber)"
				    max-size="5"
				    direction-links="true"
				    boundary-links="false" 
				    auto-hide="false" 
				    pagination-id="dirpagination_comunications" >
				</dir-pagination-controls>
			</div>
		</div>
	</div>
</div>
