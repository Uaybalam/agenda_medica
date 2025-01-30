<?php $pendingContact = isset($_['pendingContact']) ? $_['pendingContact'] : false; ?>
<div class="row form-horizontal">
	<div class="col-lg-12">
		<div class="form-group form-group-sm">
			<label class="col-md-2 control-label">Paciente</label>
			<div class="col-md-10">
				<input readonly="true" type="text" ng-model="default.communicate.patient_full_name" class="form-control"/>
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-md-2 control-label">Teléfono</label>
			<div class="col-md-4">
				<input readonly="true" type="text" ng-model="default.communicate.patient_phone" class="form-control"/>
			</div>
			<label class="col-md-3 control-label">Email</label>
			<div class="col-md-3">
				<input readonly="true" type="text" ng-model="default.communicate.patient_email" class="form-control"/>
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-md-2 control-label">Seguro</label>
			<div class="col-md-4">
				<input readonly="true" type="text" ng-model="default.communicate.patient_insurance" class="form-control"/>
			</div>
			<label class="col-md-3 control-label">Genero</label>
			<div class="col-md-3">
				<input readonly="true" type="text" ng-model="default.communicate.patient_gender" class="form-control"/>
			</div>
		</div>
		<div class="form-group form-group-sm" ng-hide="default.communicate.contact_id===0">
			<label class="col-md-2 control-label">Razón</label>
			<div class="col-md-10" >
				<textarea rows="2" readonly="true" ng-model="default.communicate.reason" class="form-control" ></textarea>
			</div>
			<div class="clearfix"></div>
		</div>

		<div class="form-group form-group-sm" >
			<label class="col-md-2"></label>
			<div class="col-md-10" >
				<div class="btn-group btn-group-sm">
					<label class="btn btn-default btn-sm"  
						ng-class="default.communicate.has_appointment ? 'active' : ''"
						ng-click="default.communicate.has_appointment = (default.communicate.has_appointment) ? false : true;"
						 > Crear cita
					</label>
				</div>
			</div>
		</div>
		<section style="border:1px solid gray;padding:15px;border-radius:4px;margin-bottom:15px;"  
				ng-show="default.communicate.has_appointment" >
			
				<div class="form-group form-group-sm">
					<label class="col-md-3 control-label">Fecha {{default.communicate.date}}</label>
					<div class="col-md-9">
						<div class="input-group input-group-sm pull-right">
					      	<input type="text"  ng-change="action_communicate.change_time()"  ng-model="default.communicate.date" class="form-control create-datepicker" placeholder="month / day / year"  >
					     	<span class="input-group-btn">
					        	<button ng-disabled="default.communicate.date==''" class="btn btn-success" type="button" ng-click="action_communicate.modal_pending()"> Todos con fecha actual</button>
					     	</span>
					    </div>
					</div>
				</div>
				<div class="form-group form-group-sm">
					<label class="col-md-3 control-label">Hora</label>
					<div class="col-md-3">
						<select ng-model="default.communicate.hour" class="form-control">
							<option value="-1" selected="true" disabled="true">Selecciona la hora</option>
							<option ng-repeat="hour in default.time.hours" value="{{hour}}">{{hour}}</option> 
						</select>
						<span class="help-block">Hora</span>
					</div> 
				</div>
				<div class="form-group form-group-sm">
					<label class="col-md-3 control-label">Codigo de visita</label>
					<div class="col-md-3">
						<input type="text" ng-model="default.communicate.code" class="form-control"/>
					</div>
					<label class="col-md-3 control-label">Tipo de visita</label>
					<div class="col-md-3">
						<select ng-model="default.communicate.visit_type" class="form-control">
							<?php foreach ($_['visit_types'] as $key => $type ) : ?>									
								<option value="<?= $key?>"><?= $type ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="form-group form-group-sm">
					<label class="col-md-2 control-label">Notas de cita</label>
					<div class="col-md-10">
						<textarea rows="2"  ng-model="default.communicate.notes_appointment" class="form-control" ></textarea>
					</div>
				</div>
		</section>

		<div class="form-group form-group-sm">
			<label class="col-md-2 control-label">Notas</label>
			<div class="col-md-10">
				<textarea rows="2"  ng-model="default.communicate.notes" class="form-control" ></textarea>
			</div>
		</div>
		<div class="form-group form-group-sm"  ng-hide="default.communicate.contact_id===0 || default.status_contact === 1 ">
			<label class="col-md-2 control-label"></label>
			<div class="col-md-10" >
				<label style="padding:0px;"> 
					<input type="checkbox" ng-model="default.communicate.close_pending" ng-checked="default.communicate.close_pending" >
					<span>Cerrar pendiente </span>
				</label>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<label class="col-md-2 control-label"></label>
			<div class="col-md-10">
				
				<button type="button" ng-click="action_communicate.open_history()" class="btn btn-success submit"> Historial </button>
				<button type="button" ng-click="action_communicate.submit()" class="btn btn-primary submit"> Enviar </button>
				<?php if($pendingContact) : ?>
					<button  href="#" ng-click="action_communicate.withoutAnswer()" class="pull-right btn btn-warning submit">  <i class="fa fa-print"></i> Contacto no respondido </button>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>	
