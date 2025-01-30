<div class="row" style="padding:20px 0px;">
	<div class="col-lg-12 custom-widget">
		<table class="table table-condensend table-bordered" >
			<thead>
				<tr>
					<th>Fecha</th>
					<th>Usuario</th>
					<th>Actividad</th>
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat="act in activity">
					<td>{{ act.date }} <span class="text-opacity"> ({{ act.time }})</span></td>
					<td>{{ act.nick_name }} <span class="text-opacity"> ({{ act.user }})</span></td>
					<td>{{ activityTranslate[act.comments] }}</td>
				</tr>
			</tbody>
		</table>	
	</div>
</div>