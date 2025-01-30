<?php echo form_open('#', [
			'class' => 'form-horizontal',
			'ng-submit' => 'action_user.submit($event)',
			'autocomplete' => 'off'
		]); ?>
		
	<div class="row">
	
		<div class="col-md-6">
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Usuario</label>
				<div class="col-md-9">
					<input class="form-control input-sm" type="text" ng-model="default.user.nick_name"  />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Contraseña</label>
				<div class="col-md-9">
					<div class="input-group input-group-sm">
						<input ng- class="form-control input-sm"  type="text" ng-model="default.user.password" ng-style="default.user.hide_password ? { '-webkit-text-security': 'disc' } : {} "  />
						<span class="input-group-btn">
							<button title="Show/Hide password" data-toggle="tooltip" 
								ng-click="default.user.hide_password = (default.user.hide_password ) ? false : true;" 
								class="btn btn-sm"  
								ng-class="default.user.hide_password ? 'btn-default' : 'btn-primary' "
								type="button"> <i class="fa fa-eye-slash" aria-hidden="true"></i> </button> 
						</span>
					</div>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Email</label>
				<div class="col-md-9">
					<input class="form-control input-sm" type="text" ng-model="default.user.email" />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Nombres</label>
				<div class="col-md-9">
					<input class="form-control input-sm" type="text" ng-model="default.user.names" />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Apellidos</label>
				<div class="col-md-9">
					<input class="form-control input-sm" type="text" ng-model="default.user.last_name" />
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Tipo de acceso</label>
				<div class="col-md-9">
					<select class="form-control input-sm" ng-model="default.user.access_type">
					<?php if(validate_access_type('root') ){ ?>
						<option value="root" >Root</option>
					<?php } ?>
						<option ng-repeat="(key, value) in data.access_type_avalible" value="{{key}}" >{{ value }}</option>
					</select>
					<!--
					<select class="form-control input-sm" ng-model="default.user.access_type" ng-options="value for value in data.access_type_avalible"></select>
					-->
				</div>
			</div>
			<div ng-show="default.user.access_type=='medic'" >
				<div class="form-group form-group-sm" >
					<label class="col-md-3 control-label">Tipo de médico</label>
					<div class="col-md-9">
						<select class="form-control input-sm" ng-model="default.user.medic_type">
							<option value="MD">MD</option>
							<option value="PA">PA</option>
							<option value="NP">NP</option>
						</select>
					</div>
				<!--	<label class="col-md-3 control-label">Medic npi</label>
					<div class="col-md-3">
						<input type="text" class="form-control input-sm" ng-model="default.user.medic_npi" />
					</div> -->
				</div>
				<div class="form-group form-group-sm">
					
					<label class="col-md-3 control-label">Firma</label>
					<div class="col-md-9">
						<input type="text" class="form-control input-sm" ng-model="default.user.digital_signature" />
					</div>
				</div>
			</div>
			
		</div>

		<div class="col-md-6">
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Fecha de nacimiento</label>
				<div class="col-md-9">
					<input ng-model="default.user.date_of_birth" class="form-control input-sm create-datepicker" placeholder="Mes/Día/Año"/>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Genero</label>
				<div class="col-md-9">
					<div class="btn-group">
						<label type="button" class="btn btn-default btn-sm" ng-click="default.user.gender='Male'" ng-class="default.user.gender=='Male' ? 'active' : ''">Masculino</label>
						<label type="button" class="btn btn-default btn-sm" ng-click="default.user.gender='Female'" ng-class="default.user.gender=='Female' ? 'active' : ''">Femenino</label>
					</div>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Estado civil</label>
				<div class="col-md-9">
					<select ng-model="default.user.marital_status" class="form-control input-sm">
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
					<input ng-model="default.user.phone" class="form-control input-sm" data-mask="999 999 9999" placeholder="Lada y numero"/>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Información medica</label>
				<div class="col-md-9">
					<textarea ng-model="default.user.medical_information" class="form-control" rows="2" placeholder="Tipo de alergias o enfermedades crónicas"></textarea>
				</div>
			</div>
			<?php if(validate_access_type('root') ){ ?>
				<div class="form-group form-group-sm" ng-show="default.user.access_type == 'admin'">
					<label class="col-md-3 control-label">Es nueva instancia?</label>
					<div class="col-md-9">
						<input type="checkbox" ng-model="default.user.new_instance" />
					</div>
				</div>
			<?php } ?>
		</div>

	</div>
	<div class="row"> 
		<div class="col-sm-12 text-right well well-sm" style="margin:0px;"> 
			<button type="submit" class="btn btn-primary submit"> Guardar <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> </button>
		</div>
	</div>

</form>