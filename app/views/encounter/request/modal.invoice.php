<?php echo form_open('/encounter/request/invoice-update',[
		'class' => 'form-horizontal',
		'ng-submit' => 'action_invoice.submit($event)',
		'autocomplete' => 'off'
	]); ?>
	<div class="row">
		<div class="col-md-6" >
			<div class="form-group form-group-sm">
				<label class="col-md-5 control-label">Visita a consultorio</label>
				<div class="col-md-7">
					<input class="form-control input-sm" ng-model="default.invoice.office_visit" ng-change="action_invoice.onChangeSubtotal()"  type="number" step="0.01" />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-5 control-label">Laboratorio</label>
				<div class="col-md-7">
					<input class="form-control input-sm" ng-model="default.invoice.laboratories" ng-change="action_invoice.onChangeSubtotal()" type="number" step="0.01" />
				</div>
			</div>
			<div class="form-group form-group-sm">	
				<label class="col-md-5 control-label">Inyección / Vacunas </label>
				<div class="col-md-7">
					<input class="form-control input-sm" ng-model="default.invoice.injections" ng-change="action_invoice.onChangeSubtotal()" type="number" step="0.01" />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-5 control-label">Medicamentos</label>
				<div class="col-md-7">
					<input class="form-control input-sm" ng-model="default.invoice.medications" ng-change="action_invoice.onChangeSubtotal()" type="number" step="0.01" />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-5 control-label">Procedimientos</label>
				<div class="col-md-7">
					<input class="form-control input-sm" ng-model="default.invoice.procedures" ng-change="action_invoice.onChangeSubtotal()" type="number" step="0.01" />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-5 control-label">INS Physical</label>
				<div class="col-md-7">
					<input class="form-control input-sm" ng-model="default.invoice.physical" ng-change="action_invoice.onChangeSubtotal()" type="number" step="0.01" />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-5 control-label">ECG</label>
				<div class="col-md-7">
					<input class="form-control input-sm" ng-model="default.invoice.ecg" ng-change="action_invoice.onChangeSubtotal()" type="number" step="0.01" />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-5 control-label">Ultrasonido</label>
				<div class="col-md-7">
					<input class="form-control input-sm" ng-model="default.invoice.ultrasound" ng-change="action_invoice.onChangeSubtotal()" type="number" step="0.01" />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-5 control-label">Rayos X</label>
				<div class="col-md-7">
					<input class="form-control input-sm" ng-model="default.invoice.x_ray" ng-change="action_invoice.onChangeSubtotal()" type="number" step="0.01" />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-5 control-label">Impresiones</label>
				<div class="col-md-7">
					<input class="form-control input-sm" ng-model="default.invoice.print_cost" ng-change="action_invoice.onChangeSubtotal()" type="number" step="0.01" />
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group form-group-sm">
				<label class="col-md-5 control-label">Sub total</label>
				<div class="col-md-7">
					<input class="form-control input-sm" type="text"  ng-model="default.invoice.subtotal" readonly="true"  />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-5 control-label">Saldo pendiente</label>
				<div class="col-md-7">
					<input class="form-control input-sm" ng-model="default.invoice.open_balance" type="number" readonly="true" step="0.01" />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-5 control-label">Tipo de descuento</label>
				<div class="col-md-7">	
					<input class="form-control input-sm" type="text" ng-model="default.invoice.discount_type" />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-5 control-label">Descuento</label>
				<div class="col-md-7">
					<input class="form-control input-sm" ng-model="default.invoice.discount"  ng-change="action_invoice.onChangeTotal()" type="number" step="0.01" />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-5 control-label">Total</label>
				<div class="col-md-7">
					<input class="form-control input-sm" ng-model="default.invoice.total" type="text" readonly="true"  />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-5 control-label">Pago</label>
				<div class="col-md-7">
					<input class="form-control input-sm" ng-model="default.invoice.paid" ng-change="action_invoice.onChangePaid()" type="number" step="0.01"  />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-5 control-label">Tipo de pago</label>
				<div class="col-md-7">
					<select class="form-control input-sm" ng-model="default.invoice.payment_type">
						<option ng-repeat="(key, value) in data.status.payment_types"  value="{{ value }}">{{value}}</option>
						
					</select>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-5 control-label">Total a pagar</label>
				<div class="col-md-7">
					<input class="form-control input-sm" ng-model="default.invoice.balance_due" type="number" step="0.01"  readonly="true"  />
				</div>
			</div>
		</div>
	</div>
	<div class="row" style="margin-bottom:0px;margin-top:20px;">
		<div class="col-lg-12 text-right well well-sm" style="margin-bottom:0px;">
			<button type="submit" class="btn btn-primary submit" > Actualizar </button>
		</div>
	</div>
<?php echo form_close(); ?>
