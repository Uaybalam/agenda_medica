<style type="text/css">
	#panel-encounter table th,
	#panel-encounter table td b{
		/*color:#3498db;*/
	}
	
	#panel-encounter table tr:hover td{
		background-color:#95a5a6 !important;
	}
	/*
	.table.table-hover-app tr th
	{
		color:#fff !important;
		background-color:#2C3E50 !important;
	}
	.table.table-hover-app tr td
	{
		color:#fff !important;
		background-color:#95a5a6 !important;
	}*/

	
</style>
<div class="panel panel-info" >
    <div class="panel-heading">
      	<div class="row">
      		<div class="col-md-6">
      			<h4 class="panel-title">
		      		<a data-toggle="collapse" data-parent="#accordion" href="#panel-encounter"> Consulta <i class="fa fa-chevron-down"></i> </a>
		      	</h4>
      		</div>
      		<div class="col-md-6 text-right">
      			<h4 class="panel-title">
		      		<a ng-href="/patient/chart/{{ data.information.patient_id}}" target="_blank"> <i class="icon-folder-plus"></i> Abrir expediente del paciente</a>
		      	</h4>
      		</div>
      	</div>
    </div>
    <div id="panel-encounter" class="panel-collapse collapse" style="font-size:12px;">
      	<?php $this->template->render_view('billing/section.encounter.body.content'); ?>
    </div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<div class="row">
			<div class="col-md-4">
				<b>Paciente:</b> <span title="Patient ID"> ({{ data.information.patient_id}})</span> {{ data.information.last_name+' '+ data.information.name}}
			</div>
			<div class="col-md-4">
				<b>ID de consulta:</b> {{ data.information.encounter_id }}
			</div>
			<div class="col-md-4">
				<b>Seguro: </b> {{ data.billing.insurance_title }} ( {{ data.billing.insurance_number }} )
			</div>
		</div>
	</div>
	<div class="panel-body form-horizontal">
		
		<?= $this->template->render_view('billing/tab.billing.patient', $_ ); ?>
		<hr style="border-top:2px solid #ecf0f1;margin-bottom:20px;">
		
		<?= $this->template->render_view('billing/tab.billing.insurance', $_ ); ?>
		<hr style="border-top:2px solid #ecf0f1;margin-bottom:20px;">
		
		<?= $this->template->render_view('billing/tab.billing.table', $_ ); ?>
		<hr style="border-top:2px solid #ecf0f1;margin-bottom:20px;">
		
		<?= $this->template->render_view('billing/tab.billing.payment', $_ ); ?>
	</div>
	<div class="panel-footer">
		<div class="row">
			<div class="col-md-6">
			<!--
				<a target="_blank" ng-href="/billing/export-csv/{{ data.billing.id }}?test=1" ng-show="data.can_print" class="btn btn-danger btn-sm"> <i class="fa fa-file-excel-o" aria-hidden="true"></i> CSV Text </a>
			-->
				<a target="_blank" ng-href="/billing/export-csv/{{ data.billing.id }}" ng-show="data.can_print" class="btn btn-warning btn-sm"> <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar CSV </a>
			
				<a target="_blank" ng-href="/billing/pdfc/{{ data.billing.id }}" ng-show="data.can_print" class="btn btn-warning btn-sm"> <i class="fa fa-print" aria-hidden="true"></i> Imprimir</a>
				
				<a target="_blank" ng-href="/billing/pdfc/{{ data.billing.id }}?printLines=1" ng-show="data.can_print" class="btn btn-warning btn-sm"> <i class="fa fa-print" aria-hidden="true"></i> Imprimir Contenido</a>
			</div>
			<div class="col-md-6 text-right"> 
				<button ng-show="!data.not_edit" class="btn btn-primary btn-sm submit" ng-click="updateBilling()"> Guardar </button>
				<button ng-show="data.billing.status==0" class="btn btn-primary btn-sm submit" ng-click="doneBilling()"> Completar  </button>
				<button ng-show="!data.not_edit" class="btn btn-success btn-sm submit" ng-click="openBillDenied()"> Denegar  </button>
				<button ng-hide="checkBtnComments()" class="btn btn-success btn-sm submit" ng-click="openBillComments()"> Comentarios  </button>
			</div>
		</div>
		 
	</div>
</div>
