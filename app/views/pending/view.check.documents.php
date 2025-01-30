<div class="panel panel-default panel-custom">
	<div class="panel-heading"><label>Pendiente de completar documentos </label></div>
	<div class="panel-body" ng-cloak >
		<ul class="nav nav-tabs" id="pending-documents">
			  <li class="active"><a data-toggle="tab" href="#tab-results"> Desde resultados <span class="badge">{{ pagination.results.total_count.toLocaleString() }}</span></a></li>
			  <li><a data-toggle="tab" href="#tab-chart"> Desde el expediente del paciente <span class="badge">{{ pagination.chart.total_count.toLocaleString()}}</span> </a></li>
		</ul>
		<div class="tab-content" style="padding-top:20px;">
			<?php $this->template->render_view('pending/renderview-documents-from-chart'); ?>
			<?php $this->template->render_view('pending/renderview-documents-from-results'); ?>
		</div>
	</div>
</div>
