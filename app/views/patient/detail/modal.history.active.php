<div class="row form-horizontal">
	<div class="col-lg-12">
		<div class="col-md-6">
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Última SHA</label>
				<div class="col-md-3">
					<input type="text" ng-model="default.history_active.last_sha" placeholder="Fecha" class="form-control" tabindex="-1">
				</div>
				<label class="col-md-3 control-label">Última revisión física</label>
				<div class="col-md-3">
					<input type="text" ng-model="default.history_active.last_physical" placeholder="Fecha" class="form-control">
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Último PAP</label>
				<div class="col-md-3">
					<input type="text" ng-model="default.history_active.pregnancy_last_pap" placeholder="Fecha" class="form-control">
				</div>
				<label class="col-md-3 control-label" ng-show="default.history_active.pregnancy_last_pap" >Normal</label>
				<div class="col-md-3" ng-show="default.history_active.pregnancy_last_pap">
					<select class="form-control" ng-model="default.history_active.last_pap_normal"  >
						<option value="No">No</option>
						<option value="Yes">Si</option>
					</select>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Última mamografía</label>
				<div class="col-md-3">
					<input type="text" ng-model="default.history_active.pregnancy_last_mamo" placeholder="Fecha" class="form-control">
				</div>
				<label class="col-md-3 control-label" ng-show="default.history_active.pregnancy_last_mamo" >Normal</label>
				<div class="col-md-3" ng-show="default.history_active.pregnancy_last_mamo">
					<select class="form-control" ng-model="default.history_active.last_mamo_normal"  >
						<option value="No">No</option>
						<option value="Yes">Si</option>
					</select>
				</div>
			</div>
			<div class="form-group form-group-sm" >
				<label class="col-md-3 control-label">Antígeno Prostático Específico</label>
				<div class="col-md-9">
					<input type="text" ng-model="default.history_active.psa" class="form-control" >
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Colonoscopia (51-75 años)</label>
				<div class="col-md-3">
					<input type="text" ng-model="default.history_active.last_colonoscopy" placeholder="Fecha" class="form-control">
				</div>
				<label class="col-md-3 control-label">Último SIG</label>
				<div class="col-md-3">
					<input type="text" ng-model="default.history_active.last_sig" placeholder="Fecha" class="form-control">
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Último FOBT</label>
				<div class="col-md-3"  >
					<input type="text" ng-model="default.history_active.last_fobt" placeholder="Fecha" class="form-control">
				</div>
				<label class="col-md-3 control-label">Última clamidia</label>
				<div class="col-md-3">
					<input type="text" ng-model="default.history_active.last_chlamidia" placeholder="Fecha" class="form-control">
				</div>
			</div>
			
			<!-- -->
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Último ECG</label>
				<div class="col-md-3">
					<input type="text" ng-model="default.history_active.last_ecg" placeholder="Fecha" class="form-control">
				</div>
				<label class="col-md-3 control-label" ng-show="default.history_active.last_ecg" >Normal</label>
				<div class="col-md-3" ng-show="default.history_active.last_ecg">
					<select class="form-control" ng-model="default.history_active.last_ecg_normal"  >
						<option value="No">No</option>
						<option value="Yes">Si</option>
					</select>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Escáner DEXA (+60 años)</label>
				<div class="col-md-3">
					<input type="text" ng-model="default.history_active.dexa_scan" placeholder="Fecha" class="form-control">
				</div>
				<label class="col-md-3 control-label" ng-show="default.history_active.dexa_scan" >Normal</label>
				<div class="col-md-3" ng-show="default.history_active.dexa_scan">
					<select class="form-control" ng-model="default.history_active.dexa_scan_normal"  >
						<option value="No">No</option>
						<option value="Yes">Si</option>
					</select>
				</div>
			</div>

			<div  class="form-group form-group-sm">
				<label class="col-md-3 control-label">Último colesterol/LDL</label>
				<div class="col-md-3">
					<input type="text" ng-model="default.history_active.last_cholesterol" placeholder="Fecha" class="form-control">
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">HGBA1C o Hemoglobina</label>
				<div class="col-md-3">
					<input type="text" ng-model="default.history_active.hgba1c_hemoglobin" placeholder="Fecha" class="form-control">
				</div>
				<label class="col-md-3 control-label" ng-show="default.history_active.hgba1c_hemoglobin" >Normal</label>
				<div class="col-md-3" ng-show="default.history_active.hgba1c_hemoglobin">
					<select class="form-control" ng-model="default.history_active.hgba1c_hemoglobin_normal"  >
						<option value="No">No</option>
						<option value="Yes">Si</option>
					</select>
				</div>
			</div>

			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Resultado</label>
				<div class="col-md-3">
					<input type="text" ng-model="default.history_active.results" placeholder="Fecha" class="form-control">
				</div>
				<label class="col-md-3 control-label" ng-show="default.history_active.results" >Normal</label>
				<div class="col-md-3" ng-show="default.history_active.results">
					<select class="form-control" ng-model="default.history_active.results_normal"  >
						<option value="No">No</option>
						<option value="Yes">Si</option>
					</select>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">¿Consume alcohol??</label>
				<div class="col-md-3" >
					<select class="form-control" ng-model="default.history_active.alcohol_history"  >
						<option value="No">No</option>
						<option value="Yes">Si</option>
						<option value=""></option>
					</select>
				</div>
				<label class="col-md-3 control-label">¿Es fumador?</label>
				<div class="col-md-3" >
					<select class="form-control" ng-model="default.history_active.smoking_history"  >
						<option value="No">No</option>
						<option value="Yes">Si</option>
						<option value=""></option>
					</select>
				</div>
			</div>
		</div>
		
		<div class="col-md-6">
			<h3 class="text-center" style="margin-top: 2px;">Embarazos</h3>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Control Natal</label>
				<div class="col-md-9">
					<input type="text" ng-model="default.history_active.pregnancy_birth_control"  class="form-control">
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Existosos</label>
				<div class="col-md-9">
					<input type="number"  ng-model="default.history_active.pregnancy_count_succesfull"  class="form-control">
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Cesáreas</label>
				<div class="col-md-9">
					<input type="number" ng-model="default.history_active.pregnancy_count_cesarean"   class="form-control">
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Abortos/Abortos espontáneos</label>
				<div class="col-md-9">
					<input type="number" ng-model="default.history_active.pregnancy_count_abortions"   class="form-control">
				</div>
			</div>
			
			<h3 class="text-center">Vacunas</h3>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Última influenza</label>
				<div class="col-md-9">
					<input type="text" ng-model="default.history_active.last_influenza" placeholder="Fecha" class="form-control">
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label" >Vacuna contra el tétanos</label>
				<div class="col-md-9">
					<input type="text" ng-model="default.history_active.last_tetanous" placeholder="Fecha" class="form-control">
				</div>
				<!--
				<label class="col-md-3 control-label" ng-show="default.history_active.last_tetanous">Normal</label>
				<div class="col-md-3" ng-show="default.history_active.last_tetanous">
					<select class="form-control" ng-model="default.history_active.last_tetanous_normal">
						<option value="No">No</option>
						<option value="Yes">Si</option>
					</select>
				</div>
				-->
			</div>
			
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Última vacuna neumocócica (+60 años)</label>
				<div class="col-md-9">
					<input type="text" ng-model="default.history_active.vaccine_pneumo" placeholder="Fecha" class="form-control">
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Vacuna contra el herpes zóster</label>
				<div class="col-md-9">
					<input type="text" ng-model="default.history_active.vaccine_zoster" placeholder="Fecha" class="form-control">
				</div>
				
			</div>
			<div class="form-group form-group-sm">
				<label class="col-md-3 control-label">Último PPD</label>
				<div class="col-md-3">
					<input type="text" ng-model="default.history_active.last_ppd" placeholder="Fecha" class="form-control">
				</div>
				<label class="col-md-3 control-label" ng-show="default.history_active.last_ppd">Normal</label>
				<div class="col-md-3" ng-show="default.history_active.last_ppd">
					<select class="form-control" ng-model="default.history_active.last_ppd_normal">
						<option value="No">No</option>
						<option value="Yes">Si</option>
					</select>
				</div>
			</div>

		</div>
	</div>
</div>

<div class="row well well" style="margin-bottom:0px;">
	<div class="col-lg-12 text-right" style="margin-bottom:0px;">
		<button type="button" ng-click="action_activehistory.submit()" class="btn btn-md btn-primary submit"> Guardar </button>
	</div>
</div>