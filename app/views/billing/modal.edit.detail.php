<div class="row form-horizontal" style="padding: 0px 20px;">
	<div class="form-group form-group-sm">
		<label class="col-md-2 control-label">
			Comments
		</label>
		<div class="col-md-10">
			<textarea class="form-control" ng-model="response.billing.comments" rows=1></textarea>
		</div>
	</div>
	<div class="form-group form-group-sm"> 
		<label class="col-md-2 control-label"> Status </label> 
		<div class="col-md-5"> 
			<select class="form-control form-control-sm" ng-model="response.billing.status"> 
				<option value="2">Sent</option> 
				<option value="3">Partial payment</option>
				<option value="4">Re-Billed</option>
				<option value="5">Paid</option>
			</select> 
		</div>
		<div class="col-md-5"> 
			<input ng-show="response.billing.status==5" ng-model="response.billing.pin" type="text" class="form-control input-xs input-password" placeholder="Secret PIN" />
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<table class="table table-bordered table-condensed" style="font-size:12px;">
			<thead>
				<tr class="well well-sm">
					<th class="text-center" > 
						<span>Number</span>
					</th>
					<th>Print</th>
					<th>CPT/HCPCS</th>
					<th>Charges</th>
					<th>Paid</th>
					<th>Write-OFF</th>
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat="det in response.billing.detail">
						<td class="text-center">
							<label  style="margin-top:4px;">{{ det.number }}</label>
						</td>
						<td>
							<label class="switch" style="margin:0px;">
								<input type="checkbox"  ng-true-value="'1'" ng-false-value="'0'" ng-model="det.active" > 
								<span class="on">Yes</span>
								<span class="off">No</span>
							</label>
						</td>
						<td> <input  ng-model="det.procedure_cpt_hcpcs" type="text" class="form-control input-xs"> </td>
						<td> <input  ng-readonly="true" ng-model="det.charges" class="form-control input-xs"> </td>
						<td> <input  string-to-number ng-change="onChangePaid(det)" ng-model="det.paid" type="number" class="form-control input-xs"> </td>
						<td> <input  string-to-number ng-change="onChangeWriteOff(det)" ng-model="det.write_off" type="number" class="form-control input-xs"> </td>
					</tr>
			</tbody>
			<tfoot>
				<tr class="well well-sm">
					<th></th>
					<th></th>
					<th  class="text-right">
						<label style="margin-top: 5px;">Totals:</label>
					</th>
					<th><input ng-model="response.billing.total_charge" type="text" readonly="true" class="form-control input-xs" /></th>
					<th><input ng-model="response.billing.total_paid" type="text" readonly="true" class="form-control input-xs" /></th>
					<th><input ng-model="response.billing.total_write_off" type="text" readonly="true" class="form-control input-xs" /></th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

<div class="row form-horizontal" style="padding: 0px 20px;">
	<div class="form-group form-group-sm">
		<label class="col-md-4 control-label">
			Balance due
		</label>
		<div class="col-md-8">
			<input ng-model="response.billing.total_due" type="text" readonly="true" class="form-control input-xs" />
		</div>
	</div>
</div>

<div class="row" style="margin-bottom: 20px;">
	<div class="col-lg-12 text-right">
		<button class="btn btn-primary submit" ng-click="updateStatus()">Save</button>
	</div>
</div>