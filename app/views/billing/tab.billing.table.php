
<div class="row">
	<div clas="col-lg-12">

		<ul class="nav nav-pills" style="margin-left: 12px;">
			<li  class="active"><a href="#charges-principal"  data-toggle="tab">Cargos 1</a></li>
			<li ng-repeat="item in data.billing.extraCharges">
				<a href="#charges-{{item.id}}"  data-toggle="tab">Cargos {{$index + 2}}</a>
			<li>
			<li ><a href="#"  data-toggle="tab" id="addCharges" ng-click="chargesController.add($event)"> <i class="fa fa-plus"></i>Agregar cargos </a></li>
		</ul>
		<div class="tab-content clearfix">
			<div class="tab-pane active" id="charges-principal">
				<div class="row">
					<div class="col-lg-12">
						<table class="table table-bordered table-condensed" style="font-size:12px;">
							<thead>
								<tr class="well well-sm">
									<th class="text-center" > 
										<span>Numero</span>
									</th>
									<th>Activo</th>
									<th>Fecha</th>
									<th>Lugar del servicio</th>
									<th>CPT/HCPCS</th>
									<th>Modificador</th>
									<th>Indicador de diagnóstico</th>
									<th>Cargos</th>
									<th>Días por unidad</th>
									<!-- <th>Family plan</th> -->
									<th>ID de Calificación</th>
									<th>Proveedor de servicios</th>
									<th>MDC #</th>
								</tr>
							</thead>
							<tbody>
								<tr ng-repeat="det in data.billing.detail">
										<td class="text-center">
											<label  style="margin-top:3px;">{{ det.number }}</label>
										</td>
										<td>
											<label class="switch">
												<input type="checkbox"  ng-true-value="'1'" ng-false-value="'0'" ng-model="det.active"> 
												<span class="on">Si</span>
												<span class="off">No</span>
											</label>
										</td>
										<td> <input ng-readonly="data.not_edit" ng-model="det.date_of_service" type="text" class="form-control input-xs"> </td> 
										<!-- <td> <input ng-readonly="data.not_edit" ng-model="det.emg" type="text" class="form-control input-xs"> </td> -->
										<td> <input ng-readonly="data.not_edit" ng-model="det.place_of_service" type="text" class="form-control input-xs"> </td>

										<td> <input ng-readonly="data.not_edit" ng-model="det.procedure_cpt_hcpcs" type="text" class="form-control input-xs" ng-change="hasProcedure(det)" > </td>
										<td class="col-md-2">
											<div class="form-input-group">
												<input ng-readonly="data.not_edit" placeholder="A" ng-model="det.modifier_a" type="text" class="form-control input-xs input-col-4">
												<input ng-readonly="data.not_edit" placeholder="B" ng-model="det.modifier_b" type="text" class="form-control input-xs input-col-4"> 
												<input ng-readonly="data.not_edit" placeholder="C" ng-model="det.modifier_c" type="text" class="form-control input-xs input-col-4"> 
												<input ng-readonly="data.not_edit" placeholder="D" ng-model="det.modifier_d" type="text" class="form-control input-xs input-col-4"> 
											</div>
										</td>
										<td> <input ng-readonly="data.not_edit" ng-model="det.diagnosis_pointer" type="text" class="form-control input-xs"> </td>
										<td> <input ng-readonly="data.not_edit" ng-model="det.charges" ng-change="change_charge()" type="number" string-to-number class="form-control input-xs"> </td>
										
										<td> <input ng-readonly="data.not_edit" ng-model="det.days_units" type="text" class="form-control input-xs"> </td>
										<!-- <td> <input ng-readonly="data.not_edit" ng-model="det.family_plan" type="text" class="form-control input-xs"> </td> -->
										<td> <input ng-readonly="data.not_edit" ng-model="det.id_qual" type="text" class="form-control input-xs"> </td>
										<td class="col-md-1"> 
											<input ng-readonly="data.not_edit" placeholder="ID" ng-model="det.rendering_provider_id" type="text" class="form-control input-xs"> 
										</td>
										<td class="col-md-1"> 
											<input ng-readonly="data.not_edit" placeholder="MDC" ng-model="det.notes_unit" type="text" class="form-control input-xs"> 
										</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="tab-pane" ng-repeat="item in data.billing.extraCharges" id="charges-{{item.id}}">
				
				<div class="tab-pane active" id="charges-principal">
				<div class="row">
					<div class="col-lg-12">
						<table class="table table-bordered table-condensed" style="font-size:12px;">
							<thead>
								<tr class="well well-sm">
									<th class="text-center" > 
										<span>Number</span>
									</th>
									<th>Active</th>
									<th>Date</th>
									<!-- <th>EMG</th> -->
									<th>Place of service</th>
									<th>CPT/HCPCS</th>
									<th>Modifier</th>
									<th>Diag pointer</th>
									<th>Charges</th>
									<th>Days or unit</th>
									<!-- <th>Family plan</th> -->
									<th>ID Qual</th>
									<th>Rend. provider</th>
									<th>MDC #</th>
								</tr>
							</thead>
							<tbody>
								<tr >
									<td class="text-center">
										<label  style="margin-top:3px;">1</label>
									</td>
									<td>
										<label class="switch">
											<input type="checkbox"  ng-true-value="'1'" ng-false-value="'0'" ng-model="item.active_1"> 
											<span class="on">Yes</span>
											<span class="off">No</span>
										</label>
									</td>
									<!-- <td> <input ng-readonly="data.not_edit" ng-model="item.emg_1" type="text" class="form-control input-xs"> </td>
									-->
									<td> <input ng-readonly="data.not_edit" ng-model="item.date_of_service_1" type="text" class="form-control input-xs"> </td>
									<td> <input ng-readonly="data.not_edit" ng-model="item.place_of_service_1" type="text" class="form-control input-xs"> </td>
									<td> <input ng-readonly="data.not_edit" ng-change="hasProcedureExtra(item, 1)" ng-model="item.procedure_cpt_hcpcs_1" type="text" class="form-control input-xs"> </td>
									<td class="col-md-2">
										<div class="form-input-group">
											<input ng-readonly="data.not_edit" placeholder="A" ng-model="item.modifier_a_1" type="text" class="form-control input-xs input-col-4">
											<input ng-readonly="data.not_edit" placeholder="B" ng-model="item.modifier_b_1" type="text" class="form-control input-xs input-col-4"> 
											<input ng-readonly="data.not_edit" placeholder="C" ng-model="item.modifier_c_1" type="text" class="form-control input-xs input-col-4"> 
											<input ng-readonly="data.not_edit" placeholder="D" ng-model="item.modifier_d_1" type="text" class="form-control input-xs input-col-4"> 
										</div>
									</td>
									<td> <input ng-readonly="data.not_edit" ng-model="item.diagnosis_pointer_1" type="text" class="form-control input-xs"> </td>
									<td> <input ng-readonly="data.not_edit" ng-model="item.charges_1" ng-change="change_charge()" type="number" string-to-number class="form-control input-xs"> </td>
									<td> <input ng-readonly="data.not_edit" ng-model="item.days_units_1" type="text" class="form-control input-xs"> </td>
									<!-- <td> <input ng-readonly="data.not_edit" ng-model="item.family_plan_1" type="text" class="form-control input-xs"> </td> -->
									<td> <input ng-readonly="data.not_edit" ng-model="item.id_qual_1" type="text" class="form-control input-xs"> </td>
									<td class="col-md-1"> 
										<input ng-readonly="data.not_edit" placeholder="ID" ng-model="item.rendering_provider_id_1" type="text" class="form-control input-xs"> 
									</td>
									<td class="col-md-1"> 
										<input ng-readonly="data.not_edit" placeholder="MDC" ng-model="det.notes_unit_1" type="text" class="form-control input-xs"> 
									</td>
								</tr>
								<tr >
									<td class="text-center">
										<label  style="margin-top:3px;">2</label>
									</td>
									<td>
										<label class="switch">
											<input type="checkbox"  ng-true-value="'1'" ng-false-value="'0'" ng-model="item.active_2"> 
											<span class="on">Yes</span>
											<span class="off">No</span>
										</label>
									</td>
									<!-- <td> <input ng-readonly="data.not_edit" ng-model="item.emg_2" type="text" class="form-control input-xs"> </td>
									-->
									<td> <input ng-readonly="data.not_edit" ng-model="item.date_of_service_2" type="text" class="form-control input-xs"> </td>
									<td> <input ng-readonly="data.not_edit" ng-model="item.place_of_service_2" type="text" class="form-control input-xs"> </td>
									<td> <input ng-readonly="data.not_edit" ng-change="hasProcedureExtra(item,2)" ng-model="item.procedure_cpt_hcpcs_2" type="text" class="form-control input-xs"> </td>
									<td class="col-md-2">
										<div class="form-input-group">
											<input ng-readonly="data.not_edit" placeholder="A" ng-model="item.modifier_a_2" type="text" class="form-control input-xs input-col-4">
											<input ng-readonly="data.not_edit" placeholder="B" ng-model="item.modifier_b_2" type="text" class="form-control input-xs input-col-4"> 
											<input ng-readonly="data.not_edit" placeholder="C" ng-model="item.modifier_c_2" type="text" class="form-control input-xs input-col-4"> 
											<input ng-readonly="data.not_edit" placeholder="D" ng-model="item.modifier_d_2" type="text" class="form-control input-xs input-col-4"> 
										</div>
									</td>
									<td> <input ng-readonly="data.not_edit" ng-model="item.diagnosis_pointer_2" type="text" class="form-control input-xs"> </td>
									<td> <input ng-readonly="data.not_edit" ng-model="item.charges_2" ng-change="change_charge()" type="number" string-to-number class="form-control input-xs"> </td>
									<td> <input ng-readonly="data.not_edit" ng-model="item.days_units_2" type="text" class="form-control input-xs"> </td>
									<!-- <td> <input ng-readonly="data.not_edit" ng-model="item.family_plan_2" type="text" class="form-control input-xs"> </td> -->
									<td> <input ng-readonly="data.not_edit" ng-model="item.id_qual_2" type="text" class="form-control input-xs"> </td>
									<td class="col-md-1"> 
										<input ng-readonly="data.not_edit" placeholder="ID" ng-model="item.rendering_provider_id_2" type="text" class="form-control input-xs"> 
									</td>
									<td class="col-md-1"> 
										<input ng-readonly="data.not_edit" placeholder="MDC" ng-model="det.notes_unit_2" type="text" class="form-control input-xs"> 
									</td>
								</tr>
								<tr >
									<td class="text-center">
										<label  style="margin-top:3px;">3</label>
									</td>
									<td>
										<label class="switch">
											<input type="checkbox"  ng-true-value="'1'" ng-false-value="'0'" ng-model="item.active_3"> 
											<span class="on">Yes</span>
											<span class="off">No</span>
										</label>
									</td>
									<!-- <td> <input ng-readonly="data.not_edit" ng-model="item.emg_3" type="text" class="form-control input-xs"> </td>
									-->
									<td> <input ng-readonly="data.not_edit" ng-model="item.date_of_service_3" type="text" class="form-control input-xs"> </td>
									<td> <input ng-readonly="data.not_edit" ng-model="item.place_of_service_3" type="text" class="form-control input-xs"> </td>
									<td> <input ng-readonly="data.not_edit" ng-change="hasProcedureExtra(item,3)" ng-model="item.procedure_cpt_hcpcs_3" type="text" class="form-control input-xs"> </td>
									<td class="col-md-2">
										<div class="form-input-group">
											<input ng-readonly="data.not_edit" placeholder="A" ng-model="item.modifier_a_3" type="text" class="form-control input-xs input-col-4">
											<input ng-readonly="data.not_edit" placeholder="B" ng-model="item.modifier_b_3" type="text" class="form-control input-xs input-col-4"> 
											<input ng-readonly="data.not_edit" placeholder="C" ng-model="item.modifier_c_3" type="text" class="form-control input-xs input-col-4"> 
											<input ng-readonly="data.not_edit" placeholder="D" ng-model="item.modifier_d_3" type="text" class="form-control input-xs input-col-4"> 
										</div>
									</td>
									<td> <input ng-readonly="data.not_edit" ng-model="item.diagnosis_pointer_3" type="text" class="form-control input-xs"> </td>
									<td> <input ng-readonly="data.not_edit" ng-model="item.charges_3" ng-change="change_charge()" type="number" string-to-number class="form-control input-xs"> </td>
									<td> <input ng-readonly="data.not_edit" ng-model="item.days_units_3" type="text" class="form-control input-xs"> </td>
									<!-- <td> <input ng-readonly="data.not_edit" ng-model="item.family_plan_3" type="text" class="form-control input-xs"> </td> -->
									<td> <input ng-readonly="data.not_edit" ng-model="item.id_qual_3" type="text" class="form-control input-xs"> </td>
									<td class="col-md-1"> 
										<input ng-readonly="data.not_edit" placeholder="ID" ng-model="item.rendering_provider_id_3" type="text" class="form-control input-xs"> 
									</td>
									<td class="col-md-1"> 
										<input ng-readonly="data.not_edit" placeholder="MDC" ng-model="det.notes_unit_3" type="text" class="form-control input-xs"> 
									</td>
								</tr>
								<tr >
									<td class="text-center">
										<label  style="margin-top:3px;">4</label>
									</td>
									<td>
										<label class="switch">
											<input type="checkbox"  ng-true-value="'1'" ng-false-value="'0'" ng-model="item.active_4"> 
											<span class="on">Yes</span>
											<span class="off">No</span>
										</label>
									</td>
									<!--
									<td> <input ng-readonly="data.not_edit" ng-model="item.emg_4" type="text" class="form-control input-xs"> </td>
									-->
									<td> <input ng-readonly="data.not_edit" ng-model="item.date_of_service_4" type="text" class="form-control input-xs"> </td>
									<td> <input ng-readonly="data.not_edit" ng-model="item.place_of_service_4" type="text" class="form-control input-xs"> </td>
									<td> <input ng-readonly="data.not_edit" ng-change="hasProcedureExtra(item,4)" ng-model="item.procedure_cpt_hcpcs_4" type="text" class="form-control input-xs"> </td>
									<td class="col-md-2">
										<div class="form-input-group">
											<input ng-readonly="data.not_edit" placeholder="A" ng-model="item.modifier_a_4" type="text" class="form-control input-xs input-col-4">
											<input ng-readonly="data.not_edit" placeholder="B" ng-model="item.modifier_b_4" type="text" class="form-control input-xs input-col-4"> 
											<input ng-readonly="data.not_edit" placeholder="C" ng-model="item.modifier_c_4" type="text" class="form-control input-xs input-col-4"> 
											<input ng-readonly="data.not_edit" placeholder="D" ng-model="item.modifier_d_4" type="text" class="form-control input-xs input-col-4"> 
										</div>
									</td>
									<td> <input ng-readonly="data.not_edit" ng-model="item.diagnosis_pointer_4" type="text" class="form-control input-xs"> </td>
									<td> <input ng-readonly="data.not_edit" ng-model="item.charges_4" ng-change="change_charge()" type="number" string-to-number class="form-control input-xs"> </td>
									<td> <input ng-readonly="data.not_edit" ng-model="item.days_units_4" type="text" class="form-control input-xs"> </td>
									<!-- <td> <input ng-readonly="data.not_edit" ng-model="item.family_plan_4" type="text" class="form-control input-xs"> </td> -->
									<td> <input ng-readonly="data.not_edit" ng-model="item.id_qual_4" type="text" class="form-control input-xs"> </td>
									<td class="col-md-1"> 
										<input ng-readonly="data.not_edit" placeholder="ID" ng-model="item.rendering_provider_id_4" type="text" class="form-control input-xs"> 
									</td>
									<td class="col-md-1"> 
										<input ng-readonly="data.not_edit" placeholder="MDC" ng-model="det.notes_unit_4" type="text" class="form-control input-xs"> 
									</td>
								</tr>
								<tr >
									<td class="text-center">
										<label  style="margin-top:3px;">5</label>
									</td>
									<td>
										<label class="switch">
											<input type="checkbox"  ng-true-value="'1'" ng-false-value="'0'" ng-model="item.active_5"> 
											<span class="on">Yes</span>
											<span class="off">No</span>
										</label>
									</td>
									<!--
									<td> <input ng-readonly="data.not_edit" ng-model="item.emg_5" type="text" class="form-control input-xs"> </td>
									-->
									<td> <input ng-readonly="data.not_edit" ng-model="item.date_of_service_5" type="text" class="form-control input-xs"> </td>
									<td> <input ng-readonly="data.not_edit" ng-model="item.place_of_service_5" type="text" class="form-control input-xs"> </td>
									<td> <input ng-readonly="data.not_edit" ng-change="hasProcedureExtra(item,5)" ng-model="item.procedure_cpt_hcpcs_5" type="text" class="form-control input-xs"> </td>
									<td class="col-md-2">
										<div class="form-input-group">
											<input ng-readonly="data.not_edit" placeholder="A" ng-model="item.modifier_a_5" type="text" class="form-control input-xs input-col-4">
											<input ng-readonly="data.not_edit" placeholder="B" ng-model="item.modifier_b_5" type="text" class="form-control input-xs input-col-4"> 
											<input ng-readonly="data.not_edit" placeholder="C" ng-model="item.modifier_c_5" type="text" class="form-control input-xs input-col-4"> 
											<input ng-readonly="data.not_edit" placeholder="D" ng-model="item.modifier_d_5" type="text" class="form-control input-xs input-col-4"> 
										</div>
									</td>
									<td> <input ng-readonly="data.not_edit" ng-model="item.diagnosis_pointer_5" type="text" class="form-control input-xs"> </td>
									<td> <input ng-readonly="data.not_edit" ng-model="item.charges_5" ng-change="change_charge()" type="number" string-to-number class="form-control input-xs"> </td>
									 <td> <input ng-readonly="data.not_edit" ng-model="item.days_units_5" type="text" class="form-control input-xs"> </td>
									<!-- <td> <input ng-readonly="data.not_edit" ng-model="item.family_plan_5" type="text" class="form-control input-xs"> </td> -->
									<td> <input ng-readonly="data.not_edit" ng-model="item.id_qual_5" type="text" class="form-control input-xs"> </td>
									<td class="col-md-1"> 
										<input ng-readonly="data.not_edit" placeholder="ID" ng-model="item.rendering_provider_id_5" type="text" class="form-control input-xs"> 
									</td>
									<td class="col-md-1"> 
										<input ng-readonly="data.not_edit" placeholder="MDC" ng-model="det.notes_unit_5" type="text" class="form-control input-xs"> 
									</td>
								</tr>
								<tr >
									<td class="text-center">
										<label  style="margin-top:3px;">6</label>
									</td>
									<td>
										<label class="switch">
											<input type="checkbox"  ng-true-value="'1'" ng-false-value="'0'" ng-model="item.active_6"> 
											<span class="on">Yes</span>
											<span class="off">No</span>
										</label>
									</td>
									<!--
									<td> <input ng-readonly="data.not_edit" ng-model="item.emg_6" type="text" class="form-control input-xs"> </td>
									-->
									<td> <input ng-readonly="data.not_edit" ng-model="item.date_of_service_6" type="text" class="form-control input-xs"> </td>
									<td> <input ng-readonly="data.not_edit" ng-model="item.place_of_service_6" type="text" class="form-control input-xs"> </td>
									<td> <input ng-readonly="data.not_edit" ng-change="hasProcedureExtra(item,6)" ng-model="item.procedure_cpt_hcpcs_6" type="text" class="form-control input-xs"> </td>
									<td class="col-md-2">
										<div class="form-input-group">
											<input ng-readonly="data.not_edit" placeholder="A" ng-model="item.modifier_a_6" type="text" class="form-control input-xs input-col-4">
											<input ng-readonly="data.not_edit" placeholder="B" ng-model="item.modifier_b_6" type="text" class="form-control input-xs input-col-4"> 
											<input ng-readonly="data.not_edit" placeholder="C" ng-model="item.modifier_c_6" type="text" class="form-control input-xs input-col-4"> 
											<input ng-readonly="data.not_edit" placeholder="D" ng-model="item.modifier_d_6" type="text" class="form-control input-xs input-col-4"> 
										</div>
									</td>
									<td> <input ng-readonly="data.not_edit" ng-model="item.diagnosis_pointer_6" type="text" class="form-control input-xs"> </td>
									<td> <input ng-readonly="data.not_edit" ng-model="item.charges_6" ng-change="change_charge()" type="number" string-to-number class="form-control input-xs"> </td>
									<td> <input ng-readonly="data.not_edit" ng-model="item.days_units_6" type="text" class="form-control input-xs"> </td>
									<!-- <td> <input ng-readonly="data.not_edit" ng-model="item.family_plan_6" type="text" class="form-control input-xs"> </td> -->
									<td> <input ng-readonly="data.not_edit" ng-model="item.id_qual_6" type="text" class="form-control input-xs"> </td>
									<td class="col-md-1"> 
										<input ng-readonly="data.not_edit" placeholder="ID" ng-model="item.rendering_provider_id_6" type="text" class="form-control input-xs"> 
									</td>
									<td class="col-md-1"> 
										<input ng-readonly="data.not_edit" placeholder="MDC" ng-model="det.notes_unit_6" type="text" class="form-control input-xs"> 
									</td>
								</tr>
							</tbody>
						</table>
						<div class="text-center" style="margin-bottom: 2px;margin-right: 4px;">
							<button class="pull-right btn btn-sm btn-danger" ng-click="chargesController.remove(item, $index)">Remover cargos ({{$index+2}}) </button>
							<p>Total de cargos: {{item.total_charge  }}</p>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</div>
			</div>
        </div>
    </div>
</div>