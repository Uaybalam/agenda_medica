
<div class="row" style="font-size:12px;">
	<div class="col-md-12 col-lg-6" >
		<div class="panel panel-default custom-widget">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-4"> <label>Solicitudes de consulta</label></div>
					<div class="col-md-2">
						<?php if($this->current_user->access_type ==='root' || $this->current_user->access_type ==='admin' || $this->current_user->access_type ==='manager' ) : ?>
							<button ng-click="action_checkout_cancel.open()" ng-show="data.encounter.checked_out_id>0" class="btn btn-danger btn-xs">Cancelar Salida</button>
						<?php endif; ?>
					</div>
					<div class="col-md-6 text-right">
						
						<a ng-show="data.encounter.appointment_id" title="Cita" data-placement="bottom" data-toggle="tooltip" 
							ng-href="/appointment/detail/{{ data.encounter.appointment_id }}" 
							class="btn btn-info btn-xs"  > <i class="fa fa-calendar"></i> </a>

						<a title="Historial del paciente" data-placement="bottom" data-toggle="tooltip" ng-href="/patient/chart/{{ data.patient.id }}" class="btn btn-info btn-xs"  > <i class="icon-folder-plus"></i> </a>
						
						<a title="Detalle del paciente" data-placement="bottom" data-toggle="tooltip" class="btn btn-info btn-xs" ng-href="/patient/detail/{{ data.patient.id }}" ><i class="fa fa-user "></i> </a>

						<div class="remark"  ng-show="data.encounter.checked_out_id==0" >
							<button ng-click="action_encounter.open()" ng-show="data.encounter.appointment_id" title="Patient checkout" data-toggle="tooltip" data-placement="bottom"  class="btn btn-success btn-xs"> <i class="fa fa-check-circle-o"></i> Salida </button>
						</div>

					</div>
				</div>
			</div>
			<div class="panel-body" style="height:auto;min-height:238px;" >
				<table  ng-cloak class="table table-hover-app table-condensend table-bordered">
					<tbody>
						<tr>
							<th class="col-md-3">ID de consulta</th>
							<td class="col-md-9" colspan="3" > {{ data.encounter.id }}</td>
						</tr>
						<tr>
							<th class="col-md-3">Firmado el</th>
							<td class="col-md-9" colspan="3"> {{ data.encounter.signed_at }}</td>
						</tr>
						<tr>
							<th class="col-md-3">Firmado por</th>
							<td class="col-md-9" colspan="3"> {{ data.encounter.signed_by }}</td>
						</tr>
						<tr>
							<th class="col-md-3">Siguiente cita</th>
							<td class="col-md-9" colspan="3"> {{ data.encounter.next_appointment }}</td>
						</tr>
						<tr>
							<th class="col-md-3">Paciente</th>
							<td class="col-md-9" colspan="3"> {{ data.patient.name }} {{ data.patient.middle_name }} {{ data.patient.last_name }}, Fecha de nacimiento ({{ data.patient.date_of_birth }})
							</td>
						</tr>
						<tr>
							<th class="col-md-3">Procedimiento</th>
							<td class="col-md-9" colspan="3"> {{ data.encounter.procedure_text }} </td>
						</tr>
						<tr>
							<th class="col-md-3">Educación del paciente</th>
							<td class="col-md-9" colspan="3"> {{ data.encounter.procedure_patient_education }}</td>
						</tr>
						<tr>
							<th class="col-md-3">Motivo de consulta</th>
							<td class="col-md-9" colspan="3"> {{ data.encounter.chief_complaint }}</td>
						</tr>
						<tr>
							<th class="col-md-3">Revisado por</th>
							<td class="col-md-9" colspan="3"> {{ data.checked_out.digital_signature }}</td>
						</tr>
					</tbody>

					<tbody ng-show="data.encounter.has_insurance==1">
						<tr >
							<th colspan="4" class="text-center" style="text-align: center;">Información del seguro</th>
						</tr>
						<tr>
							<th class="col-md-3">Seguro</th>
							<td class="col-md-3"> {{ data.encounter.insurance_title }}</td>
							<th class="col-md-3">Numero de seguro</th>
							<td class="col-md-3"> {{ data.encounter.insurance_number }}</td>
						</tr>
					</tbody>
					<tbody ng-show="data.patient.membership_name!=''">
						<tr >
							<th colspan="4" class="text-center" style="text-align: center;">Membresia</th>
						</tr>
						<tr >
							<th class="col-md-3">Nombre</th>
							<td class="col-md-3"> {{ data.patient.membership_name }}</td>
							<th class="col-md-3">Fecha</th>
							<td class="col-md-3"> {{ data.patient.membership_date }}</td>
						</tr>
						<tr>
							<th class="col-md-3">Tipo</th>
							<td class="col-md-3"> {{ data.patient.membership_type }}</td>
							<th class="col-md-3">Notas</th>
							<td class="col-md-3"> {{ data.patient.membership_notes }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-md-12 col-lg-6" >
		<div class="panel panel-default custom-widget" >
			<div class="panel-heading">
				<div class="row" ng-cloak >
					<div class="col-md-4"> <label> Factura </label>
					
						<label style="margin:0px;margin-left: 5px;vertical-align:middle;width: 100px;" data-toggle="tooltip" data-placement="bottom" title="Active invoice"  class="switch"  ng-show="data.encounter.checked_out_id==0" >
							<input ng-disabled="data.encounter.has_insurance==0" ng-change="action_invoice.toggleActive()" type="checkbox"  ng-true-value="1" ng-false-value="0" ng-model="data.invoice.enabled"> 
							<span class="on">Habilitar</span>
							<span class="off">Deshabilitar</span>
						</label>
					</div>
					<div class="col-md-8 text-right">
					
						<button ng-click="previous_charges()"  data-toggle="tooltip" data-placement="bottom" title="History invoices from current Pt." class="btn btn-xs btn-success"> 
							<i class="fa fa-history" aria-hidden="true"></i> 
							Cargos Previos
						</button>

						<button ng-disabled="data.invoice.enabled==0" ng-hide="data.invoice.status!=0" ng-show="data.encounter.checked_out_id==0" ng-click="action_invoice.open()"  title="Editar factura" data-toggle="tooltip" data-placement="bottom"  class="btn btn-success btn-xs"> <i class="fa fa-edit"></i></button>

						<a id="printInvoicePdf" href="/encounter/invoice/{{data.encounter.id}}/pdf" target="_blank" ng-show="data.invoice.status==1" title="Print PDF" data-toggle="tooltip" data-placement="bottom"  class="btn btn-warning btn-xs"> <i class="fa fa-print"></i></a>
					</div>
				</div>
			</div>	
			<div class="panel-body" style="height:auto;min-height:238px;"  >
				<div class="col-lg-12"  ng-cloak  >
					<div class="row">
						<div class="col-md-6">
							<table class="table table-hover-app table-condensend table-bordered">
								<tbody>
									<tr>
										<th class="col-xs-4 col-sm-4 col-md-7">Visita a Consultorio</th>
										<td class="col-xs-8 col-sm-8 col-md-5"> {{ data.invoice.office_visit}} </td>
									</tr>
									<tr>
										<th>Laboratorio</th>
										<td> {{ data.invoice.laboratories}}</td>
									</tr>
									<tr>
										<th>Inyección / Vacunas</th>
										<td> {{ data.invoice.injections}}</td>
									</tr>
									<tr>
										<th>Medicamentos</th>
										<td> {{ data.invoice.medications}}</td>
									</tr>
									<tr>
										<th>Procedimientos</th>
										<td> {{ data.invoice.procedures}}</td>
									</tr>
									<tr>
										<th>INS Physical</th>
										<td> {{ data.invoice.physical}}</td>
									</tr>
									<tr>
										<th>ECG</th>
										<td> {{ data.invoice.ecg}}</td>
									</tr>
									
									<tr>
										<th>Ultrasonidos</th>
										<td> {{ data.invoice.ultrasound }}</td>
									</tr>
									<tr>
										<th>Rayos X <!-- remplaced by xray label--></th>
										<td> {{ data.invoice.x_ray}}</td>
									</tr>
									<tr>
										<th>Impresiones</th>
										<td> {{ data.invoice.print_cost }}</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-md-6">
							<table class="table table-hover-app table-condensend table-bordered">
								<tbody>
									<tr>
										<th class="col-xs-4 col-sm-4 col-md-7">Sub total</th>
										<td class="col-xs-8 col-sm-8 col-md-5">{{ data.invoice.subtotal}} </td>
									</tr>
									<tr>
										<th>Saldo pendiente</th>
										<td> {{ data.invoice.open_balance}}</td>
									</tr>
									<tr>
										<th>Tipo de descuento</th>
										<td> {{ data.invoice.discount_type}}</td>
									</tr>
									<tr>
										<th>Descuento</th>
										<td> {{ data.invoice.discount}}</td>
									</tr>
									<tr>
										<th>Total</th>
										<td> {{ data.invoice.total}}</td>
									</tr>
									<tr>
										<th>Pago</th>
										<td> {{ data.invoice.paid}}</td>
									</tr>
									<tr>
										<th>Tipo de pago</th>
										<td> {{ data.invoice.payment_type}}</td>
									</tr>
									<tr>
										<th>Total a pagar</th>
										<td> <span ng-class="data.invoice.balance_due>0 ? 'text-danger' : 'text-success'">{{ data.invoice.balance_due}}</span></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row" style="font-size:12px;" >
	<div class="col-md-6 col-lg-3">
		<div class="panel panel-default custom-widget">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-6"> <label>Medicamentos <span data-toggle="tooltip" data-placement="right" ng-cloak title="Total de medicamentos" class="badge"> {{ data.medications.length }}</span> </label></div>
					<div class="col-md-6 text-right">
					</div>
				</div>
			</div>
			<div class="panel-body" style="height:auto;min-height:200px;" >
				<div class="item-box"  ng-cloak ng-repeat="item in data.medications">
					<span class="text-info" data-toggle="tooltip" title="Nombre de Medicamento">{{item.title +' '+item.dose}} </span><label data-toggle="tooltip" title="Cantidad" class="label label-warning">{{item.amount }}</label>
					<p>{{item.directions}} </p>
				</div>
				<!--
				<blockquote class="blockquote-primary" ng-cloak ng-repeat="med in data.medications">
					<div class="row">
						<div class="col-lg-12" >
							<p> <b>{{med.title }}</b></p>
						</div>
						<div class="col-lg-12">
							Amt: {{med.amount }}
						</div>
					</div>
					<p>{{med.directions}} </p>
				</blockquote>
				-->
			</div>
		</div>
	</div>
	<div class="col-md-6 col-lg-3">
		<div class="panel panel-default custom-widget">
			<div class="panel-heading">
				<label>Solicitudes <span data-toggle="tooltip" data-placement="right" ng-cloak title="Total de resultados" class="badge"> {{ data.results.length }}</span> </label>
			</div>
			<div class="panel-body" style="height:auto;min-height:200px;"  >
				<div class="item-box" ng-repeat="item in data.results">
					<div class="pull-right"> 
						<select ng-show="data.encounter.checked_out_id==0" class="form-control input-xs" ng-model="item.status" ng-change="action_results.change_status(item)">
							<option value="1">Nuevo</option>
							<option value="2">No afiliado</option>
							<option value="3">Enviado</option>
							<option value="5">Realizado</option>
							<option value="6">Rechazado</option>
							<option value="7">Pendiente</option> 
						</select>
						<b ng-show="data.encounter.checked_out_id>0">{{ data.status.result[item.status] }}</b>
					</div>
					<span class="text-info" data-toggle="tooltip" title="Nombre de Solicitud">{{item.title }}</span> <label data-toggle="tooltip" title="Tipo de resultado" class="label label-warning">{{item.type_result}}</label>
					<p>{{item.comments }}</p>
				</div>
				<!--
				<blockquote class="blockquote-primary" ng-cloak ng-repeat="result in data.results">
					<div class="row">
						<div class="col-xs-6 col-md-6" >
							<p> <b>{{result.title }}</b> ({{ result.type_result}}) </p>
							<p>{{result.comments }}</p>
						</div>
						<div class="col-xs-6 col-md-6">

							<select ng-show="data.encounter.checked_out_id==0" class="form-control input-xs" ng-model="result.status" ng-change="action_results.change_status(result)">
								<option value="1">New</option>
								<option value="2">Unaffiliated</option>
								<option value="3">Sent out</option>
								<option value="5">Done</option>
								<option value="6">Refused</option>
								<option value="7">Pending</option>
							</select>
							<span ng-show="data.encounter.checked_out_id>0">{{ data.status.result[result.status] }}</span>
						</div>
					</div>
				</blockquote>
			-->
			</div>
		</div>
	</div>
	<div class="col-md-6 col-lg-3">
		<div class="panel panel-default custom-widget">
			<div class="panel-heading"> 
				<div class="row">
					<div class="col-xs-6">
						<label>Derivaciones  <span data-toggle="tooltip" data-placement="right" ng-cloak title="Total de derivaciones" class="badge"> {{ data.referrals.length }}</span></label>
					</div>
					<div class="col-xs-6 text-right">
						<button  type="button" ng-click="action_referrals.open()" class="btn btn-success btn-xs"> <i class="fa fa-plus" aria-hidden="true"></i> Derivación </button>
					</div>
				</div>
			</div>
			<div class="panel-body" style="height:auto;min-height:200px;">
				<div class="item-box" ng-cloak ng-repeat="item in data.referrals">
					<div class="pull-right">
						<button title="Editar" data-toggle="tooltip" ng-show="item.user_created_nickname" type="button" class="btn btn-xs btn-info" ng-click="action_referrals.edit($index)"> <i  class="fa fa-pencil"></i> </button>
						<button title="Imprimir" data-toggle="tooltip" type="button" class="btn btn-xs btn-success" ng-click="action_referrals.print(item, $index)"> <i  class="fa fa-print"></i> </button>
					</div>
					<span class="text-info" data-toggle="tooltip" title="Servicio">{{item.service}}</span> <label data-toggle="tooltip" title="Especialidad" class="label label-default" style="font-size:12px;">{{item.speciality }}</label> <br>
					
					<p >{{item.reason }} </p>
					<div class="clearfix"></div>
				</div>
				<!--
				<blockquote class="blockquote-primary" ng-cloak ng-repeat="ref in data.referrals">
					<div class="row">
						<div class="col-xs-8 col-md-8" >
							<p> <b>{{ref.speciality }}</b> 
								v
								
							</p>
						</div>
						<div class="col-xs-4 col-md-4">
							Service: {{ref.service }}
						</div>
					</div>
					<p>{{ref.reason }} </p>
				</blockquote>
				-->
			</div>
		</div>
	</div>
	<div class="col-md-6 col-lg-3">
		<div class="panel panel-default custom-widget">
			
			<div class="panel-heading"> 
				<div class="row">
					<div class="col-sm-6 col-md-6">
						<label >
							Addendums <span data-toggle="tooltip" data-placement="right" ng-cloak title="Total addendums" class="badge"> {{ data.addendums.length }}</span>
						</label>
					</div >
					<div class="col-sm-6 col-md-6 text-right">
						<button  type="button" ng-click="action_addendum.open()" class="btn btn-success btn-xs"> <i class="fa fa-plus-square" aria-hidden="true"></i> Addendum </button>
					</div>
				</div>
			</div>
			<div class="panel-body" style="height:auto;min-height:200px;">
				<div class="item-box"  ng-cloak ng-repeat="item in data.addendums" >
					{{item.notes}}
					<br>
					<i class="fa fa-user" data-toggle="tooltip" title="User: {{item.nick_name}}"></i> {{ item.user}}<br>
					<i class="fa fa-clock-o" data-toggle="tooltip" title="Date: {{item.date}}"></i> {{ ngHelper.humanDate(item.create_at) }}<br>
				</div>
				<!--
				<blockquote class="blockquote-primary" ng-cloak ng-repeat="addendum in data.addendums">
					<div class="row">
						<div class="col-xs-12 col-md-12" >
							<p> <b>By: {{addendum.user }}</b> ( {{ addendum.date }} )</p>
						</div>
					</div>
					<p>{{addendum.notes}} </p>
				</blockquote>
			-->
			</div>
		</div>
	</div>
</div>

