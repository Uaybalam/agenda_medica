<div class="row">
	<div class="col-lg-12">
		<a target="_blank" href="<?= site_url('/diagnosis/printDiagnosis/'.$_['patient_id'])?>" class="btn btn-warning btn-xs"> <i class="fa fa-print"></i> Imprimir </a>
		<br><br>
		<div class="table-responsive">
			<table class="table table-condensed table-bordered" style="font-size:12px;">
				<thead>
					<tr>
						<th class="col-md-2">Fecha</th>
						<th class="col-md-1">Crónico</th>
						<th class="col-md-9">Diagnostico</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="diagnosis in data.list_diagnostics">
						<td>{{ diagnosis.signed_at }}</td>
						<td><span ng-class="diagnosis.chronic==='Yes' ? 'text-danger' : 'text-muted' ">{{ diagnosis.chronic }}</span></td>
						<td>{{ diagnosis.comment }}</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
