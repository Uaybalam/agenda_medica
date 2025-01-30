<div class="panel panel-default custom-widget">
	<div class="panel-heading">
		<div class="row">
			<div class="col-md-6">
				<label for="">Preventions</label>
			</div>
			<div class="col-md-6 text-right">
				<button ng-click="action_preventions.open('#patient-detail-modal-preventions');" class="btn btn-success btn-xs"> Edit </button>
			</div>
		</div>
		
	</div>
	<div class="panel-body" ng-cloak >
		<table class="table table-bordered">
			<tbody>
				<tr>
					<th >Allergies</th>
					<td >
						<span style="margin-right:2px;" ng-repeat="name in data.preventions.arr_allergies" class="label label-danger">{{ name }}</span>
					</td>
				</tr>
				<tr>
					<th >Alcohol</th>
					<td>{{ data.preventions.alcohol }}</td>
				</tr>
				<tr>
					<th >Drugs</th>
					<td>{{ data.preventions.drugs }}</td>
				</tr>
				<tr>
					<th >Tobacco</th>
					<td>{{ data.preventions.tobacco }}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>