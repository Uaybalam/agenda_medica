<div class="panel panel-default custom-widget" ng-cloak > 
	<div class="panel-heading"> 
		<div class="row"> 
			<div class="col-xs-6 col-md-6"> 
				<label for="">Alertas</label> 
			</div> 
			<div class="col-xs-6 col-md-6 text-right"> 
				<button title="Alerta eliminadas" data-toggle="tooltip" data-placement="bottom" ng-click="action_warning.log()" class="btn btn-success btn-xs"> <i class="fa fa-history" aria-hidden="true"></i> </button> 
				<button title="Agregar alerta" data-toggle="tooltip" data-placement="bottom" ng-click="action_warning.open()"  class="btn btn-success btn-xs"> <i class="fa fa-plus"></i> </button> 
			</div>
		</div>

	</div> 
	<div class="panel-body" style="height:240px;font-size:12px;"> 
		<table class="table table-bordered table-condensed table-hover"> 
			<tr ng-repeat="warning in data.warnings | filter: { status: '0' }" >
				<th style="padding-top:4px;">
					<button ng-click="action_warning.remove(warning, $index)" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Eliminar alerta"> <i class="fa fa-trash"></i></button>
				</th>
				<td class="danger">
					{{ warning.lapse_time }}, {{warning.user_create }} </span><br>
					<b> {{ warning.description }}</b>
				</td>
			</tr>
		</table> 
	</div>
</div>