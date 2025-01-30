<form >
	<div class="row form-horizontal">
		
		<!-- UPDATE DATE-->
		<div class="col-lg-12" ng-show="default.appointment.type_update=='appointment_date'">
			<div class="form-group form-group-sm" ng-show="default.appointment.type_update=='appointment_date'">
				<label class="col-md-3 control-label"> Fecha </label>
				<div class="col-md-9">
					<input type="text" readonly="true" class="form-control input-sm create-datepicker" ng-model="default.appointment.date" />
				</div>
			</div>
			<div class="form-group form-group-sm" ng-show="default.appointment.type_update=='appointment_date'">
				<label class="col-md-3 control-label"> Hora </label>
				<div class="col-sm-4">
					<select ng-model="default.appointment.hour" class="form-control input-sm">
						<option value=""  disabled="disabled" selected="selected">Hora</option>
						<option ng-repeat="hour in default.time.hours" value="{{hour}}">{{hour}}</option> 
					</select>
				</div>
			</div>
		</div>

		<!-- UPDATE VISIT TYPE-->
		<div class="col-lg-12" ng-show="default.appointment.type_update=='visit_type'">
			<div class="form-group form-group-sm" >
				<label class="col-sm-3 control-label">Tipo de visita</label>
				<div class="col-sm-9">
					<div class="btn-group btn-group-sm">
						<label class="btn btn-default btn-sm"  
							ng-repeat="(key, value) in data.arr_visits "
							ng-class="default.appointment.visit_type==value ? 'active' : ''"
							ng-click="default.appointment.visit_type=value"
							 > {{ value}}
						</label>
					</div>
				</div>
			</div>
		</div>

		<!-- UPDATE NOTES-->
		<div class="col-lg-12" ng-show="default.appointment.type_update=='notes'">
			<div class="form-group form-group-sm"  >
				<label class="col-md-3 control-label"> Notas </label>
				<div class="col-md-9">
					<textarea  class="form-control input-sm" ng-model="default.appointment.notes" rows="3"></textarea>
				</div>
			</div>
		</div>

		<!-- UPDATE CODE-->
		<div class="col-lg-12" ng-show="default.appointment.type_update=='code'">
			<div class="form-group form-group-sm"  >
				<label class="col-md-3 control-label"> Codigo </label>
				<div class="col-md-9">
					<input type="text" class="form-control input-sm" ng-model="default.appointment.code" />
				</div>
			</div>
		</div>

		<!-- UPDATE INSURANCE TYPES-->
		<div class="col-lg-12" ng-show="default.appointment.type_update=='insurance_type'">
			<div class="form-group form-group-sm"  >
				<label class="col-md-3 control-label"> Tipo de seguro </label>
				<div class="col-md-9">
					<input type="text" class="form-control input-sm" ng-model="default.appointment.insurance_type" />
				</div>
			</div>
		</div>

		<!-- UPDATE STATUS TO CANCEL-->
		<div class="col-lg-12" ng-show="default.appointment.type_update=='cancel'">
			<div class="form-group form-group-sm"  >
				<label class="col-md-3 control-label"> Razón de cancelación </label>
				<div class="col-md-9">
					<input type="text" class="form-control input-sm" ng-model="default.appointment.reason_cancel" placeholder="" />
				</div>
			</div>
		</div>

	</div>
	<div class="row">
		<div class="col-sm-12 text-right well well-sm" style="margin:0px;"> 
			<button type="submit" 
				ng-disabled="action_appointment.checkDisabled()" 
				ng-click="action_appointment.update()" 
				class="btn btn-primary submit"> Guardar </button>
		</div>
	</div>
</form>