<style type="text/css">
	.bold{
		font-weight: 700;
	}
	.totals-invoice{
		background-color:#4d4d4d;
		color:#fff;
		font-size: 14px;
	}
</style>
<div class="panel panel-default  panel-custom" >
	<div class="panel-heading">
		<div class="row">
			<div class="col-sm-2">
				<label>Detalles de la factura <span class="badge" ng-cloak data-toggle="tooltip" data-placement="right" title="Total de facturas">{{ appPagination.total_count.counter.toLocaleString() }}</span></label>
			</div>
			<div class="col-sm-2 text-right">
				<label>Rango de fechas</label>
			</div>
			<div class="col-sm-3">
				<div id="reportrange"  style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
				    <i class="fa fa-calendar"></i>&nbsp;
				    <span></span> <i class="fa fa-caret-down"></i>
				</div>
			</div>
			<div class="col-sm-5 text-right">
				<a title="Imprimir" target="_blank" data-placement="bottom" ng-href="{{createUrl()}}" target="_blank" data-toggle="tooltip"  class="btn btn-warning btn-xs" > <i class="fa fa-print"></i> Imprimir </a>
			</div>
		</div>
	</div>
	<div class="panel-body"  style="height:auto;min-height: 200px;">
		<div class="table-responsive" >
			<table class="table table-condensed table-hover table-bordered " >
				
				<thead>
					<tr>
						<th  style="min-width: 150px;">
							 <input type="text" ng-change="appPagination.getData(1)" class="form-control input-sm"  ng-model="filter.patient" placeholder="Paciente" />
						</th>
						<th  style="min-width: 100px;max-width: 100px;">
							<div class="input-group input-group-sm">
                               <input placeholder="Id de consulta"  ng-model="filter.encounter_id"  type="text" ng-change="appPagination.getData(1)" class="form-control input-sm"  />
                                <span class="input-group-btn">
                                    <a ng-click="appPagination.sortData('encounter_id')" class="btn btn-default btn-sm">
                                    <i class="fa  fa-sort-desc" ng-class="appPagination.sortClass('encounter_id')"></i></a>
                                </span>
                            </div>
							
						</th>
						<th class="text-center" >
							Tipo de visita
							<!--
							<input placeholder="Visit Type"  ng-model="filter.appt_visit_type"  type="text" ng-change="appPagination.getData(1)" class="form-control input-sm"  />
						-->
						</th>
						<th class="text-center">Tipo</th>
						<th class="text-center">Pago</th>
						<th class="text-center">Total</th>
						<th class="text-center">Subtotal</th>
						<th class="text-center">Saldo pendiente</th>
						<th class="text-center">Descuento</th>
						<th class="text-center">Visita a la oficina</th>
						<th class="text-center">Laboratorios</th>
						<th class="text-center">Inyecciones</th>
						<th class="text-center">Medicamentos</th>
						<th class="text-center">Procedimientos</th>
						<th class="text-center">Examen físico</th>
						<th class="text-center">ECG</th>
						<th class="text-center">Ecografía</th>
						<th class="text-center">Rayos x</th>
						<th class="text-center">Imprimir</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-cloak dir-paginate="insurance in appPagination.result_data  | itemsPerPage:appPagination.itemsPerPage"  current-page="appPagination.currentPage" total-items="appPagination.total_count.counter">
						<td> 
							<i class="fa fa-calendar" data-toggle="tooltip" title="DOB {{insurance.patient_dob}}"></i>
							<a data-toggle="tooltip" title="Patient detail" ng-href="/patient/detail/{{insurance.patient_id}}">{{insurance.patient}}</a>
						</td>
						<td>
							<i class="fa fa-clock-o" data-toggle="tooltip" title="Signed date {{ngHelper.formatDate(insurance.signed_at)}}"></i>
							<a data-toggle="tooltip" title="Encounter request" ng-href="/encounter/request/{{insurance.encounter_id}}">{{insurance.encounter_id}}</a>
						</td>
						<td >{{ insurance.appt_visit_type }} </td>
						<td >{{ insurance.payment_type }} </td>
						<td class="text-right " ng-class="(insurance.paid==0) ? 'text-danger' : ''">{{ insurance.paid }}</td>
						<td class="text-right">{{ insurance.total }}</td>
						<td class="text-right">{{ insurance.subtotal }} 	</td>
						<td class="text-right">{{ insurance.open_balance }}</td>

						<td class="text-right" ng-class="getClassName(insurance.discount)">{{ insurance.discount }}</td>
						<td class="text-right" ng-class="getClassName(insurance.office_visit)">{{ insurance.office_visit }}</td>
						<td class="text-right" ng-class="getClassName(insurance.laboratories)">{{ insurance.laboratories }}</td>
						<td class="text-right" ng-class="getClassName(insurance.injections)">{{ insurance.injections }}</td>
						<td class="text-right" ng-class="getClassName(insurance.medications)">{{ insurance.medications }}</td>
						<td class="text-right" ng-class="getClassName(insurance.procedures)">{{ insurance.procedures }}</td>
						<td class="text-right" ng-class="getClassName(insurance.physical)">{{ insurance.physical }}</td>
						<td class="text-right" ng-class="getClassName(insurance.ecg)">{{ insurance.ecg }}</td>
						<td class="text-right" ng-class="getClassName(insurance.ultrasound)">{{ insurance.ultrasound }}</td>
						<td class="text-right" ng-class="getClassName(insurance.x_ray)">{{ insurance.x_ray }}</td>
						<td class="text-right" ng-class="getClassName(insurance.print_cost)">{{ insurance.print_cost }}</td>
					</tr>
				</tbody>
				<tfooter>
					<tr>
						<th></th>
						<th></th>
						<th></th>
						<th class="text-right"> Totals: </th>
						<th class="text-right totals-invoice">{{  appPagination.total_count.paid }}</th>
						<th class="text-right totals-invoice">{{  appPagination.total_count.total }}</th>
						<th class="text-right totals-invoice">{{  appPagination.total_count.subtotal }}</th>
						<th class="text-right totals-invoice">{{  appPagination.total_count.open_balance }}</th>
						<th class="text-right totals-invoice">{{  appPagination.total_count.discount }}</th>
						<th class="text-right totals-invoice">{{  appPagination.total_count.office_visit }}</th>
						<th class="text-right totals-invoice">{{  appPagination.total_count.laboratories }}</th>
						<th class="text-right totals-invoice">{{  appPagination.total_count.injections }}</th>
						<th class="text-right totals-invoice">{{  appPagination.total_count.medications }}</th>
						<th class="text-right totals-invoice">{{  appPagination.total_count.procedures }}</th>
						<th class="text-right totals-invoice">{{  appPagination.total_count.physical }}</th>
						<th class="text-right totals-invoice">{{  appPagination.total_count.ecg }}</th>
						<th class="text-right totals-invoice">{{  appPagination.total_count.ultrasound }}</th>
						<th class="text-right totals-invoice">{{  appPagination.total_count.x_ray }}</th>
						<th class="text-right totals-invoice">{{  appPagination.total_count.print_cost }}</th>
					</tr>
				</tfooter>
			</table>
		</div>
	</div>
	<div class="panel-footer text-right">
		<div class="row">
			<div class="col-xs-2 col-xs-offset-6">
				<select class="form-control input-sm" ng-model="appPagination.itemsPerPage">
					<option value="10">10 Resultados por pagina</option>
					<option value="20">20 Resultados por pagina</option>
					<option value="50">50 Resultados por pagina</option>
					<option value="100">100 Resultados por pagina</option>
				</select>
			</div>
			<div class="col-xs-4 text-right">
				<dir-pagination-controls 
					max-size="8" 
					direction-links="true" 
					boundary-links="false" 
					on-page-change="appPagination.getData(newPageNumber)" ></dir-pagination-controls>
				
			</div>
		</div>
		
	</div>
</div>
