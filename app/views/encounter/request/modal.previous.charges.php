
<div class="row panel-custom" style="padding-bottom: 30px;">
	
	<div class="col-lg-12">
		<div class="table-responsive" >
			<table class="table table-condensed table-hover table-bordered" >
				
				<thead>
					<tr>
						<th class="col-md-2 text-center">Fecha de consulta</th>
						<th class="text-center">Pago</th>
						<th class="text-center">Total</th>
						<th class="text-center">Sub total</th>
						<th class="text-center">Saldo pendiente</th>
						<th class="text-center">Descuento</th>
						<th class="text-center">Visita a consultorio</th>
						<th class="text-center">Laboratorio</th>
						<th class="text-center">Inyección</th>
						<th class="text-center">Medicamentos</th>
						<th class="text-center">Procedimientos</th>
						<th class="text-center">ECG</th>
						<th class="text-center">Ultrasonido</th>
						<th class="text-center">Rayos X</th>
						<th class="text-center">Impresiones</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-cloak dir-paginate="insurance in paginatePreviousCharges.result_data  | itemsPerPage:paginatePreviousCharges.itemsPerPage"  current-page="paginatePreviousCharges.currentPage" total-items="paginatePreviousCharges.total_count.counter">
						<td class="text-center">
							<a data-toggle="tooltip" title="Encounter request" ng-href="/encounter/request/{{insurance.encounter_id}}"> {{ ngHelper.normalDate(insurance.encounter_date) }}</a>
						</td>
						<td class="text-right " ng-class="(insurance.paid==0) ? 'text-danger' : ''">{{ insurance.paid }}</td>
						<td class="text-right">{{ insurance.total }}</td>
						<td class="text-right" >{{ insurance.subtotal }} 	</td>
						<td class="text-right" ng-class="getClassName(insurance.open_balance)">{{ insurance.open_balance }}</td>

						<td class="text-right" ng-class="getClassName(insurance.discount)">{{ insurance.discount }}</td>
						<td class="text-right" ng-class="getClassName(insurance.office_visit)">{{ insurance.office_visit }}</td>
						<td class="text-right" ng-class="getClassName(insurance.laboratories)">{{ insurance.laboratories }}</td>
						<td class="text-right" ng-class="getClassName(insurance.injections)">{{ insurance.injections }}</td>
						<td class="text-right" ng-class="getClassName(insurance.medications)">{{ insurance.medications }}</td>
						<td class="text-right" ng-class="getClassName(insurance.procedures)">{{ insurance.procedures }}</td>
						<td class="text-right" ng-class="getClassName(insurance.ecg)">{{ insurance.ecg }}</td>
						<td class="text-right" ng-class="getClassName(insurance.ultrasound)">{{ insurance.ultrasound }}</td>
						<td class="text-right" ng-class="getClassName(insurance.x_ray)">{{ insurance.x_ray }}</td>
						<td class="text-right" ng-class="getClassName(insurance.print_cost)">{{ insurance.print_cost }}</td>
					</tr>
				</tbody>
				<tfooter>
					<tr>
						<th class="text-right"> Totales: </th>
						<th class="text-right totals-invoice">{{  paginatePreviousCharges.total_count.paid }}</th>
						<th class="text-right totals-invoice">{{  paginatePreviousCharges.total_count.total }}</th>
						<th class="text-right totals-invoice">{{  paginatePreviousCharges.total_count.subtotal }}</th>
						<th class="text-right totals-invoice">{{  paginatePreviousCharges.total_count.open_balance }}</th>
						<th class="text-right totals-invoice">{{  paginatePreviousCharges.total_count.discount }}</th>
						<th class="text-right totals-invoice">{{  paginatePreviousCharges.total_count.office_visit }}</th>
						<th class="text-right totals-invoice">{{  paginatePreviousCharges.total_count.laboratories }}</th>
						<th class="text-right totals-invoice">{{  paginatePreviousCharges.total_count.injections }}</th>
						<th class="text-right totals-invoice">{{  paginatePreviousCharges.total_count.medications }}</th>
						<th class="text-right totals-invoice">{{  paginatePreviousCharges.total_count.procedures }}</th>
						<th class="text-right totals-invoice">{{  paginatePreviousCharges.total_count.ecg }}</th>
						<th class="text-right totals-invoice">{{  paginatePreviousCharges.total_count.ultrasound }}</th>
						<th class="text-right totals-invoice">{{  paginatePreviousCharges.total_count.x_ray }}</th>
						<th class="text-right totals-invoice">{{  paginatePreviousCharges.total_count.print_cost }}</th>
					</tr>
				</tfooter>
			</table>
		</div>
	</div>
	<div class="col-lg-12 text-right" style="padding-top: 20px;">
		<dir-pagination-controls 
					max-size="8" 
					direction-links="true" 
					boundary-links="false" 
					on-page-change="paginatePreviousCharges.getData(newPageNumber)" ></dir-pagination-controls>
				
	</div>
</div>

