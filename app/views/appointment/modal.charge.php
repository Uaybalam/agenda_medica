<?php echo form_open('/appointment/charge-update',[
		'class' => 'form-horizontal',
		'ng-submit' => 'action_charge.submit($event)',
		'autocomplete' => 'off'
	]); ?>
	<div class="row">
		<div class="col-md-6" >
			<div class="form-group">
				<label class="col-md-5 control-label">Office visit</label>
				<div class="col-md-7">
					<input class="form-control" ng-model="default.charge.office_visit" ng-change="action_charge.onChangeSubtotal()"  type="number" step="0.01" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-5 control-label">Laboratories</label>
				<div class="col-md-7">
					<input class="form-control" ng-model="default.charge.laboratories" ng-change="action_charge.onChangeSubtotal()" type="number" step="0.01" />
				</div>
			</div>
			<div class="form-group">	
				<label class="col-md-5 control-label">Injections / Vaccines </label>
				<div class="col-md-7">
					<input class="form-control" ng-model="default.charge.injections" ng-change="action_charge.onChangeSubtotal()" type="number" step="0.01" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-5 control-label">Medications</label>
				<div class="col-md-7">
					<input class="form-control" ng-model="default.charge.medications" ng-change="action_charge.onChangeSubtotal()" type="number" step="0.01" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-5 control-label">Procedures</label>
				<div class="col-md-7">
					<input class="form-control" ng-model="default.charge.procedures" ng-change="action_charge.onChangeSubtotal()" type="number" step="0.01" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-5 control-label">INS Physical</label>
				<div class="col-md-7">
					<input class="form-control" ng-model="default.charge.physical" ng-change="action_charge.onChangeSubtotal()" type="number" step="0.01" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-5 control-label">ECG</label>
				<div class="col-md-7">
					<input class="form-control" ng-model="default.charge.ecg" ng-change="action_charge.onChangeSubtotal()" type="number" step="0.01" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-5 control-label">Ultrasound</label>
				<div class="col-md-7">
					<input class="form-control" ng-model="default.charge.ultrasound" ng-change="action_charge.onChangeSubtotal()" type="number" step="0.01" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-5 control-label">Co-Pay</label>
				<div class="col-md-7">
					<input class="form-control" ng-model="default.charge.x_ray" ng-change="action_charge.onChangeSubtotal()" type="number" step="0.01" />
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label class="col-md-5 control-label">Sub total</label>
				<div class="col-md-7">
					<input class="form-control" type="text"  ng-model="default.charge.subtotal" readonly="true"  />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-5 control-label">Open balance</label>
				<div class="col-md-7">
					<input class="form-control" ng-model="default.charge.open_balance" type="number" readonly="true" step="0.01" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-5 control-label">Discount type</label>
				<div class="col-md-7">	
					<input class="form-control" type="text" ng-model="default.charge.discount_type" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-5 control-label">Discount</label>
				<div class="col-md-7">
					<input class="form-control" ng-model="default.charge.discount"  ng-change="action_charge.onChangeTotal()" type="number" step="0.01" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-5 control-label">Total</label>
				<div class="col-md-7">
					<input class="form-control" ng-model="default.charge.total" type="text" readonly="true"  />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-5 control-label">Paid</label>
				<div class="col-md-7">
					<input class="form-control" ng-model="default.charge.paid" ng-change="action_charge.onChangePaid()" type="number" step="0.01"  />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-5 control-label">Payment type</label>
				<div class="col-md-7">
					<select class="form-control" ng-model="default.charge.payment_type">
						<option ng-repeat="(key, value) in action_charge.payment_types()" ng-value="{{key}}" value="{{ key }}">{{value}}</option>
						
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-5 control-label">Balance due</label>
				<div class="col-md-7">
					<input class="form-control" ng-model="default.charge.balance_due" type="number" step="0.01"  readonly="true"  />
				</div>
			</div>
		</div>
	</div>
	<div class="row well well" style="margin-bottom:0px;margin-top:20px;">
		<div class="col-lg-12 text-right" style="margin-bottom:0px;">
			<button type="submit" class="btn btn-primary submit" > Actualizar </button>
			<button type="submit" class="btn btn-primary submit" > Completado </button>
		</div>
	</div>
<?php echo form_close(); ?>
