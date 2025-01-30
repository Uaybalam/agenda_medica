<div class="panel panel-default custom-widget">
	<div class="panel-heading">
		<label >Consultas</label>
	</div>
	<div class="panel-body "  >
		<table class="table table-bordered table-condensed" ng-hide="data.encounters.length === 0"  >
			<thead>
				<tr>
					<th>Fecha de firma </th>
					<th> Motivo de consulta </th>
					<th>  </th>
				</tr>
			</thead>
			<tbody>
				<tr ng-cloak ng-repeat="encounter in data.encounters | orderBy:'-id'">
					<td>{{encounter.create_at }}</td>
					<td>{{encounter.chief_complaint }} </td>
					<td> 
						<a 	ng-href="/encounter/request/{{encounter.id}}" 
							class="btn btn-info btn-xs" 
							data-placement="bottom"
							data-toggle="tooltip"
							title="Details">&nbsp;<i class="fa fa-info"></i>&nbsp;Detalle de solicitud</a> 
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="panel-footer" style="min-height:45px;">
		
	</div>
</div>