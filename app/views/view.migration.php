<?php foreach ($_['errors'] as $key => $value) {
	echo "<label class='text-danger'>{$value}</label>";
}?>
<div clas="row">
	<div class="col-md-6">
		<div class="panel panel-warning">
			<div class="panel-heading">
				Migration data
				
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-12">
						<label for="autorun">
							<input id="autorun" type="checkbox">
							Autorun
						</label>
						<hr>
						<select class="form-control" id="clinic">
							<option value="" disabled selected="true">Choose clinic</option>
							<option value="ontario">ontario</option>
							<option value="escondido">escondido</option>
						</select>
						<input class="form-control" readonly="true" type="text" id="key_code" value="<?= $_['key_code']?>" />
						<hr>
						<div class="btn-group-vertical">
							<button data-next="migration-users" data-url="/migration/truncate/run" data-completed="Data clean" class="btn btn-danger run-migration btn-sm" >Truncate data</button>
							<button data-next="migration-patients" id="migration-users" data-url="/migration/user/run" data-completed="Users completed" disabled="disabled"  class="btn btn-danger run-migration btn-sm" >Users</button>
							<button data-next="migration-allergies" id="migration-patients" data-url="/migration/patient/run" data-completed="Patients completed" disabled="disabled" class="btn btn-danger run-migration btn-sm">Patients</button>
							<button data-next="migration-communications" id="migration-allergies" data-url="/migration/allergies/run" data-completed="Allergies updated" disabled="disabled" class="btn btn-danger run-migration btn-sm" >Allergies</button>
							<button data-next="migration-appointments"" id="migration-communications" data-url="/migration/communications/run" data-completed="Communications completed" disabled="disabled" class="btn btn-danger run-migration btn-sm" >Communications</button>
							<button data-next="migration-encounters" id="migration-appointments" data-url="/migration/appointments/run" data-completed="Appointments completed" disabled="disabled" class="btn btn-danger run-migration btn-sm" >Appointments</button>
							<button data-next="migration-diagnosis" id="migration-encounters" data-url="/migration/encounter/run" data-completed="Encounters completed" disabled="disabled" class="btn btn-danger run-migration btn-sm">Encounters</button>
							<button data-next="migration-labs" id="migration-diagnosis" data-url="/migration/diagnosis/run" data-completed="Diagnosis completed" disabled="disabled" class="btn btn-danger run-migration btn-sm" >Diagnosis</button>
							<button data-next="migration-medications" id="migration-labs" data-url="/migration/labs/run" data-completed="Labs completed" disabled="disabled" class="btn btn-danger run-migration btn-sm" >Labs</button>
							<button data-next="migration-addendums" id="migration-medications" data-url="/migration/medications/run" data-completed="Medications completed" disabled="disabled" class="btn btn-danger run-migration btn-sm" >Medications</button>
							<button data-next="migration-referrals" id="migration-addendums" data-url="/migration/addendums/run" data-completed="Addendums completed" disabled="disabled" class="btn btn-danger run-migration btn-sm" >Addendums</button>
							<!--
							<button data-next="migration-referrals" id="migration-files" data-url="/migration/files/run" data-completed="Files completed" disabled="disabled" class="btn btn-danger run-migration btn-sm" >Files</button>
							-->
							<button data-next="migration-settings" id="migration-referrals" data-url="/migration/referrals/run" data-completed="referrals completed" disabled="disabled" class="btn btn-danger run-migration btn-sm" >Referrals</button>
							<button data-next="" id="migration-settings" data-url="/migration/settings/run" data-completed="Settings completed" disabled="disabled" class="btn btn-danger run-migration btn-sm" >Settings</button>
							
						</div>
						<pre>
sudo chmod -R 775 private/uploads/patients
sudo chown -R $CURRENT_USER:33 private/uploads/patients
php public/index.php migration files run uniqiddem
							</pre>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel panel-info">
			<div class="panel-heading">Logs</div>
			<div class="panel-body"><pre id="log"></pre></div>
		</div>
	
	</div>
</div>
