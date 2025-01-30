<div class="row">
	<div class="col-lg-12">
		<a target="_blank"  href="<?= site_url('/encounter/medication/printMedications/'.$_['patient_id'])?>" class="btn btn-warning btn-xs"> <i class="fa fa-print"></i> Imprimir </a>
		<br><br>
		<div class="table-responsive">
			<table class="table table-condensed table-bordered" style="font-size:12px;">
				<thead>
					<tr>
						<th class="col-md-2">Fecha</th>
						<th class="col-md-2">Titulo</th>
						<th class="col-md-1">Cantidad</th>
						<th class="col-md-1">Crónico</th>
						<th class="col-md-5">Indicaciones</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="medication in data.list_medications">
						<td>{{ medication.date }}</td>
						<td>{{ medication.title }} {{ medication.dose }}</td>
						<td>{{ medication.amount }}</td>
						<td><span ng-class="medication.chronic==='Yes' ? 'text-danger' : 'text-muted' ">{{ medication.chronic }}</span></td>
						<td>{{ medication.directions }}</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
