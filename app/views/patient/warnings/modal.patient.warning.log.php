<div class="row">
	<div class="col-lg-12" style="font-size:12px;">
		<table class="table table-condensed table-hover table-bordered">
				<thead>
					<tr>
						<th class="well">Creado el</th>
						<th class="well">Creado por</th>
						<th class="well">Eliminado por</th>
						<th class="well">Descripción</th>
					</tr>
				</thead>
			<tbody>
				<tr ng-repeat="warning in data.warnings | filter: { status: '1' }" >
					<td class="col-xs-3 col-md-3">{{ ngHelper.formatDate(warning.create_at)}}</td>
					<td class="col-xs-3 col-md-3">{{ warning.user_create }}</td>
					<td class="col-xs-3 col-md-3">{{ warning.user_remove }}</td>
					<td class="col-xs-8 col-md-6">{{ warning.description }}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
