
<div class="custom-widget" style="padding-bottom: 20px;">
	<table class="table table-bordered table-condensed table-hover">
		<thead>
			<tr>
				<th class="well">ID Encounter</th>
				<th class="well">Method of payment</th>
				<th class="well">Total</th>
			</tr>
		</theader>
		<tbody>
			<tr ng-repeat="enc in encounters_of_day">
				<td > <a 
					target="_blank" 
					class="btn btn-xs btn-info" 
					ng-href="{{enc.method==='INSURANCE' ? '/billing/detail/'+ enc.encounter_id : '/encounter/detail/'+ enc.encounter_id}}"  
					data-toggle="tooltip" 
					data-placement="left" 
					ng-attr-title="{{ enc.method==='INSURANCE' ? 'Detail of billing' : 'Detail of requests'}}"
					>{{ enc.encounter_id }}</a></td>
				<td >{{ enc.method }}</td>
				<td > <span ng-class="enc.total>0 ? 'text-success' : 'text-danger'">{{ enc.total }}</span> </td>
			</tr>
		</tbody>
	</table>
</div>