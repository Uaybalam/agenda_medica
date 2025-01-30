
<div class="row" style="font-size: 12px;padding-bottom: 20px;">
	<div class="col-lg-12">
		<table class="table table-hover-app table-condensed table-bordered">
			<thead>
				<tr>
					<th class="col-md-3" style="text-align: center;">Event</th>
					<th class="col-md-9" style="text-align: center;">Description</th>
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat="evt in data.available_events ">
					<th>{{ evt.name }}</th>
					<td>{{ evt.description}}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>