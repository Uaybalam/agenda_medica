
<div class="row form-horizontal">
	<div class="col-md-6">
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label">Usuario</label>
			<div class="col-md-9">
				<input class="form-control input-sm" type="text" ng-model="default.user_basic.nick_name" disabled="true"  />
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label">Contraseña</label>
			<div class="col-md-9">
				<div class="input-group input-group-sm">
					<input  class="form-control input-sm" ng-readonly="!default.user_basic.edit_password" type="text" 
						ng-model="default.user_basic.password" ng-style="{ '-webkit-text-security': 'disc' }"  />
					<span class="input-group-btn">
						<button title="Cambiar contraseña" data-toggle="tooltip"
							ng-click="default.user_basic.edit_password = (default.user_basic.edit_password == 0) ? 1 : 0;" 
							class="btn btn-sm"  
							ng-class="default.user_basic.edit_password==1 ? 'btn-primary' : 'btn-default' "
							type="button"> <i class="fa fa-pencil" aria-hidden="true"></i> Editar </button> 
					</span>
				</div>
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label">Email</label>
			<div class="col-md-9">
				<input class="form-control input-sm" type="text" ng-model="default.user_basic.email" />
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label">Nombres</label>
			<div class="col-md-9">
				<input class="form-control input-sm" type="text" ng-model="default.user_basic.names" />
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label">Apellidos</label>
			<div class="col-md-9">
				<input class="form-control input-sm" type="text" ng-model="default.user_basic.last_name" />
			</div>
		</div>
		<div class="form-group form-group-sm">	
			<label class="col-md-3 control-label">Firma digital</label>
			<div class="col-md-9">
				<input type="text" class="form-control input-sm" ng-model="default.user_basic.digital_signature" />
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label">Tipo de acceso</label>
			<div class="col-md-9">
				<select class="form-control input-sm" ng-model="default.user_basic.access_type" >
					<option ng-repeat="(key, value) in data.access_type_avalible" value="{{key}}">{{ value }}</option>
				</select>
			</div>
		</div>

		<div ng-show="default.user_basic.access_type=='medic' || default.user_basic.access_type=='admin' " >
			<div class="form-group form-group-sm" >
				<label class="col-md-3 control-label">Tipo de médico</label>
				<div class="col-md-9">
					<select class="form-control input-sm" ng-model="default.user_basic.medic_type">
						<option value="MD">MD</option>
						<option value="PA">PA</option>
						<option value="NP">NP</option>
					</select>
				</div> 
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label">Fecha de nacimiento</label>
			<div class="col-md-9">
				<input ng-model="default.user_basic.date_of_birth" class="form-control input-sm create-datepicker" />
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label">Genero</label>
			<div class="col-md-9">
				<div class="btn-group">
					<label type="button" class="btn btn-default btn-sm" ng-click="default.user_basic.gender='Male'" ng-class="default.user_basic.gender=='Male' ? 'active' : ''">Masculino</label>
					<label type="button" class="btn btn-default btn-sm" ng-click="default.user_basic.gender='Female'" ng-class="default.user_basic.gender=='Female' ? 'active' : ''">Femenino</label>
				</div>
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label">Estado Civil</label>
			<div class="col-md-9">
				<select ng-model="default.user_basic.marital_status" class="form-control input-sm">
					<option value="Married">Casado/a</option>
					<option value="Widowed">Viudo/a</option>
					<option value="Separated">Separado/a</option>
					<option value="Divorced">Divorciado/a</option>
					<option value="Single">Soltero/a</option>
				</select>
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label">Teléfono</label>
			<div class="col-md-9">
				<input ng-model="default.user_basic.phone" class="form-control input-sm" data-mask="999 999 9999" placeholder="Code & number"/>
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label">Información medica</label>
			<div class="col-md-9">
				<textarea ng-model="default.user_basic.medical_information" class="form-control" rows="2" placeholder="Tipo de alergias o enfermedades crónicas"></textarea>
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label">Estatus del empleado</label>
			<div class="col-md-9">
				<input ng-model="default.user_basic.employment_status" class="form-control" placeholder="Tiempo completo, temporaral, etc">
			</div>
		</div>
		<div class="form-group form-group-sm">
			<label class="col-md-3 control-label">Fecha de empleo</label>
			<div class="col-md-9">
				<input ng-model="default.user_basic.employment_date" class="form-control create-datepicker" >
			</div>
		</div>
		<div class="form-group form-group-sm" ng-hide="data.user.status==2">
			<label class="col-md-3 control-label">Estatus</label>
			<div class="col-md-9">
				<div class="btn-group">
					<label type="button" class="btn btn-default btn-sm" ng-click="default.user_basic.status='1'" ng-class="default.user_basic.status=='1' ? 'active' : ''">Activo</label>
					<label type="button" class="btn btn-default btn-sm" ng-click="default.user_basic.status='0'" ng-class="default.user_basic.status=='0' ? 'active' : ''">Inactivo</label>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row"> 
	<div class="col-sm-12 text-right well well-sm" style="margin:0px;"> 
		<button ng-click="action_basic.update()" type="button" class="btn btn-primary submit"> Actualizar <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> </button>
	</div>
</div>
