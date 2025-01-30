<div class="panel panel-default custom-widget">
	<div class="panel-heading">
		<div class="row">
			<div class="col-sm-6">
				<label for="">Demographics</label>
			</div>
			<div class="col-sm-6 text-right">
				<a data-toggle="tooltip" title="Print Demographics" data-placement="bottom"  class="btn btn-warning btn-xs" target="_blank" ng-href="/patient/pdf/{{data.patient.id}}" > <i class="fa fa-print"></i></a>
				<a class="btn btn-info btn-xs" ng-href="/patient/detail/{{data.patient.id}}"  ><i class="fa fa-user "></i> Patient detail</a>
				<button ng-click="action_vaccines.open()" class="btn btn-success btn-xs" > Vaccines </button>
				<button ng-click="action_warning.open()" class="btn btn-success btn-xs" ><i class="fa fa-exclamation-triangle"></i> Add warning </button>
			</div>
		</div>
	</div>
	<div class="panel-body"  style="height:auto;">
		<div class="col-md-6">
			<table ng-cloak  class="table table-hover-app table-condensend table-bordered">
				<tbody>
					<tr>
						<th class="col-xs-2 col-md-2">Full name</th>
						<td class="col-xs-4 col-md-4">{{ data.patient.name +' '+data.patient.middle_name+' '+data.patient.last_name}}</td>
						<th class="col-xs-2 col-md-2">Insurances</th>
						<td class="col-xs-4 col-md-4">{{ data.patient.insurance_string }}</span></td>
					</tr>
					<tr>
						<th >Allergies</th>
						<td > {{ data.preventions.allergies }}</td>
						<th >Alcohol</th>
						<td > {{ data.preventions.alcohol }} </td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-6">
			<table ng-cloak  class="table table-hover-app table-condensend table-bordered">
				<tbody>
					<tr>
						<th class="col-xs-2 col-md-2">Age</th>
						<td class="col-xs-4 col-md-4">{{ data.patient.age }}</td>
						<th class="col-xs-2 col-md-2">DOB</th>
						<td class="col-xs-4 col-md-4">{{ data.patient.date_of_birth }}</td>
					</tr>	
					<tr>
						<th >Drugs</th>
						<td >{{ data.preventions.drugs }}</td>
						<th >Tobacco</th>
						<td >{{ data.preventions.tobacco }}</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-lg-12">
			<table ng-cloak class="table table-bordered table-condensed">
				<tbody ng-show="data.warnings.length">
					<tr class="">
						<td>
							<label> <i class="fa fa-exclamation-triangle"></i>	 Warnings</label>
							<ul style="margin-bottom:0px;">
								<li style="padding-bottom:2px;" ng-repeat="warning in data.warnings" >
									<button ng-click="action_warning.remove(warning, $index)" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove warning"> <i class="fa fa-trash"></i></button> 
									<span class="text-danger"><b>{{ warning.description }}</b> ({{ warning.lapse_time}}) </span>
								</li>
							</ul>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>