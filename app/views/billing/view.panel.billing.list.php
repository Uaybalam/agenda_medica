<div class="panel panel-default panel-custom">
	<div class="panel-heading">
		<div class="pull-right">
			<label style="margin-right: 10px;">
				<input ng-change="toggleForPrint()" ng-true-value="1" ng-false-value="0"  ng-model="checkPrint" type="checkbox" name="mynamestring" /> 
					<span ng-show="checkPrint==0">Habilitar</span> 
					<span ng-show="checkPrint==1">Deshabilitar</span>
					{{ (billing | filter: canPrint ).length }} facturas por imprimir
			</label>
			<a href="#" ng-click="goToPrint($event)" class=" btn btn-warning btn-xs" >
				Pendientes por imprimir <span class="badge badge-secondary">{{ pendingPrint }}</span>
			</a>
			<a style="display: none;" href="/billing/pdf/all" id="printAll" target="_blank">Hidden</a>
		</div><b>Facturas</b> 
		<span class="badge" title="Total Bills" data-toggle="tooltip" data-placement="right" ng-cloak> {{ total_count.toLocaleString() }}</span>

		<div class="clearfix"></div>
	</div>
	<div class="panel-body">

		<div class="text-center" style="padding:10px;" ng-show="showPrintFilter()" ng-cloak >
			<a  href="#" ng-click="goToPrintFilter($event)" class="btn btn-warning btn-sm" >
				Imprimir resultado de busqueda <span class="badge badge-secondary"> {{ total_count }}</span>
			</a>
		</div>

		<table class="table table-condensed table-hover table-bordered">
			<thead >
				<tr>
					<td class="text-right" style="padding-top: 6px;"> <b>Rango de fechas:</b></td>
					<td colspan="2">
						<div id="rangeDate" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
						    <i class="fa fa-calendar"></i>&nbsp;
						    <span></span> <i class="fa fa-caret-down"></i>
						</div>
					</td>
					<td class="text-right" style="padding-top: 6px;"> <b>Fecha de impresión:</b></td>
					<td colspan="2">
						<input ng-change="getData(1)"  ng-model="filter.print_date" placeholder="" type="text" class="form-control input-xs create-datepicker" />
					</td>
				</tr>
				<tr>
					<th  style="width: 130px;" >
						<div class="input-group input-group-sm">	
                           <input  ng-model="filter.encounter_id" ng-change="getData(1)" placeholder="Con. Num." type="text" class="form-control input-xs" />
                            <span class="input-group-btn">
                                <a ng-click="sortData('encounter_id')" class="btn btn-default btn-sm" >
                                <i class="fa " ng-class="sortClass('encounter_id')"></i></a>
                            </span>
                        </div>
					</th>
					<th  style="width: 130px;" >
						<div class="input-group input-group-sm">
                           <input  readonly="true" placeholder="Fecha" type="text" class="form-control input-xs" />
                            <span class="input-group-btn">
                                <a ng-click="sortData('date')" class="btn btn-default btn-sm" >
                                <i class="fa " ng-class="sortClass('date')"></i></a>
                            </span>
                        </div>
					</th>
					<th  style="width: 130px;" >
						<div class="input-group input-group-sm">
							<select class="form-control form-control-sm" ng-model="filter.insurance" ng-change="getData(1)">
								<optgroup label="Seguro">
							    	<option value="">Todos*</option>
							  	</optgroup>
							  	<optgroup label="Seleccionado">
							    	<?php foreach ($_['options_insurances'] as $key => $value) : ?>
										<option value="<?= $value['name']?>" ><?= $value['name']?></option>
									<?php endforeach;?>
							  	</optgroup>x
							</select>
							<!--
	                           <input  ng-model="filter.insurance" ng-change="getData(1)" placeholder="Insurance" type="text" class="form-control input-xs" />
	                            <span class="input-group-btn">
	                                <a ng-click="sortData('insurance')" class="btn btn-default btn-sm" >
	                                <i class="fa " ng-class="sortClass('insurance')"></i></a>
	                            </span>
	                        -->
                        </div>
					</th>
					<th   style="width: 180px;" >
						<div class="input-group input-group-sm">
                          	<input  ng-model="filter.biller" ng-change="getData(1)" placeholder="Facturador" type="text" class="form-control input-xs" />
                            <span class="input-group-btn">
                                <a ng-click="sortData('biller')" class="btn btn-default btn-sm" >
                                <i class="fa " ng-class="sortClass('biller')"></i></a>
                            </span>
                        </div>
					</th>
					<th class="col-xs-1 col-sm-1 col-md-1">
						<select class="form-control input-sm" ng-change="getData(1)" ng-model="filter.status" >
							<option value="">Estatus</option>
							<?php foreach ($_['status'] as $key => $value) : ?>
								<option value="<?= $key?>"><?= $value ?></option>
							<?php endforeach; ?>
						</select>
					</th>
					<th style="width: 70px;">Cargos</th>
					<th style="width: 70px;">Pago</th>
					<th style="width: 70px;">Anulación</th>
					<th style="width: 70px;">Vencido</th>
					<th>Comentarios</th>
					<th style="width: 50px;" >Impresion</th>
					<th style="width: 50px;" >Ultima impresion
					</th>
				</tr>
			</thead>
			<tbody>
				<tr ng-cloak dir-paginate="bill in billing | itemsPerPage:itemsPerPage" current-page="currentPage" total-items="total_count">
					<td class="text-center">	
						<div class="btn-group" > 
							<a 	target="_blank" title="Abrir Factura"  data-toggle="tooltip"
								ng-href="/billing/detail/{{ bill.encounter_id }}" 
								class="btn btn-info btn-xs" style="min-width: 58px;" 
							>  {{ bill.encounter_id }} </a> 
							<a  title="Editar factura"  data-toggle="tooltip"
								ng-disabled="disabledBill(bill)"
								class="btn btn-success btn-xs"
								ng-click="editBill(bill)"
							> <i class="fa fa-edit"></i> </a>
						</div>
					</td>	
					<td > <i class="fa fa-clock-o" title="{{ngHelper.formatDate(bill.date_bill)}}" data-toggle="tooltip"></i> {{ ngHelper.normalDate(bill.date_bill)}} </td>
					<td >{{bill.insurance_title}} </td>
					<td >{{bill.biller}} </td>
					<td >{{arr_status[bill.status]}} </td>
					<td class="text-right">{{bill.total_charge}} </td>
					<td class="text-right">{{bill.total_paid}} </td>
					<td class="text-right">{{bill.total_write_off}} </td>
					<td class="text-right">{{bill.total_due}} </td>
					<td >
						<div ng-hide="bill.edit==1">
							<a href="#"  ng-click="editComments(bill)"><i class="fa fa-pencil" data-toggle="tooltip" title="Editar comentarios"></i></a> {{ bill.comments}}
						</div>
						<input ng-keyup="updateComments($event,bill)" type="text" ng-show="bill.edit==1" ng-model="bill.comments" class="form-control input-xs focus-selected"  />
						<!---
						<textarea ng-show="bill.edit==1" ng-model="bill.comments" class="form-control input-xs focus-selected" ng-change="updateComments(bill)"></textarea>
						-->
					</td>
					<td class="">
						<label class="switch" style="margin:0px;" ng-show="canPrint(bill)">
							<input  ng-true-value="1" ng-false-value="0" ng-model="bill.print" ng-change="toggle_print(bill)" type="checkbox"  />
							<span class="on">Si</span>
							<span class="off">No</span>
						</label>
					</td>
					<td>
						<div ng-show="bill.print_user_nickname">
							<i data-toggle="tooltip" title="Por: {{ bill.print_user_nickname}}" class="fa fa-user" aria-hidden="true"></i>
							<i data-toggle="tooltip" title="{{ ngHelper.formatDate(bill.print_date)}}" class="fa fa-clock-o" aria-hidden="true"></i>
						</div>
					</td>
				</tr>
				<tr ng-cloak >
					<td ng-show="(!total_count)" class="text-center" colspan="11">
						<h3>Busqueda sin resultados</h3>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="panel-footer">
		<div class="row">
			<div class="col-lg-12 text-right">
				<dir-pagination-controls 
					max-size="8" 
					direction-links="true" 
					boundary-links="false" 
					on-page-change="getData(newPageNumber)" ></dir-pagination-controls>
			</div>
		</div>
		
	</div>
</div>